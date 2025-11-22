<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('guest:web')->except('logout');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        $kecamatan = \App\Models\Kecamatan::all();
        return view('auth.user.register', compact('kecamatan'));
    }

    /**
     * Handle user registration
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            // Upload foto profil (WAJIB)
            $fotoPath = $this->imageService->uploadUserPhoto($request->file('foto'));

            // Create user
            $user = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'id_kecamatan' => $request->id_kecamatan,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'foto' => $fotoPath,
            ]);

            // Log activity
            ActivityLog::createLog('user', $user->id_user, 'register', 'User registered successfully');

            // Auto login after registration
            Auth::guard('web')->login($user);

            return redirect()->route('user.dashboard')
                           ->with('success', 'Registrasi berhasil! Selamat datang di Portal Kerja Murung Raya.');
        } catch (\Exception $e) {
            return back()->with('error', 'Registrasi gagal: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.user.login');
    }

    /**
     * Handle user login
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

        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            // Log activity
            ActivityLog::createLog('user', $user->id_user, 'login', 'User logged in');

            return redirect()->intended(route('user.dashboard'))
                           ->with('success', 'Login berhasil! Selamat datang, ' . $user->nama);
        }

        return back()->with('error', 'Username atau password salah.')
                   ->withInput($request->only('username'));
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('web')->user();

        // Log activity
        if ($user) {
            ActivityLog::createLog('user', $user->id_user, 'logout', 'User logged out');
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                       ->with('success', 'Anda telah logout.');
    }
}
