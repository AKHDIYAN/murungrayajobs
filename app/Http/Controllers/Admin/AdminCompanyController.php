<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\Kecamatan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * List all companies
     */
    public function index(Request $request)
    {
        $query = Perusahaan::with(['kecamatan', 'pekerjaan']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_perusahaan', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        // Filter by verification status
        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === '1');
        }

        $companies = $query->orderBy('created_at', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        $kecamatanList = Kecamatan::all();

        // Statistics
        $verifiedCount = Perusahaan::where('is_verified', true)->count();
        $unverifiedCount = Perusahaan::where('is_verified', false)->count();
        $totalJobs = \App\Models\Pekerjaan::count();

        return view('admin.companies.index', compact(
            'companies', 
            'kecamatanList',
            'verifiedCount',
            'unverifiedCount',
            'totalJobs'
        ));
    }

    /**
     * Show company detail
     */
    public function show($id)
    {
        $company = Perusahaan::with(['kecamatan', 'pekerjaan.lamaran'])
                             ->findOrFail($id);

        // Statistics
        $totalJobs = $company->pekerjaan->count();
        $activeJobs = $company->pekerjaan->filter(function($job) {
            return $job->is_aktif;
        })->count();
        $totalApplications = $company->pekerjaan->sum(function($job) {
            return $job->lamaran->count();
        });

        return view('admin.companies.show', compact(
            'company',
            'totalJobs',
            'activeJobs',
            'totalApplications'
        ));
    }

    /**
     * Show edit company form
     */
    public function edit($id)
    {
        $company = Perusahaan::findOrFail($id);
        $kecamatanList = Kecamatan::all();

        return view('admin.companies.edit', compact('company', 'kecamatanList'));
    }

    /**
     * Update company
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:perusahaan,username,' . $id . ',id_perusahaan',
            'email' => 'required|email|unique:perusahaan,email,' . $id . ',id_perusahaan',
            'no_telepon' => 'nullable|string|min:10|max:15',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
            'tahun_berdiri' => 'nullable|integer|min:1900|max:' . date('Y'),
            'website' => 'nullable|url',
            'is_verified' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        try {
            $company = Perusahaan::findOrFail($id);
            
            $data = $request->only([
                'nama_perusahaan',
                'username',
                'email',
                'no_telepon',
                'alamat',
                'deskripsi',
                'id_kecamatan',
                'tahun_berdiri',
                'website',
                'is_verified'
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($company->logo && \Storage::exists('public/' . $company->logo)) {
                    \Storage::delete('public/' . $company->logo);
                }
                $data['logo'] = $request->file('logo')->store('logos', 'public');
            }

            // Handle password update
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $company->update($data);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated company: ' . $company->nama_perusahaan);

            return redirect()->route('admin.companies.show', $id)
                           ->with('success', 'Data perusahaan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data perusahaan: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete company
     */
    public function destroy($id)
    {
        try {
            $company = Perusahaan::findOrFail($id);
            $nama = $company->nama_perusahaan;
            $company->delete();

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted company: ' . $nama);

            return redirect()->route('admin.companies.index')
                           ->with('success', 'Perusahaan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus perusahaan: ' . $e->getMessage());
        }
    }

    /**
     * Verify company
     */
    public function verify($id)
    {
        try {
            $company = Perusahaan::findOrFail($id);
            $company->update(['is_verified' => true]);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Verified company: ' . $company->nama_perusahaan);

            return back()->with('success', 'Perusahaan berhasil diverifikasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memverifikasi perusahaan: ' . $e->getMessage());
        }
    }

    /**
     * Unverify company
     */
    public function unverify($id)
    {
        try {
            $company = Perusahaan::findOrFail($id);
            $company->update(['is_verified' => false]);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Unverified company: ' . $company->nama_perusahaan);

            return back()->with('success', 'Verifikasi perusahaan berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Suspend company
     */
    public function suspend($id)
    {
        try {
            $company = Perusahaan::findOrFail($id);
            $company->update(['is_verified' => false]);

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Suspended company: ' . $company->nama_perusahaan);

            return back()->with('success', 'Perusahaan berhasil ditangguhkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menangguhkan perusahaan: ' . $e->getMessage());
        }
    }
}
