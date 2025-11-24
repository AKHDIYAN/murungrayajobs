<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pekerjaan;
use App\Models\Kecamatan;
use App\Models\Sektor;
use App\Models\ActivityLog;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminJobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->middleware('admin');
        $this->jobService = $jobService;
    }

    /**
     * List all jobs
     */
    public function index(Request $request)
    {
        $query = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori', 'lamaran']);

        // Search
        if ($request->filled('search')) {
            $query->where('nama_pekerjaan', 'like', '%' . $request->search . '%');
        }

        // Filter by company
        if ($request->filled('company')) {
            $query->where('id_perusahaan', $request->company);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->orderBy('tanggal_posting', 'desc')
                     ->paginate(15)
                     ->withQueryString();

        // Get company list and sektor list for filters
        $companyList = \App\Models\Perusahaan::orderBy('nama_perusahaan')->get();
        $sektorList = Sektor::orderBy('nama_kategori')->get();

        // Calculate statistics
        $pendingCount = Pekerjaan::where('status', 'Pending')->count();
        $acceptedCount = Pekerjaan::where('status', 'Diterima')->count();
        $totalApplicants = \App\Models\Lamaran::count();

        return view('admin.jobs.index', compact(
            'jobs',
            'companyList',
            'sektorList',
            'pendingCount',
            'acceptedCount',
            'totalApplicants'
        ));
    }

    /**
     * Show job detail
     */
    public function show($id)
    {
        $job = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori', 'lamaran.user'])
                        ->findOrFail($id);

        // Statistics
        $totalApplicants = $job->lamaran->count();
        $pendingApplicants = $job->lamaran->where('status', 'Pending')->count();
        $acceptedApplicants = $job->lamaran->where('status', 'Diterima')->count();
        $rejectedApplicants = $job->lamaran->where('status', 'Ditolak')->count();

        return view('admin.jobs.show', compact(
            'job',
            'totalApplicants',
            'pendingApplicants',
            'acceptedApplicants',
            'rejectedApplicants'
        ));
    }

    /**
     * Show edit job form
     */
    public function edit($id)
    {
        $job = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])->findOrFail($id);

        return view('admin.jobs.edit', compact('job'));
    }

    /**
     * Update job
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_expired' => 'required|date',
            'status' => 'required|in:Diterima,Pending,Ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        try {
            $job = Pekerjaan::findOrFail($id);
            
            $job->update([
                'tanggal_expired' => $request->tanggal_expired,
                'status' => $request->status,
                'catatan_admin' => $request->catatan_admin,
            ]);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated job: ' . $job->nama_pekerjaan);

            return redirect()->route('admin.jobs.show', $id)
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
            $job = Pekerjaan::findOrFail($id);
            $nama = $job->nama_pekerjaan;
            $this->jobService->deleteJob($id);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted job: ' . $nama);

            return redirect()->route('admin.jobs.index')
                           ->with('success', 'Lowongan pekerjaan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus lowongan: ' . $e->getMessage());
        }
    }

    /**
     * Approve job
     */
    public function approve($id)
    {
        try {
            $job = Pekerjaan::findOrFail($id);
            $job->update(['status' => 'Diterima']);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Approved job: ' . $job->nama_pekerjaan);

            return back()->with('success', 'Lowongan pekerjaan berhasil disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui lowongan: ' . $e->getMessage());
        }
    }

    /**
     * Reject job
     */
    public function reject($id)
    {
        try {
            $job = Pekerjaan::findOrFail($id);
            $job->update(['status' => 'Ditolak']);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Rejected job: ' . $job->nama_pekerjaan);

            return back()->with('success', 'Lowongan pekerjaan berhasil ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak lowongan: ' . $e->getMessage());
        }
    }
}
