<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;
use App\Models\Sektor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelatihanController extends Controller
{
    /**
     * Display listing of trainings
     */
    public function index(Request $request)
    {
        $query = Pelatihan::with(['sektor', 'pesertaDiterima']);

        // Filter by sector
        if ($request->filled('sektor')) {
            $query->where('id_sektor', $request->sektor);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('jenis')) {
            $query->where('jenis_pelatihan', $request->jenis);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_pelatihan', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('penyelenggara', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'LIKE', '%' . $request->search . '%');
            });
        }

        $pelatihan = $query->orderBy('tanggal_mulai', 'desc')->paginate(12);
        $sektorList = Sektor::all();

        return view('pelatihan.index', compact('pelatihan', 'sektorList'));
    }

    /**
     * Show single training detail
     */
    public function show($id)
    {
        $pelatihan = Pelatihan::with(['sektor', 'pesertaDiterima', 'peserta' => function($q) {
            $q->where('id_user', Auth::id());
        }])->findOrFail($id);

        $sudahDaftar = $pelatihan->peserta->where('id_user', Auth::id())->first();

        return view('pelatihan.show', compact('pelatihan', 'sudahDaftar'));
    }

    /**
     * Register for training
     */
    public function daftar(Request $request, $id)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('user.login')
                           ->with('error', 'Silakan login terlebih dahulu untuk mendaftar pelatihan');
        }

        $pelatihan = Pelatihan::findOrFail($id);

        // Check if already registered
        $sudahDaftar = PelatihanPeserta::where('id_pelatihan', $id)
                                       ->where('id_user', Auth::id())
                                       ->exists();

        if ($sudahDaftar) {
            return redirect()->back()->with('error', 'Anda sudah mendaftar pelatihan ini');
        }

        // Check quota
        if ($pelatihan->sisa_kuota <= 0) {
            return redirect()->back()->with('error', 'Kuota pelatihan sudah penuh');
        }

        // Check if registration is open
        if (!$pelatihan->is_pendaftaran_buka) {
            return redirect()->back()->with('error', 'Pendaftaran pelatihan sudah ditutup');
        }

        $request->validate([
            'alasan_mengikuti' => 'required|string|max:500',
        ]);

        PelatihanPeserta::create([
            'id_pelatihan' => $id,
            'id_user' => Auth::id(),
            'status_pendaftaran' => 'Pending',
            'alasan_mengikuti' => $request->alasan_mengikuti,
        ]);

        return redirect()->route('user.pelatihan.riwayat')
                       ->with('success', 'Pendaftaran berhasil! Menunggu konfirmasi admin.');
    }
}
