<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Support\Facades\DB;

class StatistikApiController extends Controller
{
    /**
     * Get dashboard statistics.
     * GET /api/statistik/dashboard
     */
    public function dashboard()
    {
        $stats = [
            'pencari_kerja' => [
                'total' => User::count(),
                'registered_today' => User::whereDate('created_at', today())->count(),
                'registered_this_month' => User::whereMonth('created_at', now()->month)->count()
            ],
            'perusahaan' => [
                'total' => Perusahaan::count(),
                'with_active_jobs' => Perusahaan::whereHas('pekerjaan', function($q) {
                    $q->where('status', 'Aktif');
                })->count()
            ],
            'lowongan' => [
                'total' => Pekerjaan::count(),
                'aktif' => Pekerjaan::where('status', 'Aktif')->count(),
                'tidak_aktif' => Pekerjaan::where('status', 'Tidak Aktif')->count()
            ],
            'lamaran' => [
                'total' => Lamaran::count(),
                'pending' => Lamaran::where('status', 'Pending')->count(),
                'diterima' => Lamaran::where('status', 'Diterima')->count(),
                'ditolak' => Lamaran::where('status', 'Ditolak')->count()
            ],
            'pelatihan' => [
                'total_program' => Pelatihan::count(),
                'total_peserta' => PelatihanPeserta::count(),
                'upcoming' => Pelatihan::where('tanggal_mulai', '>', now())->count(),
                'ongoing' => Pelatihan::where('tanggal_mulai', '<=', now())
                    ->where('tanggal_selesai', '>=', now())->count()
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistik dashboard berhasil diambil',
            'data' => $stats
        ]);
    }

    /**
     * Get employment statistics by kecamatan.
     * GET /api/statistik/kecamatan
     */
    public function kecamatan()
    {
        $kecamatan = Kecamatan::withCount(['users as pencari_kerja', 'pekerjaan as lowongan'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_kecamatan' => $item->nama_kecamatan,
                    'pencari_kerja' => $item->pencari_kerja,
                    'lowongan' => $item->lowongan,
                    'koordinat' => [
                        'latitude' => $item->latitude,
                        'longitude' => $item->longitude
                    ]
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Statistik per kecamatan berhasil diambil',
            'data' => $kecamatan
        ]);
    }

    /**
     * Get employment statistics by sektor.
     * GET /api/statistik/sektor
     */
    public function sektor()
    {
        $sektor = Sektor::withCount(['perusahaan', 'pekerjaan'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_kategori' => $item->nama_kategori,
                    'perusahaan' => $item->perusahaan_count,
                    'lowongan' => $item->pekerjaan_count
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Statistik per sektor berhasil diambil',
            'data' => $sektor
        ]);
    }

    /**
     * Get trend statistics.
     * GET /api/statistik/trend
     */
    public function trend(Request $request)
    {
        $months = $request->get('months', 6); // Default 6 bulan terakhir
        
        $trend = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('Y-m');
            
            $trend[] = [
                'bulan' => $date->format('F Y'),
                'pencari_kerja' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'lowongan' => Pekerjaan::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'lamaran' => Lamaran::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'pelatihan' => Pelatihan::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count()
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Trend statistik berhasil diambil',
            'data' => $trend
        ]);
    }

    /**
     * Get comprehensive report data.
     * GET /api/statistik/report
     */
    public function report(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $report = [
            'periode' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nama_bulan' => now()->month($bulan)->format('F')
            ],
            'summary' => [
                'pencari_kerja' => User::count(),
                'perusahaan' => Perusahaan::count(),
                'lowongan_aktif' => Pekerjaan::where('status', 'Aktif')->count(),
                'total_lamaran' => Lamaran::count()
            ],
            'pencari_kerja_per_kecamatan' => User::with('kecamatan')
                ->selectRaw('kecamatan_id, count(*) as total')
                ->groupBy('kecamatan_id')
                ->get(),
            'lowongan_per_kecamatan' => Pekerjaan::with('kecamatan')
                ->selectRaw('kecamatan_id, count(*) as total')
                ->groupBy('kecamatan_id')
                ->get(),
            'lowongan_per_sektor' => Pekerjaan::with('kategori')
                ->selectRaw('kategori_id, count(*) as total')
                ->groupBy('kategori_id')
                ->get(),
            'lamaran_status' => [
                'pending' => Lamaran::where('status', 'Pending')->count(),
                'diterima' => Lamaran::where('status', 'Diterima')->count(),
                'ditolak' => Lamaran::where('status', 'Ditolak')->count()
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Data laporan berhasil diambil',
            'data' => $report
        ]);
    }
}
