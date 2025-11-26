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
        // Get kecamatan with job counts (menggunakan status 'Diterima' dan tanggal tidak expired)
        $kecamatanStats = Kecamatan::leftJoin('pekerjaan', function($join) {
                $join->on('kecamatan.id_kecamatan', '=', 'pekerjaan.id_kecamatan')
                     ->where('pekerjaan.status', '=', 'Diterima')
                     ->whereDate('pekerjaan.tanggal_expired', '>=', \Carbon\Carbon::today());
            })
            ->leftJoin('user', 'kecamatan.id_kecamatan', '=', 'user.id_kecamatan')
            ->selectRaw('
                kecamatan.id_kecamatan, 
                kecamatan.nama_kecamatan, 
                kecamatan.slug,
                COUNT(DISTINCT pekerjaan.id_pekerjaan) as total_lowongan,
                COUNT(DISTINCT user.id_user) as total_pencari_kerja,
                SUM(CASE WHEN user.status_kerja = "Menganggur" THEN 1 ELSE 0 END) as total_menganggur,
                SUM(CASE WHEN user.status_kerja = "Bekerja" THEN 1 ELSE 0 END) as total_bekerja
            ')
            ->groupBy('kecamatan.id_kecamatan', 'kecamatan.nama_kecamatan', 'kecamatan.slug')
            ->get()
            ->map(function($item) {
                $item->tingkat_pengangguran = $item->total_pencari_kerja > 0 
                    ? round(($item->total_menganggur / $item->total_pencari_kerja) * 100, 1)
                    : 0;
                return $item;
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
