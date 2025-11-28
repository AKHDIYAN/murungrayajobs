<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Perusahaan;

class AuthController extends Controller
{
    /**
     * User login
     * POST /api/v1/auth/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:user,perusahaan'
        ]);

        $credentials = $request->only('email', 'password');
        
        if ($request->user_type === 'user') {
            // User/Job Seeker Login
            $user = User::where('email', $request->email)->first();
            
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'nama' => $user->nama,
                        'email' => $user->email,
                        'jenis_kelamin' => $user->jenis_kelamin,
                        'status_kerja' => $user->status_kerja,
                        'type' => 'user'
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);
            
        } else {
            // Company Login
            $perusahaan = Perusahaan::where('email', $request->email)->first();
            
            if (!$perusahaan || !Hash::check($request->password, $perusahaan->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $perusahaan->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $perusahaan->id,
                        'nama_perusahaan' => $perusahaan->nama_perusahaan,
                        'email' => $perusahaan->email,
                        'username' => $perusahaan->username,
                        'type' => 'perusahaan'
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);
        }
    }

    /**
     * User registration
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:8|confirmed',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kecamatan_id' => 'required|exists:kecamatan,id_kecamatan',
            'id_pendidikan' => 'required|exists:pendidikan,id_pendidikan',
            'id_usia' => 'required|exists:usia,id_usia',
            'no_telepon' => 'nullable|string|max:15'
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'kecamatan_id' => $request->kecamatan_id,
            'id_pendidikan' => $request->id_pendidikan,
            'id_usia' => $request->id_usia,
            'no_telepon' => $request->no_telepon,
            'status_kerja' => 'Pencari Kerja' // Default status
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'status_kerja' => $user->status_kerja,
                    'type' => 'user'
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    /**
     * Get authenticated user profile
     * GET /api/v1/auth/profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        if ($user instanceof User) {
            // Job seeker profile
            $user->load(['kecamatan', 'pendidikan', 'usia']);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'status_kerja' => $user->status_kerja,
                    'pekerjaan_saat_ini' => $user->pekerjaan_saat_ini,
                    'pengalaman_kerja' => $user->pengalaman_kerja,
                    'skills' => $user->skills ? explode(',', $user->skills) : [],
                    'jenis_sertifikasi' => $user->jenis_sertifikasi,
                    'sertifikat_verified' => $user->sertifikat_verified,
                    'no_telepon' => $user->no_telepon,
                    'kecamatan' => $user->kecamatan?->nama_kecamatan,
                    'pendidikan' => $user->pendidikan?->tingkatan_pendidikan,
                    'usia' => $user->usia?->kelompok_usia,
                    'type' => 'user'
                ]
            ]);
        } else {
            // Company profile  
            $user->load(['kecamatan']);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => [
                    'id' => $user->id,
                    'nama_perusahaan' => $user->nama_perusahaan,
                    'email' => $user->email,
                    'username' => $user->username,
                    'alamat' => $user->alamat,
                    'no_telepon' => $user->no_telepon,
                    'deskripsi' => $user->deskripsi,
                    'logo' => $user->logo,
                    'kecamatan' => $user->kecamatan?->nama_kecamatan,
                    'type' => 'perusahaan'
                ]
            ]);
        }
    }

    /**
     * Update user profile
     * PUT /api/v1/auth/profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        if ($user instanceof User) {
            // Job seeker profile update
            $request->validate([
                'nama' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:user,email,' . $user->id,
                'jenis_kelamin' => 'sometimes|in:Laki-laki,Perempuan',
                'status_kerja' => 'sometimes|in:Pencari Kerja,Bekerja,Menganggur',
                'pekerjaan_saat_ini' => 'sometimes|nullable|string|max:255',
                'pengalaman_kerja' => 'sometimes|nullable|integer|min:0',
                'skills' => 'sometimes|nullable|string|max:500',
                'jenis_sertifikasi' => 'sometimes|nullable|string|max:255',
                'no_telepon' => 'sometimes|nullable|string|max:15',
                'kecamatan_id' => 'sometimes|exists:kecamatan,id_kecamatan',
                'id_pendidikan' => 'sometimes|exists:pendidikan,id_pendidikan',
                'id_usia' => 'sometimes|exists:usia,id_usia'
            ]);
            
            $user->update($request->only([
                'nama', 'email', 'jenis_kelamin', 'status_kerja', 'pekerjaan_saat_ini',
                'pengalaman_kerja', 'skills', 'jenis_sertifikasi', 'no_telepon',
                'kecamatan_id', 'id_pendidikan', 'id_usia'
            ]));
            
        } else {
            // Company profile update
            $request->validate([
                'nama_perusahaan' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:perusahaan,email,' . $user->id,
                'alamat' => 'sometimes|nullable|string|max:500',
                'no_telepon' => 'sometimes|nullable|string|max:15',
                'deskripsi' => 'sometimes|nullable|string|max:1000',
                'kecamatan_id' => 'sometimes|exists:kecamatan,id_kecamatan'
            ]);
            
            $user->update($request->only([
                'nama_perusahaan', 'email', 'alamat', 'no_telepon', 
                'deskripsi', 'kecamatan_id'
            ]));
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user->fresh()
        ]);
    }

    /**
     * Logout (revoke token)
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Revoke all tokens
     * POST /api/v1/auth/logout-all
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices successfully'
        ]);
    }

    /**
     * Change password
     * POST /api/v1/auth/change-password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}