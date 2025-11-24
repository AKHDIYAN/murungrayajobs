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
            ->selectRaw('kecamatan.id_kecamatan, kecamatan.nama_kecamatan, COUNT(pekerjaan.id_pekerjaan) as total')
            ->groupBy('kecamatan.id_kecamatan', 'kecamatan.nama_kecamatan')
            ->get();

        // Total lowongan aktif
        $totalLowongan = Pekerjaan::aktif()->count();

        // Kecamatan dengan lowongan terbanyak
        $kecamatanTerbanyak = $kecamatanStats->sortByDesc('total')->first();

        return view('map', compact('kecamatanStats', 'totalLowongan', 'kecamatanTerbanyak'));
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
