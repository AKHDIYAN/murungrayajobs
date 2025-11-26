<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;

class PelatihanApiController extends Controller
{
    /**
     * Display a listing of training programs.
     * GET /api/pelatihan
     */
    public function index(Request $request)
    {
        $query = Pelatihan::with(['sektor']);

        // Filter by sektor
        if ($request->has('sektor_id')) {
            $query->where('sektor_id', $request->sektor_id);
        }

        // Filter by date range
        if ($request->has('tanggal_mulai')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
        }
        if ($request->has('tanggal_selesai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('judul_pelatihan', 'like', "%{$request->search}%");
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $pelatihan = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan berhasil diambil',
            'data' => $pelatihan
        ]);
    }

    /**
     * Display the specified training.
     * GET /api/pelatihan/{id}
     */
    public function show($id)
    {
        $pelatihan = Pelatihan::with(['sektor', 'peserta.user'])
            ->find($id);

        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Pelatihan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pelatihan berhasil diambil',
            'data' => $pelatihan
        ]);
    }

    /**
     * Get training participants.
     * GET /api/pelatihan/{id}/peserta
     */
    public function peserta($id)
    {
        $pelatihan = Pelatihan::find($id);

        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Pelatihan tidak ditemukan'
            ], 404);
        }

        $peserta = PelatihanPeserta::with('user')
            ->where('pelatihan_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data peserta pelatihan berhasil diambil',
            'data' => [
                'pelatihan' => $pelatihan,
                'peserta' => $peserta,
                'total_peserta' => $peserta->count()
            ]
        ]);
    }

    /**
     * Get training statistics.
     * GET /api/pelatihan/statistics
     */
    public function statistics()
    {
        $stats = [
            'total_pelatihan' => Pelatihan::count(),
            'total_peserta' => PelatihanPeserta::count(),
            'per_sektor' => Pelatihan::with('sektor')
                ->selectRaw('sektor_id, count(*) as total')
                ->groupBy('sektor_id')
                ->get(),
            'upcoming' => Pelatihan::where('tanggal_mulai', '>', now())->count(),
            'ongoing' => Pelatihan::where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->count(),
            'completed' => Pelatihan::where('tanggal_selesai', '<', now())->count()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistik pelatihan berhasil diambil',
            'data' => $stats
        ]);
    }
}
