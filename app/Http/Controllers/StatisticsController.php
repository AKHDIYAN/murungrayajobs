<?php

namespace App\Http\Controllers;

use App\Models\Statistik;
use App\Models\Kecamatan;
use App\Models\Pendidikan;
use App\Models\Usia;
use App\Models\Sektor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Statistics page
     */
    public function index(Request $request)
    {
        // Get filters
        $selectedKecamatan = $request->get('kecamatan', null);
        $selectedPendidikan = $request->get('pendidikan', null);
        $selectedUsia = $request->get('usia', null);

        // Base query
        $query = Statistik::with(['kecamatan', 'pendidikan', 'usia', 'sektor']);

        // Apply filters
        if ($selectedKecamatan) {
            $query->where('id_kecamatan', $selectedKecamatan);
        }

        if ($selectedPendidikan) {
            $query->where('id_pendidikan', $selectedPendidikan);
        }

        if ($selectedUsia) {
            $query->where('id_usia', $selectedUsia);
        }

        // Get statistics
        $totalData = $query->count();
        $bekerja = (clone $query)->bekerja()->count();
        $menganggur = (clone $query)->menganggur()->count();

        // Statistics by gender
        $byGender = (clone $query)->select('jenis_kelamin', DB::raw('count(*) as total'))
                                   ->groupBy('jenis_kelamin')
                                   ->get();

        // Statistics by kecamatan
        $byKecamatan = Statistik::with('kecamatan')
                                ->select('id_kecamatan', 
                                        DB::raw('count(*) as total'),
                                        DB::raw('sum(case when status = "Bekerja" then 1 else 0 end) as bekerja'),
                                        DB::raw('sum(case when status = "Menganggur" then 1 else 0 end) as menganggur'))
                                ->groupBy('id_kecamatan')
                                ->get();

        // Statistics by pendidikan
        $byPendidikan = Statistik::with('pendidikan')
                                 ->select('id_pendidikan',
                                         DB::raw('count(*) as total'),
                                         DB::raw('sum(case when status = "Bekerja" then 1 else 0 end) as bekerja'),
                                         DB::raw('sum(case when status = "Menganggur" then 1 else 0 end) as menganggur'))
                                 ->groupBy('id_pendidikan')
                                 ->get();

        // Statistics by usia
        $byUsia = Statistik::with('usia')
                           ->select('id_usia',
                                   DB::raw('count(*) as total'),
                                   DB::raw('sum(case when status = "Bekerja" then 1 else 0 end) as bekerja'),
                                   DB::raw('sum(case when status = "Menganggur" then 1 else 0 end) as menganggur'))
                           ->groupBy('id_usia')
                           ->get();

        // Statistics by sektor (only for employed)
        $bySektor = Statistik::with('sektor')
                             ->bekerja()
                             ->whereNotNull('id_sektor')
                             ->select('id_sektor', DB::raw('count(*) as total'))
                             ->groupBy('id_sektor')
                             ->orderBy('total', 'desc')
                             ->get();

        // For filter dropdowns
        $kecamatanList = Kecamatan::all();
        $pendidikanList = Pendidikan::all();
        $usiaList = Usia::all();

        return view('statistics.index', compact(
            'totalData',
            'bekerja',
            'menganggur',
            'byGender',
            'byKecamatan',
            'byPendidikan',
            'byUsia',
            'bySektor',
            'kecamatanList',
            'pendidikanList',
            'usiaList',
            'selectedKecamatan',
            'selectedPendidikan',
            'selectedUsia'
        ));
    }

    /**
     * API: Statistics data (JSON)
     */
    public function apiData(Request $request)
    {
        $data = [
            'total' => Statistik::count(),
            'bekerja' => Statistik::bekerja()->count(),
            'menganggur' => Statistik::menganggur()->count(),
            'by_gender' => Statistik::select('jenis_kelamin', DB::raw('count(*) as total'))
                                    ->groupBy('jenis_kelamin')
                                    ->get(),
            'by_kecamatan' => Statistik::with('kecamatan')
                                       ->select('id_kecamatan', DB::raw('count(*) as total'))
                                       ->groupBy('id_kecamatan')
                                       ->get(),
            'by_status' => Statistik::select('status', DB::raw('count(*) as total'))
                                    ->groupBy('status')
                                    ->get(),
        ];

        return response()->json($data);
    }
}
