<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.admin.forgot-password');
    }

    /**
     * Send reset password email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:admin,username',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.exists' => 'Username tidak terdaftar dalam sistem.',
        ]);

        $admin = Admin::where('username', $request->username)->first();
        
        // Admin tidak punya email, jadi kita tidak bisa kirim email
        // Untuk admin, kita akan gunakan alternatif: tampilkan link reset di halaman
        
        return back()->withErrors(['username' => 'Fitur reset password untuk Admin harus dilakukan secara manual. Hubungi Super Admin atau gunakan script reset password.']);
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.admin.reset-password', [
            'token' => $token,
            'username' => $request->username
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required|exists:admin,username',
            'password' => 'required|min:8|confirmed',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.exists' => 'Username tidak terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // Update password
        $admin = Admin::where('username', $request->username)->first();
        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}
