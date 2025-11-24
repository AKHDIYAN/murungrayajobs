<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatistikRequest;
use App\Models\Statistik;
use App\Models\Kecamatan;
use App\Models\Pendidikan;
use App\Models\Usia;
use App\Models\Sektor;
use App\Models\ActivityLog;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStatisticsController extends Controller
{
    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->middleware('admin');
        $this->pdfService = $pdfService;
    }

    /**
     * Dashboard with charts
     */
    public function dashboard(Request $request)
    {
        // Summary statistics
        $totalUsers = \App\Models\User::count();
        $totalCompanies = \App\Models\Perusahaan::count();
        $totalJobs = \App\Models\Pekerjaan::count();
        $totalApplications = \App\Models\Lamaran::count();

        // Applications by month (last 6 months)
        $applicationsByMonth = \App\Models\Lamaran::select(
                DB::raw('DATE_FORMAT(tanggal_terkirim, "%b %Y") as month'),
                DB::raw('count(*) as total')
            )
            ->where('tanggal_terkirim', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(tanggal_terkirim)'))
            ->get();

        // Jobs by category
        $jobsByCategory = \App\Models\Pekerjaan::select(
                'sektor.nama_kategori as kategori',
                DB::raw('count(*) as total')
            )
            ->join('sektor', 'pekerjaan.id_kategori', '=', 'sektor.id_sektor')
            ->groupBy('sektor.id_sektor', 'sektor.nama_kategori')
            ->orderBy('total', 'desc')
            ->limit(6)
            ->get();

        // Users by district
        $usersByDistrict = \App\Models\User::select(
                'kecamatan.nama_kecamatan as kecamatan',
                DB::raw('count(*) as total')
            )
            ->join('kecamatan', 'user.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->groupBy('kecamatan.id_kecamatan', 'kecamatan.nama_kecamatan')
            ->orderBy('total', 'desc')
            ->limit(7)
            ->get();

        // Application status
        $applicationsPending = \App\Models\Lamaran::where('status', 'Pending')->count();
        $applicationsAccepted = \App\Models\Lamaran::where('status', 'Diterima')->count();
        $applicationsRejected = \App\Models\Lamaran::where('status', 'Ditolak')->count();

        // Recent activities
        $recentActivities = ActivityLog::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.statistics.index', compact(
            'totalUsers',
            'totalCompanies',
            'totalJobs',
            'totalApplications',
            'applicationsByMonth',
            'jobsByCategory',
            'usersByDistrict',
            'applicationsPending',
            'applicationsAccepted',
            'applicationsRejected',
            'recentActivities'
        ));
    }

    /**
     * List all statistics data with import feature
     */
    public function dataIndex(Request $request)
    {
        $query = Statistik::with(['kecamatan', 'pendidikan', 'usia', 'sektor']);

        // Search
        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', "%{$request->search}%");
        }

        // Filter by kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('jenis_kelamin', $request->gender);
        }

        $statistik = $query->orderBy('created_at', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        // Summary statistics
        $totalData = Statistik::count();
        $bekerja = Statistik::bekerja()->count();
        $menganggur = Statistik::menganggur()->count();

        $kecamatanList = Kecamatan::all();

        return view('admin.statistics.data', compact(
            'statistik',
            'totalData',
            'bekerja',
            'menganggur',
            'kecamatanList'
        ));
    }

    /**
     * Show create statistics form
     */
    public function create()
    {
        $kecamatanList = Kecamatan::all();
        $pendidikanList = Pendidikan::all();
        $usiaList = Usia::all();
        $sektorList = Sektor::all();

        return view('admin.statistics.create', compact(
            'kecamatanList',
            'pendidikanList',
            'usiaList',
            'sektorList'
        ));
    }

    /**
     * Store new statistics data
     */
    public function store(StoreStatistikRequest $request)
    {
        try {
            Statistik::create($request->validated());

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'create', 'Created statistics data: ' . $request->nama);

            return redirect()->route('admin.statistics.data.index')
                           ->with('success', 'Data statistik berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan data statistik: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Show edit statistics form
     */
    public function edit($id)
    {
        $data = Statistik::findOrFail($id);
        $kecamatanList = Kecamatan::all();
        $pendidikanList = Pendidikan::all();
        $usiaList = Usia::all();
        $sektorList = Sektor::all();

        return view('admin.statistics.edit', compact(
            'data',
            'kecamatanList',
            'pendidikanList',
            'usiaList',
            'sektorList'
        ));
    }

    /**
     * Update statistics data
     */
    public function update(StoreStatistikRequest $request, $id)
    {
        try {
            $data = Statistik::findOrFail($id);
            $data->update($request->validated());

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated statistics data: ' . $data->nama);

            return redirect()->route('admin.statistics.data.index')
                           ->with('success', 'Data statistik berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data statistik: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete statistics data
     */
    public function destroy($id)
    {
        try {
            $data = Statistik::findOrFail($id);
            $nama = $data->nama;
            $data->delete();

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted statistics data: ' . $nama);

            return back()->with('success', 'Data statistik berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data statistik: ' . $e->getMessage());
        }
    }

    /**
     * Import statistics from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'File CSV harus dipilih.',
            'file.mimes' => 'File harus berformat CSV (.csv).',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $import = new \App\Imports\StatistikImport();
            $import->import($request->file('file')->getPathname());

            // Log activity
            $admin = Auth::guard('admin')->user();
            $message = sprintf(
                'Imported statistics data: %d berhasil, %d diabaikan',
                $import->getImported(),
                $import->getSkipped()
            );
            ActivityLog::createLog('admin', $admin->id_admin, 'import', $message);

            // Prepare result message
            $successMessage = "Import selesai! {$import->getImported()} data berhasil diimport.";
            
            if ($import->getSkipped() > 0) {
                $errorList = implode('<br>', array_slice($import->getErrors(), 0, 10));
                if (count($import->getErrors()) > 10) {
                    $errorList .= '<br>Dan ' . (count($import->getErrors()) - 10) . ' error lainnya...';
                }
                
                return redirect()->route('admin.statistics.data.index')
                               ->with('warning', $successMessage . "<br><br>Namun, {$import->getSkipped()} data diabaikan karena:<br>{$errorList}");
            }

            return redirect()->route('admin.statistics.data.index')
                           ->with('success', $successMessage);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for import
     */
    public function downloadTemplate()
    {
        try {
            // Redirect to CSV template for now
            return response()->download(
                public_path('templates/template-import-statistik.csv'),
                'template-import-statistik.csv'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal download template: ' . $e->getMessage());
        }
    }

    /**
     * Export statistics to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            // This requires creating an Export class
            return back()->with('info', 'Fitur export Excel akan segera tersedia.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export statistics to PDF
     */
    public function exportPDF(Request $request)
    {
        try {
            $data = [
                'total' => Statistik::count(),
                'bekerja' => Statistik::bekerja()->count(),
                'menganggur' => Statistik::menganggur()->count(),
                'by_kecamatan' => Statistik::with('kecamatan')
                                          ->select('id_kecamatan', DB::raw('count(*) as total'))
                                          ->groupBy('id_kecamatan')
                                          ->get(),
                'by_gender' => Statistik::select('jenis_kelamin', DB::raw('count(*) as total'))
                                       ->groupBy('jenis_kelamin')
                                       ->get(),
            ];

            return $this->pdfService->generateStatistikPDF($data);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }
}
