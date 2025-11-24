<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Pekerjaan;
use App\Models\Lamaran;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build query with date filter
        $userQuery = User::query();
        $companyQuery = Perusahaan::query();
        $jobQuery = Pekerjaan::query();
        $applicationQuery = Lamaran::query();

        if ($startDate) {
            $userQuery->where('created_at', '>=', $startDate);
            $companyQuery->where('tanggal_registrasi', '>=', $startDate);
            $jobQuery->where('created_at', '>=', $startDate);
            $applicationQuery->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $endDateTime = $endDate . ' 23:59:59';
            $userQuery->where('created_at', '<=', $endDateTime);
            $companyQuery->where('tanggal_registrasi', '<=', $endDateTime);
            $jobQuery->where('created_at', '<=', $endDateTime);
            $applicationQuery->where('created_at', '<=', $endDateTime);
        }

        // Get total statistics
        $totalUser = $userQuery->count();
        $totalCompany = $companyQuery->count();
        $totalLowongan = $jobQuery->where('status', 'aktif')->count();
        $totalLamaran = $applicationQuery->count();

        // Get top 10 companies with most jobs
        $topCompanies = Perusahaan::withCount('pekerjaan')
            ->orderBy('pekerjaan_count', 'desc')
            ->take(10)
            ->get();

        $companyNames = $topCompanies->pluck('nama_perusahaan')->toArray();
        $jobCounts = $topCompanies->pluck('pekerjaan_count')->toArray();

        // Get user registration per month (last 6 months)
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        
        $userRegistrations = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Fill in missing months with 0
        $months = [];
        $userCounts = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');
            
            $months[] = $monthLabel;
            
            $registration = $userRegistrations->firstWhere('month', $monthKey);
            $userCounts[] = $registration ? $registration->count : 0;
        }

        // Prepare chart data
        $chartData = [
            'companyNames' => $companyNames,
            'jobCounts' => $jobCounts,
            'months' => $months,
            'userCounts' => $userCounts,
        ];

        // Get recent activities (last 10)
        $recentActivities = ActivityLog::where('user_type', 'admin')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'totalUser' => $totalUser,
            'totalCompany' => $totalCompany,
            'totalLowongan' => $totalLowongan,
            'totalLamaran' => $totalLamaran,
            'chartData' => $chartData,
            'recentActivities' => $recentActivities,
        ]);
    }
}
