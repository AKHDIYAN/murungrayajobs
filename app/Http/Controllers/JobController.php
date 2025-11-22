<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\Kecamatan;
use App\Models\Sektor;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Job listing page
     */
    public function index(Request $request)
    {
        $query = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
                          ->aktif();

        // Filter by kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter by jenis pekerjaan
        if ($request->filled('jenis')) {
            $query->where('jenis_pekerjaan', $request->jenis);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->orderBy('tanggal_posting', 'desc');
                break;
            case 'oldest':
                $query->orderBy('tanggal_posting', 'asc');
                break;
            case 'salary_high':
                $query->orderBy('gaji_max', 'desc');
                break;
            case 'salary_low':
                $query->orderBy('gaji_min', 'asc');
                break;
        }

        $jobs = $query->paginate(12)->withQueryString();

        // For filters
        $kecamatanList = Kecamatan::all();
        $kategoriList = Sektor::all();

        return view('jobs.index', compact('jobs', 'kecamatanList', 'kategoriList'));
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
