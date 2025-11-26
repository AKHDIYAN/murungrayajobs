<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pekerjaan;
use Illuminate\Support\Facades\Validator;

class PekerjaanApiController extends Controller
{
    /**
     * Display a listing of jobs.
     * GET /api/pekerjaan
     */
    public function index(Request $request)
    {
        $query = Pekerjaan::with(['perusahaan', 'kategori', 'kecamatan']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kecamatan
        if ($request->has('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        // Filter by kategori
        if ($request->has('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Search by title or company
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('posisi', 'like', "%{$search}%")
                  ->orWhereHas('perusahaan', function($q2) use ($search) {
                      $q2->where('nama_perusahaan', 'like', "%{$search}%");
                  });
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $pekerjaan = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data lowongan berhasil diambil',
            'data' => $pekerjaan
        ]);
    }

    /**
     * Display the specified job.
     * GET /api/pekerjaan/{id}
     */
    public function show($id)
    {
        $pekerjaan = Pekerjaan::with(['perusahaan', 'kategori', 'kecamatan', 'lamaran.user'])
            ->find($id);

        if (!$pekerjaan) {
            return response()->json([
                'success' => false,
                'message' => 'Lowongan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail lowongan berhasil diambil',
            'data' => $pekerjaan
        ]);
    }

    /**
     * Get job statistics.
     * GET /api/pekerjaan/statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Pekerjaan::count(),
            'aktif' => Pekerjaan::where('status', 'Aktif')->count(),
            'tidak_aktif' => Pekerjaan::where('status', 'Tidak Aktif')->count(),
            'per_kategori' => Pekerjaan::with('kategori')
                ->selectRaw('kategori_id, count(*) as total')
                ->groupBy('kategori_id')
                ->get(),
            'per_kecamatan' => Pekerjaan::with('kecamatan')
                ->selectRaw('kecamatan_id, count(*) as total')
                ->groupBy('kecamatan_id')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistik lowongan berhasil diambil',
            'data' => $stats
        ]);
    }
}
