<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lamaran;

class LamaranApiController extends Controller
{
    /**
     * Display a listing of applications.
     * GET /api/lamaran
     */
    public function index(Request $request)
    {
        $query = Lamaran::with(['user', 'pekerjaan.perusahaan']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user_id
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by pekerjaan_id
        if ($request->has('pekerjaan_id')) {
            $query->where('pekerjaan_id', $request->pekerjaan_id);
        }

        // Filter by date range
        if ($request->has('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        if ($request->has('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $lamaran = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data lamaran berhasil diambil',
            'data' => $lamaran
        ]);
    }

    /**
     * Display the specified application.
     * GET /api/lamaran/{id}
     */
    public function show($id)
    {
        $lamaran = Lamaran::with(['user', 'pekerjaan.perusahaan', 'pekerjaan.kecamatan'])
            ->find($id);

        if (!$lamaran) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail lamaran berhasil diambil',
            'data' => $lamaran
        ]);
    }

    /**
     * Get application statistics.
     * GET /api/lamaran/statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Lamaran::count(),
            'pending' => Lamaran::where('status', 'Pending')->count(),
            'diterima' => Lamaran::where('status', 'Diterima')->count(),
            'ditolak' => Lamaran::where('status', 'Ditolak')->count(),
            'today' => Lamaran::whereDate('created_at', today())->count(),
            'this_month' => Lamaran::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistik lamaran berhasil diambil',
            'data' => $stats
        ]);
    }
}
