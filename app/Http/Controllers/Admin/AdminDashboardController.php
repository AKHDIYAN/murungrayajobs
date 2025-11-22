<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Pekerjaan;
use App\Models\Lamaran;
use App\Models\Statistik;
use App\Models\Admin;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Admin dashboard
     */
    public function index()
    {
        // Overall statistics
        $totalUsers = User::count();
        $totalCompanies = Perusahaan::count();
        $verifiedCompanies = Perusahaan::where('is_verified', true)->count();
        $totalJobs = Pekerjaan::count();
        $activeJobs = Pekerjaan::aktif()->count();
        $pendingJobs = Pekerjaan::where('status', 'Pending')->count();
        $totalApplications = Lamaran::count();
        $totalStatistik = Statistik::count();

        // Employment statistics
        $bekerja = Statistik::bekerja()->count();
        $menganggur = Statistik::menganggur()->count();

        // Recent activities
        $recentUsers = User::orderBy('tanggal_registrasi', 'desc')->limit(5)->get();
        $recentCompanies = Perusahaan::orderBy('tanggal_registrasi', 'desc')->limit(5)->get();
        $recentJobs = Pekerjaan::with(['perusahaan'])->orderBy('tanggal_posting', 'desc')->limit(5)->get();

        // Chart data - Jobs by kecamatan
        $jobsByKecamatan = Pekerjaan::with('kecamatan')
                                    ->select('id_kecamatan', DB::raw('count(*) as total'))
                                    ->groupBy('id_kecamatan')
                                    ->get();

        // Chart data - Applications by status
        $applicationsByStatus = Lamaran::select('status', DB::raw('count(*) as total'))
                                       ->groupBy('status')
                                       ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCompanies',
            'verifiedCompanies',
            'totalJobs',
            'activeJobs',
            'pendingJobs',
            'totalApplications',
            'totalStatistik',
            'bekerja',
            'menganggur',
            'recentUsers',
            'recentCompanies',
            'recentJobs',
            'jobsByKecamatan',
            'applicationsByStatus'
        ));
    }

    /**
     * List all admins
     */
    public function adminList()
    {
        $admins = Admin::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show create admin form
     */
    public function createAdmin()
    {
        return view('admin.admins.create');
    }

    /**
     * Store new admin
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admin,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            Admin::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            // Log activity
            $currentAdmin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $currentAdmin->id_admin, 'create', 'Created new admin: ' . $request->username);

            return redirect()->route('admin.admins.index')
                           ->with('success', 'Admin baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan admin: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete admin
     */
    public function destroyAdmin($id)
    {
        try {
            $currentAdmin = Auth::guard('admin')->user();

            // Prevent self-deletion
            if ($currentAdmin->id_admin == $id) {
                return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            $admin = Admin::findOrFail($id);
            $username = $admin->username;
            $admin->delete();

            // Log activity
            ActivityLog::createLog('admin', $currentAdmin->id_admin, 'delete', 'Deleted admin: ' . $username);

            return back()->with('success', 'Admin berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus admin: ' . $e->getMessage());
        }
    }

    /**
     * Activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::orderBy('created_at', 'desc');

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.logs', compact('logs'));
    }

    /**
     * System settings
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        // Implement settings update logic
        // This could use a settings table or config files
        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
