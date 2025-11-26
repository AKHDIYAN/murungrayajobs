<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perusahaan;

class PerusahaanApiController extends Controller
{
    /**
     * Display a listing of companies.
     * GET /api/perusahaan
     */
    public function index(Request $request)
    {
        $query = Perusahaan::with(['sektor', 'kecamatan']);

        // Filter by sektor
        if ($request->has('sektor_id')) {
            $query->where('sektor_id', $request->sektor_id);
        }

        // Filter by kecamatan
        if ($request->has('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('nama_perusahaan', 'like', "%{$request->search}%");
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $perusahaan = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data perusahaan berhasil diambil',
            'data' => $perusahaan
        ]);
    }

    /**
     * Display the specified company.
     * GET /api/perusahaan/{id}
     */
    public function show($id)
    {
        $perusahaan = Perusahaan::with(['sektor', 'kecamatan', 'pekerjaan' => function($query) {
            $query->where('status', 'Aktif')->latest()->take(10);
        }])->find($id);

        if (!$perusahaan) {
            return response()->json([
                'success' => false,
                'message' => 'Perusahaan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail perusahaan berhasil diambil',
            'data' => $perusahaan
        ]);
    }

    /**
     * Get company statistics.
     * GET /api/perusahaan/statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Perusahaan::count(),
            'per_sektor' => Perusahaan::with('sektor')
                ->selectRaw('sektor_id, count(*) as total')
                ->groupBy('sektor_id')
                ->get(),
            'per_kecamatan' => Perusahaan::with('kecamatan')
                ->selectRaw('kecamatan_id, count(*) as total')
                ->groupBy('kecamatan_id')
                ->get(),
            'with_active_jobs' => Perusahaan::whereHas('pekerjaan', function($q) {
                $q->where('status', 'Aktif');
            })->count()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistik perusahaan berhasil diambil',
            'data' => $stats
        ]);
    }
}
