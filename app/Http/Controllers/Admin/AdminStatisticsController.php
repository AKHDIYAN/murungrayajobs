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
     * List all statistics data
     */
    public function index(Request $request)
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

        return view('admin.statistics.index', compact(
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

            return redirect()->route('admin.statistics.index')
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

            return redirect()->route('admin.statistics.index')
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
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            // This requires creating an Import class
            // For now, return placeholder
            return back()->with('info', 'Fitur import Excel akan segera tersedia.');
            
            // Example implementation:
            // Excel::import(new StatistikImport, $request->file('file'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
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
