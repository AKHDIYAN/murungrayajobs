<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kecamatan;
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
        $query = User::with('kecamatan');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            });
        }

        // Filter by kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('jenis_kelamin', $request->gender);
        }

        $users = $query->orderBy('tanggal_registrasi', 'desc')
                      ->paginate(15)
                      ->withQueryString();

        $kecamatanList = Kecamatan::all();

        return view('admin.users.index', compact('users', 'kecamatanList'));
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

        return view('admin.users.edit', compact('user', 'kecamatanList'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $id . ',id_user',
            'no_telepon' => 'required|string|min:10|max:15',
            'alamat' => 'required|string',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update($request->only(['nama', 'email', 'no_telepon', 'alamat', 'id_kecamatan']));

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
