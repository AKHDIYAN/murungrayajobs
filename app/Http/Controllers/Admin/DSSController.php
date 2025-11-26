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
                     ->where('pekerjaan.status', '=', 'Diterima')
                     ->whereDate('pekerjaan.tanggal_expired', '>=', now());
            })
            ->select(
                'sektor.id_sektor',
                'sektor.nama_kategori',
                DB::raw('COUNT(DISTINCT pekerjaan.id_pekerjaan) as demand_lowongan'),
                DB::raw('COALESCE(SUM(pekerjaan.jumlah_lowongan), 0) as demand_total')
            )
            ->groupBy('sektor.id_sektor', 'sektor.nama_kategori')
            ->get()
            ->map(function($item) {
                // Count pencari kerja (all unemployed users as general supply)
                $item->supply_pencari_kerja = User::where('status_kerja', 'Menganggur')->count();
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
        $totalPelamar = User::count(); // Total user/pencari kerja terdaftar
        $totalLamaran = Lamaran::count(); // Total lamaran yang diajukan
        $totalDiterima = Lamaran::where('status', 'Diterima')->count();
        $tingkatSerapan = $totalLamaran > 0 ? round(($totalDiterima / $totalLamaran) * 100, 2) : 0;

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
        $totalUser = User::count();
        $totalBersertifikat = User::whereNotNull('jenis_sertifikasi')->count();
        $totalVerified = User::where('sertifikat_verified', true)->count();
        
        $sertifikasiStats = (object) [
            'total_user' => $totalUser,
            'total_bersertifikat' => $totalBersertifikat,
            'total_verified' => $totalVerified,
            'persentase_coverage' => $totalUser > 0 ? round(($totalBersertifikat / $totalUser) * 100, 2) : 0,
        ];

        // 7. Age Demographics
        $demografiUsia = collect([
            (object)['kelompok_usia' => '18-25', 'jumlah' => 0],
            (object)['kelompok_usia' => '26-35', 'jumlah' => 0],
            (object)['kelompok_usia' => '36-45', 'jumlah' => 0],
            (object)['kelompok_usia' => '46+', 'jumlah' => 0],
        ]);

        $usiaData = User::all()
            ->groupBy(function($user) {
                if (!$user->tanggal_lahir) {
                    return '18-25'; // Default untuk user tanpa tanggal lahir
                }
                
                try {
                    $umur = \Carbon\Carbon::parse($user->tanggal_lahir)->age;
                    if ($umur >= 18 && $umur <= 25) return '18-25';
                    if ($umur >= 26 && $umur <= 35) return '26-35';
                    if ($umur >= 36 && $umur <= 45) return '36-45';
                    if ($umur > 45) return '46+';
                    return '18-25'; // Default jika di luar range
                } catch (\Exception $e) {
                    return '18-25'; // Default jika parsing gagal
                }
            })
            ->map(function($group) {
                return $group->count();
            });

        $demografiUsia = $demografiUsia->map(function($item) use ($usiaData) {
            $item->jumlah = $usiaData->get($item->kelompok_usia, 0);
            return $item;
        });

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
