<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Sektor;
use App\Models\Pendidikan;
use App\Models\Usia;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /*
    |--------------------------------------------------------------------------
    | KECAMATAN Management
    |--------------------------------------------------------------------------
    */

    /**
     * List all kecamatan
     */
    public function kecamatanIndex()
    {
        $kecamatan = Kecamatan::withCount(['users', 'perusahaan', 'pekerjaan'])
                              ->orderBy('nama_kecamatan')
                              ->paginate(15);

        return view('admin.master-data.kecamatan.index', compact('kecamatan'));
    }

    /**
     * Store new kecamatan
     */
    public function kecamatanStore(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:255|unique:kecamatan,nama_kecamatan',
        ], [
            'nama_kecamatan.required' => 'Nama kecamatan wajib diisi',
            'nama_kecamatan.unique' => 'Kecamatan sudah ada',
        ]);

        try {
            Kecamatan::create($request->only('nama_kecamatan'));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'create', 'Created kecamatan: ' . $request->nama_kecamatan);

            return back()->with('success', 'Kecamatan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan kecamatan: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Update kecamatan
     */
    public function kecamatanUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:255|unique:kecamatan,nama_kecamatan,' . $id . ',id_kecamatan',
        ]);

        try {
            $kecamatan = Kecamatan::findOrFail($id);
            $oldName = $kecamatan->nama_kecamatan;
            $kecamatan->update($request->only('nama_kecamatan'));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated kecamatan: ' . $oldName . ' to ' . $request->nama_kecamatan);

            return back()->with('success', 'Kecamatan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui kecamatan: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete kecamatan
     */
    public function kecamatanDestroy($id)
    {
        try {
            $kecamatan = Kecamatan::findOrFail($id);
            
            // Check if kecamatan is being used
            if ($kecamatan->users()->exists() || $kecamatan->perusahaan()->exists() || $kecamatan->pekerjaan()->exists()) {
                return back()->with('error', 'Kecamatan tidak dapat dihapus karena masih digunakan.');
            }

            $nama = $kecamatan->nama_kecamatan;
            $kecamatan->delete();

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted kecamatan: ' . $nama);

            return back()->with('success', 'Kecamatan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kecamatan: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SEKTOR Management
    |--------------------------------------------------------------------------
    */

    /**
     * List all sektor
     */
    public function sektorIndex()
    {
        $sektor = Sektor::withCount('pekerjaan')
                        ->orderBy('nama_kategori')
                        ->paginate(15);

        return view('admin.master-data.sektor.index', compact('sektor'));
    }

    /**
     * Store new sektor
     */
    public function sektorStore(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:sektor,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama sektor wajib diisi',
            'nama_kategori.unique' => 'Sektor sudah ada',
        ]);

        try {
            Sektor::create($request->only('nama_kategori'));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'create', 'Created sektor: ' . $request->nama_kategori);

            return back()->with('success', 'Sektor berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan sektor: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Update sektor
     */
    public function sektorUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:sektor,nama_kategori,' . $id . ',id_sektor',
        ]);

        try {
            $sektor = Sektor::findOrFail($id);
            $oldName = $sektor->nama_kategori;
            $sektor->update($request->only('nama_kategori'));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated sektor: ' . $oldName . ' to ' . $request->nama_kategori);

            return back()->with('success', 'Sektor berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui sektor: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete sektor
     */
    public function sektorDestroy($id)
    {
        try {
            $sektor = Sektor::findOrFail($id);
            
            // Check if sektor is being used
            if ($sektor->pekerjaan()->exists()) {
                return back()->with('error', 'Sektor tidak dapat dihapus karena masih digunakan.');
            }

            $nama = $sektor->nama_kategori;
            $sektor->delete();

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted sektor: ' . $nama);

            return back()->with('success', 'Sektor berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus sektor: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PENDIDIKAN Management
    |--------------------------------------------------------------------------
    */

    /**
     * List all pendidikan
     */
    public function pendidikanIndex()
    {
        $pendidikan = Pendidikan::withCount('statistik')
                                ->orderBy('id_pendidikan')
                                ->paginate(15);

        return view('admin.master-data.pendidikan.index', compact('pendidikan'));
    }

    /**
     * Store new pendidikan
     */
    public function pendidikanStore(Request $request)
    {
        $request->validate([
            'tingkatan_pendidikan' => 'required|string|max:255|unique:pendidikan,tingkatan_pendidikan',
        ], [
            'tingkatan_pendidikan.required' => 'Tingkatan pendidikan wajib diisi',
            'tingkatan_pendidikan.unique' => 'Tingkatan pendidikan sudah ada',
        ]);

        try {
            Pendidikan::create($request->only('tingkatan_pendidikan'));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'create', 'Created pendidikan: ' . $request->tingkatan_pendidikan);

            return back()->with('success', 'Tingkatan pendidikan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan tingkatan pendidikan: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Update pendidikan
     */
    public function pendidikanUpdate(Request $request, $id)
    {
        $request->validate([
            'tingkatan_pendidikan' => 'required|string|max:255|unique:pendidikan,tingkatan_pendidikan,' . $id . ',id_pendidikan',
        ]);

        try {
            $pendidikan = Pendidikan::findOrFail($id);
            $oldName = $pendidikan->tingkatan_pendidikan;
            $pendidikan->update($request->only('tingkatan_pendidikan'));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated pendidikan: ' . $oldName . ' to ' . $request->tingkatan_pendidikan);

            return back()->with('success', 'Tingkatan pendidikan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui tingkatan pendidikan: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete pendidikan
     */
    public function pendidikanDestroy($id)
    {
        try {
            $pendidikan = Pendidikan::findOrFail($id);
            
            // Check if pendidikan is being used
            if ($pendidikan->statistik()->exists()) {
                return back()->with('error', 'Tingkatan pendidikan tidak dapat dihapus karena masih digunakan.');
            }

            $nama = $pendidikan->tingkatan_pendidikan;
            $pendidikan->delete();

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted pendidikan: ' . $nama);

            return back()->with('success', 'Tingkatan pendidikan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tingkatan pendidikan: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | USIA Management
    |--------------------------------------------------------------------------
    */

    /**
     * List all usia
     */
    public function usiaIndex()
    {
        $usia = Usia::withCount('statistik')
                    ->orderBy('id_usia')
                    ->paginate(15);

        return view('admin.master-data.usia.index', compact('usia'));
    }

    /**
     * Store new usia
     */
    public function usiaStore(Request $request)
    {
        $request->validate([
            'kelompok_usia' => 'required|string|max:255|unique:usia,kelompok_usia',
        ], [
            'kelompok_usia.required' => 'Kelompok usia wajib diisi',
            'kelompok_usia.unique' => 'Kelompok usia sudah ada',
        ]);

        try {
            Usia::create($request->only('kelompok_usia'));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'create', 'Created usia: ' . $request->kelompok_usia);

            return back()->with('success', 'Kelompok usia berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan kelompok usia: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Update usia
     */
    public function usiaUpdate(Request $request, $id)
    {
        $request->validate([
            'kelompok_usia' => 'required|string|max:255|unique:usia,kelompok_usia,' . $id . ',id_usia',
        ]);

        try {
            $usia = Usia::findOrFail($id);
            $oldName = $usia->kelompok_usia;
            $usia->update($request->only('kelompok_usia'));

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'update', 'Updated usia: ' . $oldName . ' to ' . $request->kelompok_usia);

            return back()->with('success', 'Kelompok usia berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui kelompok usia: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Delete usia
     */
    public function usiaDestroy($id)
    {
        try {
            $usia = Usia::findOrFail($id);
            
            // Check if usia is being used
            if ($usia->statistik()->exists()) {
                return back()->with('error', 'Kelompok usia tidak dapat dihapus karena masih digunakan.');
            }

            $nama = $usia->kelompok_usia;
            $usia->delete();

            // Log activity
            $admin = Auth::guard('admin')->user();
            ActivityLog::createLog('admin', $admin->id_admin, 'delete', 'Deleted usia: ' . $nama);

            return back()->with('success', 'Kelompok usia berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kelompok usia: ' . $e->getMessage());
        }
    }
}
