<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Pekerjaan;
use App\Models\Lamaran;
use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;
use App\Models\Kecamatan;
use App\Models\Sektor;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display laporan page
     */
    public function index()
    {
        return view('admin.laporan.index');
    }

    /**
     * Generate Laporan Kondisi Ketenagakerjaan
     */
    public function laporanKetenagakerjaan(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        
        $data = [
            'bulan' => Carbon::create($tahun, $bulan)->format('F Y'),
            'tahun' => $tahun,
            'generated_at' => now()->format('d F Y H:i'),
            
            // Statistik Umum
            'total_pencari_kerja' => User::count(),
            'total_perusahaan' => Perusahaan::count(),
            'total_lowongan' => Pekerjaan::count(),
            'lowongan_aktif' => Pekerjaan::aktif()->count(),
            'total_lamaran' => Lamaran::count(),
            
            // Per Kecamatan
            'pencari_kerja_per_kecamatan' => User::select('id_kecamatan', \DB::raw('count(*) as total'))
                ->groupBy('id_kecamatan')
                ->with('kecamatan')
                ->get(),
            
            'lowongan_per_kecamatan' => Pekerjaan::select('id_kecamatan', \DB::raw('count(*) as total'))
                ->groupBy('id_kecamatan')
                ->with('kecamatan')
                ->get(),
            
            // Per Sektor
            'lowongan_per_sektor' => Pekerjaan::select('id_kategori', \DB::raw('count(*) as total'))
                ->groupBy('id_kategori')
                ->with('kategori')
                ->get(),
            
            // Status Lamaran
            'lamaran_pending' => Lamaran::where('status', 'Pending')->count(),
            'lamaran_diterima' => Lamaran::where('status', 'Diterima')->count(),
            'lamaran_ditolak' => Lamaran::where('status', 'Ditolak')->count(),
            
            // Pelatihan
            'total_pelatihan' => Pelatihan::count(),
            'total_peserta_pelatihan' => PelatihanPeserta::count(),
        ];

        $pdf = Pdf::loadView('admin.laporan.ketenagakerjaan', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Ketenagakerjaan_' . $tahun . '_' . $bulan . '.pdf');
    }

    /**
     * Generate Laporan Lowongan Kerja
     */
    public function laporanLowongan(Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai ?? now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->tanggal_akhir ?? now()->endOfMonth()->format('Y-m-d');
        
        $lowongan = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
            ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_akhir])
            ->orderBy('tanggal_posting', 'desc')
            ->get();
        
        $data = [
            'tanggal_mulai' => Carbon::parse($tanggal_mulai)->format('d F Y'),
            'tanggal_akhir' => Carbon::parse($tanggal_akhir)->format('d F Y'),
            'generated_at' => now()->format('d F Y H:i'),
            'lowongan' => $lowongan,
            'total_lowongan' => $lowongan->count(),
            'total_perusahaan' => $lowongan->unique('id_perusahaan')->count(),
        ];

        $pdf = Pdf::loadView('admin.laporan.lowongan', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Lowongan_' . $tanggal_mulai . '_' . $tanggal_akhir . '.pdf');
    }

    /**
     * Generate Laporan Lamaran
     */
    public function laporanLamaran(Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai ?? now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->tanggal_akhir ?? now()->endOfMonth()->format('Y-m-d');
        
        $lamaran = Lamaran::with(['user', 'pekerjaan.perusahaan', 'pekerjaan.kecamatan'])
            ->whereBetween('tanggal_terkirim', [$tanggal_mulai, $tanggal_akhir])
            ->orderBy('tanggal_terkirim', 'desc')
            ->get();
        
        $data = [
            'tanggal_mulai' => Carbon::parse($tanggal_mulai)->format('d F Y'),
            'tanggal_akhir' => Carbon::parse($tanggal_akhir)->format('d F Y'),
            'generated_at' => now()->format('d F Y H:i'),
            'lamaran' => $lamaran,
            'total_lamaran' => $lamaran->count(),
            'total_pending' => $lamaran->where('status', 'Pending')->count(),
            'total_diterima' => $lamaran->where('status', 'Diterima')->count(),
            'total_ditolak' => $lamaran->where('status', 'Ditolak')->count(),
        ];

        $pdf = Pdf::loadView('admin.laporan.lamaran', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Lamaran_' . $tanggal_mulai . '_' . $tanggal_akhir . '.pdf');
    }

    /**
     * Generate Laporan Pelatihan
     */
    public function laporanPelatihan(Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai ?? now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->tanggal_akhir ?? now()->endOfMonth()->format('Y-m-d');
        
        $pelatihan = Pelatihan::with(['sektor', 'peserta.user'])
            ->whereBetween('tanggal_mulai', [$tanggal_mulai, $tanggal_akhir])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
        
        $data = [
            'tanggal_mulai' => Carbon::parse($tanggal_mulai)->format('d F Y'),
            'tanggal_akhir' => Carbon::parse($tanggal_akhir)->format('d F Y'),
            'generated_at' => now()->format('d F Y H:i'),
            'pelatihan' => $pelatihan,
            'total_pelatihan' => $pelatihan->count(),
            'total_peserta' => $pelatihan->sum(function($p) { return $p->peserta->count(); }),
        ];

        $pdf = Pdf::loadView('admin.laporan.pelatihan', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Pelatihan_' . $tanggal_mulai . '_' . $tanggal_akhir . '.pdf');
    }
}
