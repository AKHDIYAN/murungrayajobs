<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Pekerjaan;
use App\Models\Lamaran;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyDashboardController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->middleware('company');
        $this->imageService = $imageService;
    }

    /**
     * Company dashboard
     */
    public function index()
    {
        $company = Auth::guard('company')->user();

        // Statistics
        $totalJobs = Pekerjaan::where('id_perusahaan', $company->id_perusahaan)->count();
        $activeJobs = Pekerjaan::where('id_perusahaan', $company->id_perusahaan)
                               ->aktif()
                               ->count();
        $expiredJobs = Pekerjaan::where('id_perusahaan', $company->id_perusahaan)
                                ->berakhir()
                                ->count();
        $pendingJobs = Pekerjaan::where('id_perusahaan', $company->id_perusahaan)
                                ->where('status', 'Pending')
                                ->count();

        // Application statistics
        $totalApplications = Lamaran::whereHas('pekerjaan', function($q) use ($company) {
                                        $q->where('id_perusahaan', $company->id_perusahaan);
                                    })->count();
        $pendingApplications = Lamaran::whereHas('pekerjaan', function($q) use ($company) {
                                          $q->where('id_perusahaan', $company->id_perusahaan);
                                      })
                                      ->where('status', 'Pending')
                                      ->count();

        // Recent jobs
        $recentJobs = Pekerjaan::with(['kecamatan', 'kategori'])
                               ->where('id_perusahaan', $company->id_perusahaan)
                               ->orderBy('tanggal_posting', 'desc')
                               ->limit(5)
                               ->get();

        // Recent applications
        $recentApplications = Lamaran::with(['user.kecamatan', 'pekerjaan'])
                                     ->whereHas('pekerjaan', function($q) use ($company) {
                                         $q->where('id_perusahaan', $company->id_perusahaan);
                                     })
                                     ->orderBy('tanggal_terkirim', 'desc')
                                     ->limit(5)
                                     ->get();

        return view('company.dashboard', compact(
            'company',
            'totalJobs',
            'activeJobs',
            'expiredJobs',
            'pendingJobs',
            'totalApplications',
            'pendingApplications',
            'recentJobs',
            'recentApplications'
        ));
    }

    /**
     * Show profile
     */
    public function profile()
    {
        /** @var \App\Models\Perusahaan $company */
        $company = Auth::guard('company')->user();
        $company = $company->load('kecamatan'); // Assign hasil load

        $kecamatanList = \App\Models\Kecamatan::all();

        return view('company.profile', compact('company', 'kecamatanList'));
    }

    /**
     * Update profile
     */
    public function updateProfile(UpdateCompanyRequest $request)
    {
        try {
            $company = Auth::guard('company')->user();
            $data = $request->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $data['logo'] = $this->imageService->updateCompanyLogo(
                    $request->file('logo'),
                    $company->logo
                );
            }

            // Handle password update
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            // Update company
            /** @var \App\Models\Perusahaan $company */
            $company->update($data);

            return back()->with('success', 'Profil perusahaan berhasil diperbarui.');
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
        $company = Auth::guard('company')->user();
        return view('company.settings', compact('company'));
    }
}
