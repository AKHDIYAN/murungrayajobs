<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Pendidikan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * List all users
     */
    public function index(Request $request)
    {
        $query = User::with(['kecamatan', 'pendidikan', 'lamaran']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        // Filter by pendidikan
        if ($request->filled('pendidikan')) {
            $query->where('id_pendidikan', $request->pendidikan);
        }

        $users = $query->orderBy('created_at', 'desc')
                      ->paginate(15)
                      ->withQueryString();

        $kecamatanList = Kecamatan::all();
        $pendidikanList = Pendidikan::all();

        // Statistics
        $activeApplicants = User::whereHas('lamaran')->count();
        $newThisMonth = User::whereMonth('created_at', date('m'))
                           ->whereYear('created_at', date('Y'))
                           ->count();
        $verifiedUsers = User::whereNotNull('nik')
                            ->whereNotNull('no_telepon')
                            ->count();

        return view('admin.users.index', compact(
            'users', 
            'kecamatanList', 
            'pendidikanList',
            'activeApplicants',
            'newThisMonth',
            'verifiedUsers'
        ));
    }

    /**
     * Show user detail
     */
    public function show($id)
    {
        $user = User::with(['kecamatan', 'lamaran.pekerjaan.perusahaan'])
                    ->findOrFail($id);

        // Statistics
        $totalApplications = $user->lamaran->count();
        $acceptedApplications = $user->lamaran->where('status', 'Diterima')->count();
        $pendingApplications = $user->lamaran->where('status', 'Pending')->count();
        $rejectedApplications = $user->lamaran->where('status', 'Ditolak')->count();

        return view('admin.users.show', compact(
            'user',
            'totalApplications',
            'acceptedApplications',
            'pendingApplications',
            'rejectedApplications'
        ));
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $kecamatanList = Kecamatan::all();
        $pendidikanList = Pendidikan::all();

        return view('admin.users.edit', compact('user', 'kecamatanList', 'pendidikanList'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $id . ',id_user',
            'no_telepon' => 'nullable|string|min:10|max:15',
            'nik' => 'nullable|string|size:16',
            'alamat' => 'nullable|string',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
            'id_pendidikan' => 'nullable|exists:pendidikan,id_pendidikan',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update($request->only([
                'nama', 
                'email', 
                'no_telepon', 
                'nik',
                'alamat', 
                'id_kecamatan',
                'id_pendidikan',
                'jenis_kelamin',
                'tanggal_lahir'
            ]));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated user: ' . $user->nama);

            return redirect()->route('admin.users.show', $id)
                           ->with('success', 'Data user berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data user: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $nama = $user->nama;
            $user->delete();

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted user: ' . $nama);

            return redirect()->route('admin.users.index')
                           ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Suspend user
     */
    public function suspend($id)
    {
        // Note: You need to add 'is_active' or 'is_suspended' column to user table
        // For now, this is a placeholder
        return back()->with('info', 'Fitur suspend user akan segera tersedia.');
    }

    /**
     * Activate user
     */
    public function activate($id)
    {
        // Note: You need to add 'is_active' or 'is_suspended' column to user table
        // For now, this is a placeholder
        return back()->with('info', 'Fitur aktivasi user akan segera tersedia.');
    }
}
