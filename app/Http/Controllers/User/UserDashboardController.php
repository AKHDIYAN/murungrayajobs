<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Lamaran;
use App\Models\Pekerjaan;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserDashboardController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->middleware('user');
        $this->imageService = $imageService;
    }

    /**
     * User dashboard
     */
    public function index()
    {
        $user = Auth::guard('web')->user();

        // Statistics
        $totalApplications = Lamaran::where('id_user', $user->id_user)->count();
        $pendingApplications = Lamaran::where('id_user', $user->id_user)
                                      ->where('status', 'Pending')
                                      ->count();
        $acceptedApplications = Lamaran::where('id_user', $user->id_user)
                                       ->where('status', 'Diterima')
                                       ->count();
        $rejectedApplications = Lamaran::where('id_user', $user->id_user)
                                       ->where('status', 'Ditolak')
                                       ->count();

        // Pelatihan Statistics
        $totalPelatihan = \App\Models\PelatihanPeserta::where('id_user', $user->id_user)->count();
        $pelatihanDiterima = \App\Models\PelatihanPeserta::where('id_user', $user->id_user)
                                                          ->where('status_pendaftaran', 'Diterima')
                                                          ->count();

        // Recent applications
        $recentApplications = Lamaran::with(['pekerjaan.perusahaan', 'pekerjaan.kecamatan'])
                                     ->where('id_user', $user->id_user)
                                     ->orderBy('tanggal_terkirim', 'desc')
                                     ->limit(5)
                                     ->get();

        // Recommended jobs (based on user kecamatan)
        $recommendedJobs = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
                                    ->aktif()
                                    ->where('id_kecamatan', $user->id_kecamatan)
                                    ->whereNotIn('id_pekerjaan', function($query) use ($user) {
                                        $query->select('id_pekerjaan')
                                              ->from('lamaran')
                                              ->where('id_user', $user->id_user);
                                    })
                                    ->orderBy('tanggal_posting', 'desc')
                                    ->limit(6)
                                    ->get();

        return view('user.dashboard', compact(
            'user',
            'totalApplications',
            'pendingApplications',
            'acceptedApplications',
            'rejectedApplications',
            'totalPelatihan',
            'pelatihanDiterima',
            'recentApplications',
            'recommendedJobs'
        ));
    }

    /**
     * Show profile
     */
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();
        $user = $user->load('kecamatan'); // Assign hasil load

        $kecamatanList = \App\Models\Kecamatan::all();

        return view('user.profile', compact('user', 'kecamatanList'));
    }

    /**
     * Update profile
     */
    public function updateProfile(UpdateUserRequest $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::guard('web')->user();
            $data = $request->validated();

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $data['foto'] = $this->imageService->updateUserPhoto(
                    $request->file('foto'),
                    $user->foto
                );
            }

            // Handle password update
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            // Update user
            $user->update($data);

            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Settings page
     */
    public function settings()
    {
        $user = Auth::guard('web')->user();
        return view('user.settings', compact('user'));
    }

    /**
     * Update profile with new validation
     */
    public function updateProfileNew(\App\Http\Requests\UpdateUserProfileRequest $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::guard('web')->user();
            
            // Update basic info
            $user->nama = $request->nama;
            $user->nik = $request->nik;

            // Handle foto upload (wajib)
            if ($request->hasFile('foto')) {
                // Delete old photo if exists
                if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                    \Storage::disk('public')->delete($user->foto);
                }
                
                $fotoPath = $request->file('foto')->store('uploads/user/foto', 'public');
                $user->foto = $fotoPath;
            }

            // Handle KTP upload (optional)
            if ($request->hasFile('ktp')) {
                // Delete old KTP if exists
                if ($user->ktp && \Storage::disk('public')->exists($user->ktp)) {
                    \Storage::disk('public')->delete($user->ktp);
                }
                
                $ktpPath = $request->file('ktp')->store('uploads/user/ktp', 'public');
                $user->ktp = $ktpPath;
            }

            // Handle sertifikat upload (optional, multiple files)
            if ($request->hasFile('sertifikat')) {
                $sertifikatPaths = [];
                
                // Get existing certificates
                if ($user->sertifikat) {
                    $sertifikatPaths = is_array($user->sertifikat) 
                        ? $user->sertifikat 
                        : json_decode($user->sertifikat, true) ?? [];
                }

                foreach ($request->file('sertifikat') as $file) {
                    $path = $file->store('uploads/user/sertifikat', 'public');
                    $sertifikatPaths[] = $path;
                }

                $user->sertifikat = json_encode($sertifikatPaths);
            }

            $user->save();

            return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Update password
     */
    public function updatePassword(\App\Http\Requests\UpdatePasswordRequest $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::guard('web')->user();
            
            // Update password
            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui password: ' . $e->getMessage());
        }
    }

    /**
     * Update email
     */
    public function updateEmail(\App\Http\Requests\UpdateEmailRequest $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::guard('web')->user();
            
            // Update email
            $user->email = $request->email;
            $user->save();

            return back()->with('success', 'Email berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui email: ' . $e->getMessage());
        }
    }
}
