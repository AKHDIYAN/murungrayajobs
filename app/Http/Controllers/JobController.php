<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\Kecamatan;
use App\Models\Sektor;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Job listing page with advanced filtering
     */
    public function index(Request $request)
    {
        $query = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
                          ->where('status', 'Diterima')
                          ->where('tanggal_expired', '>=', now());

        // Get total active jobs for hero section
        $totalJobs = Pekerjaan::where('status', 'Diterima')
                              ->where('tanggal_expired', '>=', now())
                              ->count();

        // Search by keyword (nama pekerjaan, nama perusahaan, deskripsi)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_pekerjaan', 'LIKE', "%{$keyword}%")
                  ->orWhere('nama_perusahaan', 'LIKE', "%{$keyword}%")
                  ->orWhere('deskripsi_pekerjaan', 'LIKE', "%{$keyword}%");
            });
        }

        // Filter by kecamatan (support both ID and slug)
        if ($request->filled('kecamatan')) {
            $kecamatanInput = $request->kecamatan;
            
            // Check if input is numeric (ID) or string (slug)
            if (is_numeric($kecamatanInput)) {
                $query->where('id_kecamatan', $kecamatanInput);
            } else {
                // Convert slug to title case and find kecamatan
                $kecamatanName = str_replace('-', ' ', $kecamatanInput);
                $kecamatanName = ucwords($kecamatanName);
                
                $kecamatan = Kecamatan::where('nama_kecamatan', 'LIKE', "%{$kecamatanName}%")->first();
                if ($kecamatan) {
                    $query->where('id_kecamatan', $kecamatan->id_kecamatan);
                }
            }
        }

        // Filter by sektor
        if ($request->filled('sektor')) {
            $query->where('id_kategori', $request->sektor);
        }

        // Filter by jenis pekerjaan
        if ($request->filled('jenis')) {
            $query->where('jenis_pekerjaan', $request->jenis);
        }

        // Filter by salary range
        if ($request->filled('min_gaji')) {
            $query->where('gaji_max', '>=', $request->min_gaji);
        }
        if ($request->filled('max_gaji')) {
            $query->where('gaji_min', '<=', $request->max_gaji);
        }

        // Sorting
        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'terbaru':
                $query->orderBy('created_at', 'desc');
                break;
            case 'gaji_tertinggi':
                $query->orderBy('gaji_max', 'desc');
                break;
            case 'gaji_terendah':
                $query->orderBy('gaji_min', 'asc');
                break;
            case 'paling_diminati':
                $query->withCount('lamaran')
                      ->orderBy('lamaran_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $jobs = $query->paginate(20)->withQueryString();

        // Load filter data
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        $sektors = Sektor::orderBy('nama_kategori')->get();
        $pendidikans = Pendidikan::orderBy('id_pendidikan')->get();

        return view('jobs.index', compact(
            'jobs',
            'totalJobs',
            'kecamatans',
            'sektors',
            'pendidikans'
        ));
    }

    /**
     * Job detail page
     */
    public function show($id)
    {
        $job = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
                        ->findOrFail($id);

        // Check if job is still active
        if (!$job->is_aktif) {
            return redirect()->route('jobs.index')
                           ->with('error', 'Lowongan ini sudah berakhir atau tidak aktif.');
        }

        // Get related jobs (same category or kecamatan)
        $relatedJobs = Pekerjaan::with(['perusahaan', 'kecamatan'])
                                ->aktif()
                                ->where('id_pekerjaan', '!=', $id)
                                ->where(function($q) use ($job) {
                                    $q->where('id_kategori', $job->id_kategori)
                                      ->orWhere('id_kecamatan', $job->id_kecamatan);
                                })
                                ->limit(4)
                                ->get();

        // Check if user already applied
        $hasApplied = false;
        if (auth()->guard('web')->check()) {
            $hasApplied = \App\Models\Lamaran::where('id_pekerjaan', $id)
                                              ->where('id_user', auth()->guard('web')->id())
                                              ->exists();
        }

        return view('jobs.show', compact('job', 'relatedJobs', 'hasApplied'));
    }

    /**
     * API: Job listing (JSON)
     */
    public function apiIndex(Request $request)
    {
        $query = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
                          ->aktif();

        // Apply filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $jobs = $query->orderBy('tanggal_posting', 'desc')
                     ->paginate(12);

        return response()->json($jobs);
    }

    /**
     * API: Job detail (JSON)
     */
    public function apiShow($id)
    {
        $job = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
                        ->findOrFail($id);

        if (!$job->is_aktif) {
            return response()->json(['error' => 'Job not active'], 404);
        }

        return response()->json($job);
    }
}
