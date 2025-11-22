<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log activity after request
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            $this->logActivity($request);
        }

        return $response;
    }

    protected function logActivity(Request $request)
    {
        $userType = null;
        $userId = null;
        $action = $this->getActionFromMethod($request->method());

        if (Auth::guard('web')->check()) {
            $userType = 'user';
            $userId = Auth::guard('web')->id();
        } elseif (Auth::guard('company')->check()) {
            $userType = 'company';
            $userId = Auth::guard('company')->id();
        } elseif (Auth::guard('admin')->check()) {
            $userType = 'admin';
            $userId = Auth::guard('admin')->id();
        }

        if ($userType && $userId) {
            ActivityLog::createLog(
                $userType,
                $userId,
                $action,
                $request->path()
            );
        }
    }

    protected function getActionFromMethod($method)
    {
        $actions = [
            'POST' => 'create',
            'PUT' => 'update',
            'PATCH' => 'update',
            'DELETE' => 'delete',
        ];

        return $actions[$method] ?? 'unknown';
    }
}
