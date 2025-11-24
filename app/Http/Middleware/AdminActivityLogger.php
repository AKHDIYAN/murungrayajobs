<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminActivityLogger
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated admin
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            
            // Determine action based on route and method
            $action = $this->determineAction($request);
            
            if ($action) {
                ActivityLog::createLog(
                    'admin',
                    $admin->id_admin ?? $admin->id,
                    $action['type'],
                    $action['description']
                );
            }
        }

        return $response;
    }

    /**
     * Determine the action type and description
     */
    private function determineAction(Request $request): ?array
    {
        $method = $request->method();
        $path = $request->path();
        $routeName = $request->route()?->getName();

        // Dashboard access
        if (str_contains($path, 'admin/dashboard') && $method === 'GET') {
            return [
                'type' => 'view_dashboard',
                'description' => 'Mengakses dashboard admin'
            ];
        }

        // User management
        if (str_contains($path, 'admin/users')) {
            return match($method) {
                'GET' => ['type' => 'view_users', 'description' => 'Melihat daftar user'],
                'POST' => ['type' => 'create_user', 'description' => 'Menambah user baru'],
                'PUT', 'PATCH' => ['type' => 'update_user', 'description' => 'Mengupdate data user ID: ' . $request->route('id')],
                'DELETE' => ['type' => 'delete_user', 'description' => 'Menghapus user ID: ' . $request->route('id')],
                default => null
            };
        }

        // Company management
        if (str_contains($path, 'admin/companies') || str_contains($path, 'admin/perusahaan')) {
            if (str_contains($path, 'verify') && $method === 'POST') {
                return ['type' => 'verify_company', 'description' => 'Memverifikasi perusahaan ID: ' . $request->route('id')];
            }
            if (str_contains($path, 'suspend') && $method === 'POST') {
                return ['type' => 'suspend_company', 'description' => 'Menangguhkan perusahaan ID: ' . $request->route('id')];
            }
            return match($method) {
                'GET' => ['type' => 'view_companies', 'description' => 'Melihat daftar perusahaan'],
                'POST' => ['type' => 'create_company', 'description' => 'Menambah perusahaan baru'],
                'PUT', 'PATCH' => ['type' => 'update_company', 'description' => 'Mengupdate data perusahaan ID: ' . $request->route('id')],
                'DELETE' => ['type' => 'delete_company', 'description' => 'Menghapus perusahaan ID: ' . $request->route('id')],
                default => null
            };
        }

        // Job management
        if (str_contains($path, 'admin/jobs') || str_contains($path, 'admin/lowongan')) {
            if (str_contains($path, 'approve') && $method === 'POST') {
                return ['type' => 'approve_job', 'description' => 'Menyetujui lowongan ID: ' . $request->route('id')];
            }
            if (str_contains($path, 'reject') && $method === 'POST') {
                return ['type' => 'reject_job', 'description' => 'Menolak lowongan ID: ' . $request->route('id')];
            }
            return match($method) {
                'GET' => ['type' => 'view_jobs', 'description' => 'Melihat daftar lowongan'],
                'DELETE' => ['type' => 'delete_job', 'description' => 'Menghapus lowongan ID: ' . $request->route('id')],
                default => null
            };
        }

        // Application management
        if (str_contains($path, 'admin/applications') || str_contains($path, 'admin/lamaran')) {
            return match($method) {
                'GET' => ['type' => 'view_applications', 'description' => 'Melihat daftar lamaran'],
                default => null
            };
        }

        // Statistics
        if (str_contains($path, 'admin/statistics') || str_contains($path, 'admin/statistik')) {
            return ['type' => 'view_statistics', 'description' => 'Melihat halaman statistik'];
        }

        // Master data
        if (str_contains($path, 'admin/master-data')) {
            return match($method) {
                'GET' => ['type' => 'view_master_data', 'description' => 'Melihat master data'],
                'POST' => ['type' => 'create_master_data', 'description' => 'Menambah master data'],
                'PUT', 'PATCH' => ['type' => 'update_master_data', 'description' => 'Mengupdate master data'],
                'DELETE' => ['type' => 'delete_master_data', 'description' => 'Menghapus master data'],
                default => null
            };
        }

        // Logs
        if (str_contains($path, 'admin/logs')) {
            return ['type' => 'view_logs', 'description' => 'Melihat activity logs'];
        }

        return null;
    }
}
