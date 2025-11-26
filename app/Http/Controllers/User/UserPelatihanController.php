<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PelatihanPeserta;
use Illuminate\Support\Facades\Auth;

class UserPelatihanController extends Controller
{
    /**
     * Display training history
     */
    public function riwayat()
    {
        $riwayatPelatihan = PelatihanPeserta::with(['pelatihan.sektor'])
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.pelatihan.riwayat', compact('riwayatPelatihan'));
    }

    /**
     * Download certificate for user
     */
    public function downloadSertifikat($pesertaId)
    {
        $peserta = PelatihanPeserta::with(['pelatihan', 'user'])
            ->where('id_peserta_pelatihan', $pesertaId)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        // Check if certificate file exists
        if (!$peserta->file_sertifikat) {
            return redirect()->back()->with('error', 'Sertifikat belum tersedia. Silakan hubungi admin.');
        }

        // Check if file exists in storage
        if (!\Storage::disk('public')->exists($peserta->file_sertifikat)) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        // Download file
        return response()->download(
            storage_path('app/public/' . $peserta->file_sertifikat),
            'Sertifikat_' . str_replace(' ', '_', $peserta->user->nama) . '.' . pathinfo($peserta->file_sertifikat, PATHINFO_EXTENSION)
        );
    }
}
