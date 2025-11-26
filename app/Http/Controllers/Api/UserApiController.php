<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserApiController extends Controller
{
    /**
     * Display a listing of job seekers.
     * GET /api/users
     */
    public function index(Request $request)
    {
        $query = User::with(['kecamatan', 'pendidikan', 'usia']);

        // Filter by kecamatan
        if ($request->has('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        // Filter by pendidikan
        if ($request->has('pendidikan_id')) {
            $query->where('pendidikan_id', $request->pendidikan_id);
        }

        // Filter by usia
        if ($request->has('usia_id')) {
            $query->where('usia_id', $request->usia_id);
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $users = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data pencari kerja berhasil diambil',
            'data' => $users
        ]);
    }

    /**
     * Display the specified user.
     * GET /api/users/{id}
     */
    public function show($id)
    {
        $user = User::with(['kecamatan', 'pendidikan', 'usia', 'lamaran.pekerjaan.perusahaan'])
            ->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pencari kerja tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pencari kerja berhasil diambil',
            'data' => $user
        ]);
    }

    /**
     * Get user statistics.
     * GET /api/users/statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => User::count(),
            'per_kecamatan' => User::with('kecamatan')
                ->selectRaw('kecamatan_id, count(*) as total')
                ->groupBy('kecamatan_id')
                ->get(),
            'per_pendidikan' => User::with('pendidikan')
                ->selectRaw('pendidikan_id, count(*) as total')
                ->groupBy('pendidikan_id')
                ->get(),
            'per_usia' => User::with('usia')
                ->selectRaw('usia_id, count(*) as total')
                ->groupBy('usia_id')
                ->get(),
            'registered_today' => User::whereDate('created_at', today())->count(),
            'registered_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistik pencari kerja berhasil diambil',
            'data' => $stats
        ]);
    }
}
