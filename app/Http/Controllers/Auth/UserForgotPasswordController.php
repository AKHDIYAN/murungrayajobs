<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.user.forgot-password');
    }

    /**
     * Send reset password email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate token
        $token = Str::random(64);

        // Delete old tokens for this email
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('guard', 'web')
            ->delete();

        // Create new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'guard' => 'web',
            'created_at' => now(),
        ]);

        // Send email
        $resetUrl = route('user.password.reset', ['token' => $token, 'email' => $request->email]);
        
        try {
            Mail::to($request->email)->send(new ResetPasswordMail($resetUrl, $user->nama, 'user'));
            
            return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.']);
        }
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.user.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:user,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // Check if token exists and not expired (60 minutes)
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('guard', 'web')
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Check token expiration (60 minutes)
        if (now()->diffInMinutes($tokenData->created_at) > 60) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('guard', 'web')
                ->delete();
            
            return back()->withErrors(['email' => 'Token reset password sudah kadaluarsa. Silakan minta link baru.']);
        }

        // Verify token
        if (!Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete used token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('guard', 'web')
            ->delete();

        return redirect()->route('auth.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}
