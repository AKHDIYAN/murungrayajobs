<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Pekerjaan;
use App\Models\ActivityLog;
use App\Services\ApplicationService;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AdminApplicationController extends Controller
{
    protected $applicationService;
    protected $pdfService;

    public function __construct(ApplicationService $applicationService, PDFService $pdfService)
    {
        $this->middleware('admin');
        $this->applicationService = $applicationService;
        $this->pdfService = $pdfService;
    }

    /**
     * List all applications
     */
    public function index(Request $request)
    {
        $query = Lamaran::with(['user.kecamatan', 'pekerjaan.perusahaan', 'pekerjaan.kecamatan']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            });
        }

        // Filter by job
        if ($request->filled('job')) {
            $query->where('id_pekerjaan', $request->job);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->filled('company')) {
            $query->whereHas('pekerjaan', function($q) use ($request) {
                $q->where('id_perusahaan', $request->company);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_terkirim', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_terkirim', '<=', $request->date_to);
        }

        $applications = $query->orderBy('tanggal_terkirim', 'desc')
                             ->paginate(15)
                             ->withQueryString();

        // Get company list and job list for filters
        $companyList = \App\Models\Perusahaan::orderBy('nama_perusahaan')->get();
        $jobList = Pekerjaan::with('perusahaan')->orderBy('nama_pekerjaan')->get();

        // Calculate statistics
        $totalApplications = Lamaran::count();
        $pendingCount = Lamaran::where('status', 'Pending')->count();
        $acceptedCount = Lamaran::where('status', 'Diterima')->count();
        $rejectedCount = Lamaran::where('status', 'Ditolak')->count();

        return view('admin.applications.index', compact(
            'applications',
            'companyList',
            'jobList',
            'totalApplications',
            'pendingCount',
            'acceptedCount',
            'rejectedCount'
        ));
    }

    /**
     * Show application detail
     */
    public function show($id)
    {
        $application = Lamaran::with([
                                   'user.kecamatan',
                                   'pekerjaan.perusahaan',
                                   'pekerjaan.kecamatan',
                                   'pekerjaan.kategori'
                               ])
                               ->findOrFail($id);

        return view('admin.applications.show', compact('application'));
    }

    /**
     * Download application detail as PDF
     */
    public function downloadPDF($id)
    {
        try {
            $application = Lamaran::with(['user.kecamatan', 'pekerjaan.perusahaan'])
                                  ->findOrFail($id);

            return $this->pdfService->generateApplicantDetailPDF($id, $application->pekerjaan->id_perusahaan);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export applications to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            // This requires creating an Export class
            // For now, return placeholder
            return back()->with('info', 'Fitur export Excel akan segera tersedia.');
            
            // Example implementation:
            // return Excel::download(new ApplicationsExport($request->all()), 'applications.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export applications to PDF
     */
    public function exportPDF(Request $request)
    {
        try {
            $query = Lamaran::with(['user.kecamatan', 'pekerjaan.perusahaan']);

            // Apply same filters as index
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $applications = $query->orderBy('tanggal_terkirim', 'desc')->get();

            if ($applications->isEmpty()) {
                return back()->with('error', 'Tidak ada data untuk diexport.');
            }

            $data = ['applications' => $applications];
            return $this->pdfService->generateStatistikPDF($data);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Pending,Ditolak',
        ]);

        try {
            $this->applicationService->updateStatus($id, $request->status);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated application status to: ' . $request->status);

            return back()->with('success', 'Status lamaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Delete application
     */
    public function destroy($id)
    {
        try {
            $application = Lamaran::findOrFail($id);
            $application->delete();

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted application ID: ' . $id);

            return redirect()->route('admin.applications.index')
                           ->with('success', 'Lamaran berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus lamaran: ' . $e->getMessage());
        }
    }
}
