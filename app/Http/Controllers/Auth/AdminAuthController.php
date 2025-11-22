<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            // Update last login
            /** @var \App\Models\Admin $admin */
            $admin->updateLastLogin();

            // Log activity
            ActivityLog::createLog('admin', $admin->id_admin, 'login', 'Admin logged in');

            return redirect()->intended(route('admin.dashboard'))
                           ->with('success', 'Login berhasil! Selamat datang, Admin.');
        }

        return back()->with('error', 'Username atau password salah.')
                   ->withInput($request->only('username'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Log activity
        if ($admin) {
            ActivityLog::createLog('admin', $admin->id_admin, 'logout', 'Admin logged out');
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
                       ->with('success', 'Anda telah logout.');
    }
}
