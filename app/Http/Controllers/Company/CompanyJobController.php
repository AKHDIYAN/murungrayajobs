<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Pekerjaan;
use App\Models\Kecamatan;
use App\Models\Sektor;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyJobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->middleware('company');
        $this->jobService = $jobService;
    }

    /**
     * List all company jobs
     */
    public function index(Request $request)
    {
        $company = Auth::guard('company')->user();

        $query = Pekerjaan::with(['kecamatan', 'kategori'])
                          ->where('id_perusahaan', $company->id_perusahaan);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->aktif();
            } elseif ($request->status === 'berakhir') {
                $query->berakhir();
            } elseif ($request->status === 'pending') {
                $query->where('status', 'Pending');
            } elseif ($request->status === 'ditolak') {
                $query->where('status', 'Ditolak');
            }
        }

        $jobs = $query->orderBy('tanggal_posting', 'desc')
                     ->paginate(10)
                     ->withQueryString();

        return view('company.jobs.index', compact('jobs'));
    }

    /**
     * Show create job form
     */
    public function create()
    {
        $kecamatanList = Kecamatan::all();
        $kategoriList = Sektor::all();

        return view('company.jobs.create', compact('kecamatanList', 'kategoriList'));
    }

    /**
     * Store new job
     */
    public function store(StoreJobRequest $request)
    {
        try {
            $company = Auth::guard('company')->user();

            $job = $this->jobService->createJob($request->validated(), $company);

            return redirect()->route('company.jobs.show', $job->id_pekerjaan)
                           ->with('success', 'Lowongan pekerjaan berhasil dibuat. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat lowongan: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Show job detail
     */
    public function show($id)
    {
        $company = Auth::guard('company')->user();

        $job = Pekerjaan::with(['kecamatan', 'kategori', 'lamaran.user'])
                        ->where('id_perusahaan', $company->id_perusahaan)
                        ->findOrFail($id);

        // Statistics for this job
        $totalApplicants = $job->lamaran->count();
        $pendingApplicants = $job->lamaran->where('status', 'Pending')->count();
        $acceptedApplicants = $job->lamaran->where('status', 'Diterima')->count();

        return view('company.jobs.show', compact('job', 'totalApplicants', 'pendingApplicants', 'acceptedApplicants'));
    }

    /**
     * Show edit job form
     */
    public function edit($id)
    {
        $company = Auth::guard('company')->user();

        $job = Pekerjaan::where('id_perusahaan', $company->id_perusahaan)
                        ->findOrFail($id);

        $kecamatanList = Kecamatan::all();
        $kategoriList = Sektor::all();

        return view('company.jobs.edit', compact('job', 'kecamatanList', 'kategoriList'));
    }

    /**
     * Update job
     */
    public function update(UpdateJobRequest $request, $id)
    {
        try {
            $company = Auth::guard('company')->user();

            // Verify ownership
            $job = Pekerjaan::where('id_perusahaan', $company->id_perusahaan)
                            ->findOrFail($id);

            $this->jobService->updateJob($id, $request->validated());

            return redirect()->route('company.jobs.show', $id)
                           ->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui lowongan: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete job
     */
    public function destroy($id)
    {
        try {
            $company = Auth::guard('company')->user();

            // Verify ownership
            $job = Pekerjaan::where('id_perusahaan', $company->id_perusahaan)
                            ->findOrFail($id);

            $this->jobService->deleteJob($id);

            return redirect()->route('company.jobs.index')
                           ->with('success', 'Lowongan pekerjaan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus lowongan: ' . $e->getMessage());
        }
    }
}
