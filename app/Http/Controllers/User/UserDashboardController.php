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
}
