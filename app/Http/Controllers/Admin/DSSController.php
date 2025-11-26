<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pekerjaan;
use App\Models\Perusahaan;
use App\Models\Kecamatan;
use App\Models\Sektor;
use App\Models\Pendidikan;
use App\Models\Lamaran;
use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DSSController extends Controller
{
    /**
     * Dashboard DSS - Decision Support System
     */
    public function index()
    {
        // === GAP ANALYSIS ===
        
        // 1. Supply vs Demand per Sektor
        $supplyDemandPerSektor = Sektor::leftJoin('pekerjaan', function($join) {
                $join->on('sektor.id_sektor', '=', 'pekerjaan.id_kategori')
                     ->where('pekerjaan.status', 'Diterima')
                     ->whereDate('pekerjaan.tanggal_expired', '>=', now());
            })
            ->leftJoin('user', 'user.id_pendidikan', '=', 'sektor.id_sektor') // Simplified mapping
            ->select(
                'sektor.id_sektor',
                'sektor.nama_sektor',
                DB::raw('COUNT(DISTINCT pekerjaan.id_pekerjaan) as demand_lowongan'),
                DB::raw('SUM(pekerjaan.jumlah_lowongan) as demand_total'),
                DB::raw('COUNT(DISTINCT user.id_user) as supply_pencari_kerja')
            )
            ->groupBy('sektor.id_sektor', 'sektor.nama_sektor')
            ->get()
            ->map(function($item) {
                $item->gap = $item->supply_pencari_kerja - $item->demand_total;
                $item->gap_status = $item->gap > 0 ? 'Surplus' : 'Defisit';
                return $item;
            });

        // 2. Unemployment Rate per Kecamatan
        $pengangguranPerKecamatan = Kecamatan::leftJoin('user', 'kecamatan.id_kecamatan', '=', 'user.id_kecamatan')
            ->select(
                'kecamatan.id_kecamatan',
                'kecamatan.nama_kecamatan',
                DB::raw('COUNT(user.id_user) as total_pencari_kerja'),
                DB::raw('SUM(CASE WHEN user.status_kerja = "Menganggur" THEN 1 ELSE 0 END) as total_menganggur'),
                DB::raw('SUM(CASE WHEN user.status_kerja = "Bekerja" THEN 1 ELSE 0 END) as total_bekerja')
            )
            ->groupBy('kecamatan.id_kecamatan', 'kecamatan.nama_kecamatan')
            ->get()
            ->map(function($item) {
                $item->tingkat_pengangguran = $item->total_pencari_kerja > 0 
                    ? round(($item->total_menganggur / $item->total_pencari_kerja) * 100, 2)
                    : 0;
                return $item;
            });

        // 3. Job Absorption Rate (Tingkat Serapan)
        $totalLowongan = Pekerjaan::aktif()->sum('jumlah_lowongan');
        $totalPelamar = Lamaran::count();
        $totalDiterima = Lamaran::where('status', 'Diterima')->count();
        $tingkatSerapan = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 2) : 0;

        // 4. Skills Gap - Most Needed vs Available
        $skillsDemand = Pekerjaan::aktif()
            ->with('kategori')
            ->get()
            ->groupBy('id_kategori')
            ->map(function($jobs, $key) {
                return [
                    'sektor' => $jobs->first()->kategori->nama_sektor ?? 'Unknown',
                    'demand' => $jobs->count(),
                ];
            })
            ->sortByDesc('demand')
            ->take(10);

        $skillsSupply = User::where('status_kerja', 'Menganggur')
            ->whereNotNull('skills')
            ->get()
            ->pluck('skills')
            ->flatten()
            ->countBy()
            ->sortByDesc(function($count) { return $count; })
            ->take(10);

        // 5. Training Needs Recommendation
        $pelatihanNeeded = $supplyDemandPerSektor
            ->filter(function($item) {
                return $item->gap < 0; // Defisit = butuh pelatihan
            })
            ->map(function($item) {
                $pelatihanTersedia = Pelatihan::where('id_sektor', $item->id_sektor)
                    ->where('status', 'Dibuka')
                    ->count();
                
                $item->pelatihan_tersedia = $pelatihanTersedia;
                $item->rekomendasi_pelatihan = abs($item->gap);
                return $item;
            })
            ->sortByDesc('rekomendasi_pelatihan');

        // 6. Certification Status
        $sertifikasiStats = [
            'total_user' => User::count(),
            'tersertifikasi' => User::where('sertifikat_verified', true)->count(),
            'belum_sertifikasi' => User::where('sertifikat_verified', false)->orWhereNull('sertifikat')->count(),
        ];
        $sertifikasiStats['persentase_sertifikasi'] = $sertifikasiStats['total_user'] > 0
            ? round(($sertifikasiStats['tersertifikasi'] / $sertifikasiStats['total_user']) * 100, 2)
            : 0;

        // 7. Age Demographics
        $demografiUsia = User::select(
                DB::raw('CASE 
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 18 AND 25 THEN "18-25"
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 26 AND 35 THEN "26-35"
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 36 AND 45 THEN "36-45"
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) > 45 THEN "46+"
                    ELSE "Unknown"
                END as kelompok_usia'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereNotNull('tanggal_lahir')
            ->groupBy('kelompok_usia')
            ->get();

        return view('admin.dss.index', compact(
            'supplyDemandPerSektor',
            'pengangguranPerKecamatan',
            'totalLowongan',
            'totalPelamar',
            'totalDiterima',
            'tingkatSerapan',
            'skillsDemand',
            'skillsSupply',
            'pelatihanNeeded',
            'sertifikasiStats',
            'demografiUsia'
        ));
    }

    /**
     * Export DSS Report
     */
    public function export(Request $request)
    {
        // Will be implemented with Excel export
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
