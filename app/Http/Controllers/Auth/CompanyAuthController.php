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
        $kecamatans = \App\Models\Kecamatan::orderBy('nama_kecamatan')->get();
        return view('auth.company.register', compact('kecamatans'));
    }

    /**
     * Handle company registration
     */
    public function register(RegisterCompanyRequest $request)
    {
        try {
            // Upload dan resize logo perusahaan (WAJIB, akan diresize jadi 200x200px)
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

            // Redirect ke halaman login dengan pesan sukses
            return redirect()->route('company.login')
                           ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.')
                           ->with('info', 'Akun Anda akan diverifikasi oleh admin sebelum dapat memposting lowongan.');
        } catch (\Exception $e) {
            // Jika ada error, hapus logo yang sudah terupload (jika ada)
            if (isset($logoPath) && $logoPath) {
                $this->imageService->deleteImage($logoPath);
            }

            return back()->with('error', 'Registrasi gagal: ' . $e->getMessage())
                       ->withInput($request->except('password', 'password_confirmation'));
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
