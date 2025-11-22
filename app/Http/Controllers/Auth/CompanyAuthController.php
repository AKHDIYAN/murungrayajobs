<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCompanyRequest;
use App\Models\Perusahaan;
use App\Models\ActivityLog;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyAuthController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('guest:company')->except('logout');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        $kecamatan = \App\Models\Kecamatan::all();
        return view('auth.company.register', compact('kecamatan'));
    }

    /**
     * Handle company registration
     */
    public function register(RegisterCompanyRequest $request)
    {
        try {
            // Upload logo perusahaan (WAJIB)
            $logoPath = $this->imageService->uploadCompanyLogo($request->file('logo'));

            // Create company
            $company = Perusahaan::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'nama_perusahaan' => $request->nama_perusahaan,
                'id_kecamatan' => $request->id_kecamatan,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'deskripsi' => $request->deskripsi,
                'logo' => $logoPath,
                'is_verified' => false, // Perlu verifikasi admin
            ]);

            // Log activity
            ActivityLog::createLog('company', $company->id_perusahaan, 'register', 'Company registered successfully');

            // Auto login after registration
            Auth::guard('company')->login($company);

            return redirect()->route('company.dashboard')
                           ->with('success', 'Registrasi berhasil! Akun Anda menunggu verifikasi admin.')
                           ->with('info', 'Anda dapat mulai membuat lowongan, namun lowongan akan ditampilkan setelah akun terverifikasi.');
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
        return view('auth.company.login');
    }

    /**
     * Handle company login
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

        if (Auth::guard('company')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $company = Auth::guard('company')->user();

            // Log activity
            ActivityLog::createLog('company', $company->id_perusahaan, 'login', 'Company logged in');

            return redirect()->intended(route('company.dashboard'))
                           ->with('success', 'Login berhasil! Selamat datang, ' . $company->nama_perusahaan);
        }

        return back()->with('error', 'Username atau password salah.')
                   ->withInput($request->only('username'));
    }

    /**
     * Handle company logout
     */
    public function logout(Request $request)
    {
        $company = Auth::guard('company')->user();

        // Log activity
        if ($company) {
            ActivityLog::createLog('company', $company->id_perusahaan, 'logout', 'Company logged out');
        }

        Auth::guard('company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                       ->with('success', 'Anda telah logout.');
    }
}
