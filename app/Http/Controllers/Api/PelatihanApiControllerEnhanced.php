<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;
use App\Models\User;
use App\Models\Sektor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PelatihanApiControllerEnhanced extends Controller
{
    /**
     * Get all training programs
     * GET /api/v1/pelatihan
     */
    public function index(Request $request)
    {
        try {
            $query = Pelatihan::with(['sektor']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by sector
            if ($request->has('sektor_id')) {
                $query->where('id_sektor', $request->sektor_id);
            }

            // Filter by type
            if ($request->has('jenis_pelatihan')) {
                $query->where('jenis_pelatihan', $request->jenis_pelatihan);
            }

            // Search by name
            if ($request->has('search')) {
                $query->where('nama_pelatihan', 'LIKE', '%' . $request->search . '%');
            }

            $perPage = $request->get('per_page', 10);
            $pelatihan = $query->orderBy('tanggal_mulai', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Training programs retrieved successfully',
                'data' => $pelatihan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get specific training program
     * GET /api/v1/pelatihan/{id}
     */
    public function show($id)
    {
        $pelatihan = Pelatihan::with(['sektor', 'peserta.user'])->find($id);

        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Training program not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Training program retrieved successfully',
            'data' => $pelatihan
        ]);
    }

    /**
     * Register for training program
     * POST /api/v1/pelatihan/{id}/register
     */
    public function register(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $pelatihan = Pelatihan::find($id);
        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Training program not found'
            ], 404);
        }

        // Check if program is open for registration
        if ($pelatihan->status !== 'Dibuka') {
            return response()->json([
                'success' => false,
                'message' => 'Training program is not open for registration'
            ], 400);
        }

        // Check quota
        $currentParticipants = $pelatihan->peserta()->count();
        if ($currentParticipants >= $pelatihan->kuota_peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Training program is full'
            ], 400);
        }

        // Check if user already registered
        $existingRegistration = PelatihanPeserta::where('id_pelatihan', $id)
            ->where('id_user', $request->user_id)
            ->first();

        if ($existingRegistration) {
            return response()->json([
                'success' => false,
                'message' => 'User already registered for this training'
            ], 400);
        }

        // Create registration
        $peserta = PelatihanPeserta::create([
            'id_pelatihan' => $id,
            'id_user' => $request->user_id,
            'tanggal_daftar' => now(),
            'status_kehadiran' => 'Terdaftar'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered for training program',
            'data' => $peserta
        ], 201);
    }

    /**
     * Get training statistics
     * GET /api/v1/pelatihan/statistics
     */
    public function statistics()
    {
        try {
            $totalPrograms = Pelatihan::count();
            $activePrograms = Pelatihan::where('status', 'Dibuka')->count();
            $totalParticipants = PelatihanPeserta::count();

            return response()->json([
                'success' => true,
                'message' => 'Training statistics retrieved successfully',
                'data' => [
                    'overview' => [
                        'total_programs' => $totalPrograms,
                        'active_programs' => $activePrograms,
                        'total_participants' => $totalParticipants
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get user's training history
     * GET /api/v1/pelatihan/user/{user_id}
     */
    public function userTrainings($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $trainings = PelatihanPeserta::with(['pelatihan.sektor'])
            ->where('id_user', $userId)
            ->orderBy('tanggal_daftar', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'User training history retrieved successfully',
            'data' => $trainings
        ]);
    }

    /**
     * Get available training programs for a user
     * GET /api/v1/pelatihan/available/{user_id}
     */
    public function availableForUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Get training programs user hasn't registered for
        $registeredProgramIds = PelatihanPeserta::where('id_user', $userId)
            ->pluck('id_pelatihan')
            ->toArray();

        $availableTrainings = Pelatihan::with(['sektor'])
            ->where('status', 'Dibuka')
            ->where('tanggal_mulai', '>', now())
            ->whereNotIn('id_pelatihan', $registeredProgramIds)
            ->orderBy('tanggal_mulai')
            ->get()
            ->map(function ($pelatihan) {
                $currentParticipants = $pelatihan->peserta()->count();
                $pelatihan->available_slots = $pelatihan->kuota_peserta - $currentParticipants;
                $pelatihan->is_full = $currentParticipants >= $pelatihan->kuota_peserta;
                return $pelatihan;
            });

        return response()->json([
            'success' => true,
            'message' => 'Available training programs retrieved successfully',
            'data' => $availableTrainings
        ]);
    }

    /**
     * Get training program participants
     * GET /api/v1/pelatihan/{id}/participants
     */
    public function participants($id)
    {
        $pelatihan = Pelatihan::find($id);
        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Training program not found'
            ], 404);
        }

        $participants = PelatihanPeserta::with(['user.kecamatan', 'user.pendidikan'])
            ->where('id_pelatihan', $id)
            ->orderBy('tanggal_daftar')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Training participants retrieved successfully',
            'data' => [
                'pelatihan' => $pelatihan,
                'participants' => $participants,
                'total_participants' => $participants->count(),
                'available_slots' => $pelatihan->kuota_peserta - $participants->count()
            ]
        ]);
    }
}