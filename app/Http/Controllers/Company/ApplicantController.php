<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Pekerjaan;
use App\Services\ApplicationService;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    protected $applicationService;
    protected $pdfService;

    public function __construct(ApplicationService $applicationService, PDFService $pdfService)
    {
        $this->middleware('company');
        $this->applicationService = $applicationService;
        $this->pdfService = $pdfService;
    }

    /**
     * List all applicants for company
     */
    public function index(Request $request)
    {
        $company = Auth::guard('company')->user();

        // Get company jobs for filter
        $companyJobs = Pekerjaan::where('id_perusahaan', $company->id_perusahaan)
                                ->orderBy('nama_pekerjaan')
                                ->get();

        // Build filters array
        $filters = [];
        if ($request->filled('id_pekerjaan')) {
            $filters['id_pekerjaan'] = $request->id_pekerjaan;
        }
        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }
        if ($request->filled('id_kecamatan')) {
            $filters['id_kecamatan'] = $request->id_kecamatan;
        }
        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        // Get applicants
        $applicants = $this->applicationService->getApplicationsByCompany(
            $company->id_perusahaan,
            $filters
        );

        $kecamatanList = \App\Models\Kecamatan::all();

        return view('company.applicants.index', compact('applicants', 'companyJobs', 'kecamatanList'));
    }

    /**
     * Show applicant detail
     */
    public function show($id)
    {
        $company = Auth::guard('company')->user();

        $applicant = Lamaran::with(['user.kecamatan', 'pekerjaan.kecamatan', 'pekerjaan.kategori'])
                            ->whereHas('pekerjaan', function($q) use ($company) {
                                $q->where('id_perusahaan', $company->id_perusahaan);
                            })
                            ->findOrFail($id);

        return view('company.applicants.show', compact('applicant'));
    }

    /**
     * Download applicant detail as PDF (FITUR UTAMA)
     */
    public function downloadPDF($id)
    {
        try {
            $company = Auth::guard('company')->user();

            return $this->pdfService->generateApplicantDetailPDF($id, $company->id_perusahaan);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download all applicants as PDF (FITUR UTAMA)
     */
    public function downloadAllPDF(Request $request)
    {
        try {
            $company = Auth::guard('company')->user();

            // Build filters
            $filters = [];
            if ($request->filled('id_pekerjaan')) {
                $filters['id_pekerjaan'] = $request->id_pekerjaan;
            }
            if ($request->filled('status')) {
                $filters['status'] = $request->status;
            }

            // Get applicants
            $applicants = $this->applicationService->getApplicationsByCompany(
                $company->id_perusahaan,
                $filters
            );

            if ($applicants->isEmpty()) {
                return back()->with('error', 'Tidak ada data pelamar untuk diunduh.');
            }

            return $this->pdfService->generateApplicantsListPDF($applicants, $company);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Update applicant status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Pending,Ditolak',
        ]);

        try {
            $company = Auth::guard('company')->user();

            // Verify ownership
            $lamaran = Lamaran::whereHas('pekerjaan', function($q) use ($company) {
                               $q->where('id_perusahaan', $company->id_perusahaan);
                           })
                           ->findOrFail($id);

            $this->applicationService->updateStatus($id, $request->status);

            return back()->with('success', 'Status lamaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
