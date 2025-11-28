<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Sektor;
use App\Models\Statistik;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Homepage
     */
    public function index()
    {
        // Get latest active jobs
        $latestJobs = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
                               ->aktif()
                               ->orderBy('tanggal_posting', 'desc')
                               ->limit(6)
                               ->get();

        // Statistics
        $totalJobs = Pekerjaan::aktif()->count();
        $totalCompanies = Perusahaan::verified()->count();
        $totalUsers = User::count();
        $totalKecamatan = Kecamatan::count();

        // Popular categories
        $popularCategories = Sektor::withCount(['pekerjaan' => function($q) {
                                        $q->aktif();
                                    }])
                                    ->orderBy('pekerjaan_count', 'desc')
                                    ->limit(8)
                                    ->get();

        return view('home', compact(
            'latestJobs',
            'totalJobs',
            'totalCompanies',
            'totalUsers',
            'totalKecamatan',
            'popularCategories'
        ));
    }

    /**
     * Map page
     */
    public function map()
    {
        // Get kecamatan with job counts and user data
        $kecamatanStats = Kecamatan::select([
                'kecamatan.id_kecamatan', 
                'kecamatan.nama_kecamatan'
            ])
            ->get()
            ->map(function($kecamatan) {
                // Count jobs for this kecamatan
                $totalLowongan = Pekerjaan::where('id_kecamatan', $kecamatan->id_kecamatan)
                    ->where('status', 'Diterima')
                    ->whereDate('tanggal_expired', '>=', \Carbon\Carbon::today())
                    ->count();

                // Count users for this kecamatan
                $totalPencariKerja = User::where('id_kecamatan', $kecamatan->id_kecamatan)->count();
                $totalMenganggur = User::where('id_kecamatan', $kecamatan->id_kecamatan)
                    ->where('status_kerja', 'Menganggur')
                    ->count();
                $totalBekerja = User::where('id_kecamatan', $kecamatan->id_kecamatan)
                    ->where('status_kerja', 'Bekerja')
                    ->count();

                $kecamatan->total_lowongan = $totalLowongan;
                $kecamatan->total_pencari_kerja = $totalPencariKerja;
                $kecamatan->total_menganggur = $totalMenganggur;
                $kecamatan->total_bekerja = $totalBekerja;
                $kecamatan->tingkat_pengangguran = $totalPencariKerja > 0 
                    ? round(($totalMenganggur / $totalPencariKerja) * 100, 1)
                    : 0;
                
                // Untuk compatibility dengan yang lama
                $kecamatan->total = $totalLowongan;

                return $kecamatan;
            });

        // Total lowongan aktif
        $totalLowongan = Pekerjaan::aktif()->count();
        
        // Total pencari kerja
        $totalPencariKerja = User::count();
        $totalMenganggur = User::where('status_kerja', 'Menganggur')->count();

        // Kecamatan dengan lowongan terbanyak
        $kecamatanTerbanyak = $kecamatanStats->sortByDesc('total_lowongan')->first();

        return view('map', compact(
            'kecamatanStats', 
            'totalLowongan', 
            'totalPencariKerja',
            'totalMenganggur',
            'kecamatanTerbanyak'
        ));
    }

    /**
     * API: Get kecamatan list
     */
    public function apiKecamatan()
    {
        $kecamatan = Kecamatan::all();
        return response()->json($kecamatan);
    }

    /**
     * API: Get sektor list
     */
    public function apiSektor()
    {
        $sektor = Sektor::all();
        return response()->json($sektor);
    }

    /**
     * API: Get map data for Leaflet
     */
    public function apiMapData()
    {
        $kecamatan = Kecamatan::with(['pekerjaan' => function($q) {
                                    $q->aktif();
                                }])
                                ->get()
                                ->map(function($k) {
                                    return [
                                        'id' => $k->id_kecamatan,
                                        'name' => $k->nama_kecamatan,
                                        'jobs_count' => $k->pekerjaan->count(),
                                        // Add coordinates if available
                                        // 'lat' => $k->latitude,
                                        // 'lng' => $k->longitude,
                                    ];
                                });

        return response()->json($kecamatan);
    }
}
