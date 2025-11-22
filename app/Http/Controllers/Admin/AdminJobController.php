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
        $query = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by company
        if ($request->filled('company')) {
            $query->where('id_perusahaan', $request->company);
        }

        // Filter by kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->aktif();
            } elseif ($request->status === 'berakhir') {
                $query->berakhir();
            } else {
                $query->where('status', $request->status);
            }
        }

        $jobs = $query->orderBy('tanggal_posting', 'desc')
                     ->paginate(15)
                     ->withQueryString();

        $kecamatanList = Kecamatan::all();
        $kategoriList = Sektor::all();

        return view('admin.jobs.index', compact('jobs', 'kecamatanList', 'kategoriList'));
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
        $job = Pekerjaan::with('perusahaan')->findOrFail($id);
        $kecamatanList = Kecamatan::all();
        $kategoriList = Sektor::all();

        return view('admin.jobs.edit', compact('job', 'kecamatanList', 'kategoriList'));
    }

    /**
     * Update job
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pekerjaan' => 'required|string|max:255',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kategori' => 'required|exists:sektor,id_sektor',
            'gaji_min' => 'required|numeric|min:0',
            'gaji_max' => 'required|numeric|min:0|gte:gaji_min',
            'deskripsi_pekerjaan' => 'required|string',
            'persyaratan_pekerjaan' => 'required|string',
            'jumlah_lowongan' => 'required|integer|min:1',
            'jenis_pekerjaan' => 'required|in:Full-Time,Part-Time,Kontrak',
            'tanggal_expired' => 'required|date|after_or_equal:today',
            'status' => 'required|in:Diterima,Pending,Ditolak',
        ]);

        try {
            $this->jobService->updateJob($id, $request->all());

            // Log activity
            $admin = Auth::guard('admin')->user();
            $job = Pekerjaan::find($id);
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
