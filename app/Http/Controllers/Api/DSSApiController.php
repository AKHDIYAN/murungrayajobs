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

class DSSApiController extends Controller
{
    /**
     * Get Decision Support System analytics
     * GET /api/v1/dss/analytics
     */
    public function analytics()
    {
        try {
            // Simplified analytics for now
            $basicStats = [
                'total_users' => User::count(),
                'total_companies' => Perusahaan::count(),
                'total_jobs' => Pekerjaan::count(),
                'total_applications' => Lamaran::count(),
                'total_trainings' => Pelatihan::count()
            ];

            return response()->json([
                'success' => true,
                'message' => 'DSS analytics retrieved successfully',
                'data' => [
                    'basic_stats' => $basicStats,
                    'generated_at' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get supply vs demand analysis by sector
     * GET /api/v1/dss/supply-demand
     */
    public function supplyDemand()
    {
        $analysis = $this->getSupplyDemandAnalysis();

        return response()->json([
            'success' => true,
            'message' => 'Supply demand analysis retrieved successfully',
            'data' => $analysis
        ]);
    }

    /**
     * Get skills gap analysis
     * GET /api/v1/dss/skills-gap
     */
    public function skillsGap()
    {
        $analysis = $this->getSkillsGapAnalysis();

        return response()->json([
            'success' => true,
            'message' => 'Skills gap analysis retrieved successfully',
            'data' => $analysis
        ]);
    }

    /**
     * Get training recommendations
     * GET /api/v1/dss/training-recommendations
     */
    public function trainingRecommendations(Request $request)
    {
        $kecamatanId = $request->get('kecamatan_id');
        $sektorId = $request->get('sektor_id');

        // Analyze skills gaps
        $skillsGap = collect($this->getSkillsGapAnalysis());
        
        // Get available training programs
        $availableTrainings = Pelatihan::where('status', 'Dibuka')
            ->where('tanggal_mulai', '>', now())
            ->with(['sektor'])
            ->get();

        // Match training to gaps
        $recommendations = $skillsGap->map(function ($gap) use ($availableTrainings, $kecamatanId, $sektorId) {
            // Filter by region and sector if provided
            $relevantTrainings = $availableTrainings;
            
            if ($sektorId) {
                $relevantTrainings = $relevantTrainings->where('id_sektor', $sektorId);
            }

            $gap->recommended_trainings = $relevantTrainings->take(3);
            $gap->urgency_score = $this->calculateUrgencyScore($gap);
            
            return $gap;
        })->sortByDesc('urgency_score');

        return response()->json([
            'success' => true,
            'message' => 'Training recommendations generated successfully',
            'data' => [
                'recommendations' => $recommendations->values(),
                'filters_applied' => [
                    'kecamatan_id' => $kecamatanId,
                    'sektor_id' => $sektorId
                ]
            ]
        ]);
    }

    /**
     * Get job absorption rate analysis
     * GET /api/v1/dss/job-absorption
     */
    public function jobAbsorption()
    {
        $totalLowongan = Pekerjaan::where('status', 'Aktif')->sum('jumlah_lowongan');
        $totalPelamar = User::where('status_kerja', 'Pencari Kerja')->count();
        $totalLamaran = Lamaran::count();
        $totalDiterima = Lamaran::where('status', 'Diterima')->count();

        // Calculate rates
        $applicationRate = $totalPelamar > 0 ? round(($totalLamaran / $totalPelamar) * 100, 2) : 0;
        $acceptanceRate = $totalLamaran > 0 ? round(($totalDiterima / $totalLamaran) * 100, 2) : 0;
        $jobFulfillmentRate = $totalLowongan > 0 ? round(($totalDiterima / $totalLowongan) * 100, 2) : 0;

        // By sector analysis
        $sectorAnalysis = Sektor::leftJoin('pekerjaan', 'sektor.id_sektor', '=', 'pekerjaan.id_kategori')
            ->leftJoin('lamaran', 'pekerjaan.id', '=', 'lamaran.id_pekerjaan')
            ->select([
                'sektor.nama_kategori',
                DB::raw('SUM(pekerjaan.jumlah_lowongan) as total_lowongan'),
                DB::raw('COUNT(lamaran.id) as total_lamaran'),
                DB::raw('SUM(CASE WHEN lamaran.status = "Diterima" THEN 1 ELSE 0 END) as total_diterima')
            ])
            ->groupBy('sektor.id_sektor', 'sektor.nama_kategori')
            ->get()
            ->map(function ($item) {
                $item->absorption_rate = $item->total_lowongan > 0 
                    ? round(($item->total_diterima / $item->total_lowongan) * 100, 2) 
                    : 0;
                return $item;
            });

        return response()->json([
            'success' => true,
            'message' => 'Job absorption analysis retrieved successfully',
            'data' => [
                'overall_metrics' => [
                    'total_lowongan' => $totalLowongan,
                    'total_pelamar' => $totalPelamar,
                    'total_lamaran' => $totalLamaran,
                    'total_diterima' => $totalDiterima,
                    'application_rate' => $applicationRate,
                    'acceptance_rate' => $acceptanceRate,
                    'job_fulfillment_rate' => $jobFulfillmentRate
                ],
                'by_sector' => $sectorAnalysis
            ]
        ]);
    }

    /**
     * Private helper methods
     */
    private function getSupplyDemandAnalysis()
    {
        return Sektor::leftJoin('pekerjaan', 'sektor.id_sektor', '=', 'pekerjaan.id_kategori')
            ->leftJoin('user', 'sektor.id_sektor', '=', 'user.id_sektor')
            ->select([
                'sektor.nama_kategori',
                DB::raw('SUM(CASE WHEN pekerjaan.status = "Aktif" THEN pekerjaan.jumlah_lowongan ELSE 0 END) as demand_lowongan'),
                DB::raw('COUNT(DISTINCT pekerjaan.id) as demand_total'),
                DB::raw('COUNT(DISTINCT CASE WHEN user.status_kerja = "Pencari Kerja" THEN user.id END) as supply_pencari_kerja'),
                DB::raw('COUNT(DISTINCT user.id) as supply_total')
            ])
            ->groupBy('sektor.id_sektor', 'sektor.nama_kategori')
            ->get()
            ->map(function ($item) {
                $item->gap = $item->demand_lowongan - $item->supply_pencari_kerja;
                $item->gap_percentage = $item->demand_lowongan > 0 
                    ? round(($item->gap / $item->demand_lowongan) * 100, 2) 
                    : 0;
                $item->status = $item->gap > 0 ? 'Shortage' : ($item->gap < 0 ? 'Surplus' : 'Balanced');
                return $item;
            });
    }

    private function getSkillsGapAnalysis()
    {
        // Get required skills from job postings
        $jobSkills = Pekerjaan::where('status', 'Aktif')
            ->whereNotNull('persyaratan_pekerjaan')
            ->pluck('persyaratan_pekerjaan')
            ->flatMap(function ($requirements) {
                // Extract potential skills from requirements
                return preg_split('/[,;\n]/', $requirements);
            })
            ->map(function ($skill) {
                return trim(strtolower($skill));
            })
            ->filter()
            ->countBy();

        // Get available skills from users
        $userSkills = User::whereNotNull('skills')
            ->pluck('skills')
            ->flatMap(function ($skills) {
                return array_map('trim', array_map('strtolower', explode(',', $skills)));
            })
            ->filter()
            ->countBy();

        // Calculate gaps
        $allSkills = $jobSkills->keys()->merge($userSkills->keys())->unique();
        
        return $allSkills->map(function ($skill) use ($jobSkills, $userSkills) {
            $demand = $jobSkills->get($skill, 0);
            $supply = $userSkills->get($skill, 0);
            
            return [
                'skill' => $skill,
                'demand' => $demand,
                'supply' => $supply,
                'gap' => $demand - $supply,
                'gap_percentage' => $demand > 0 ? round((($demand - $supply) / $demand) * 100, 2) : 0
            ];
        })->sortByDesc('gap')->values();
    }

    private function getEmploymentTrends()
    {
        // Last 12 months employment trends
        $trends = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trends->push([
                'month' => $date->format('Y-m'),
                'month_name' => $date->format('F Y'),
                'new_users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'new_jobs' => Pekerjaan::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'applications' => Lamaran::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count()
            ]);
        }

        return $trends;
    }

    private function getTrainingEffectiveness()
    {
        $completedTrainings = PelatihanPeserta::where('status_kehadiran', 'Hadir')
            ->with(['user', 'pelatihan'])
            ->get();

        $effectiveness = $completedTrainings->groupBy('pelatihan.nama_pelatihan')
            ->map(function ($participants, $trainingName) {
                $beforeEmployed = $participants->filter(function ($p) {
                    return $p->user->status_kerja === 'Bekerja';
                })->count();
                
                $total = $participants->count();
                
                return [
                    'training_name' => $trainingName,
                    'total_participants' => $total,
                    'employed_after' => $beforeEmployed,
                    'employment_rate' => $total > 0 ? round(($beforeEmployed / $total) * 100, 2) : 0
                ];
            })->values();

        return $effectiveness;
    }

    private function getRegionalAnalysis()
    {
        return Kecamatan::leftJoin('user', 'kecamatan.id_kecamatan', '=', 'user.kecamatan_id')
            ->leftJoin('pekerjaan', 'kecamatan.id_kecamatan', '=', 'pekerjaan.id_kecamatan')
            ->select([
                'kecamatan.nama_kecamatan',
                DB::raw('COUNT(DISTINCT user.id) as total_workforce'),
                DB::raw('COUNT(DISTINCT CASE WHEN user.status_kerja = "Menganggur" THEN user.id END) as unemployed'),
                DB::raw('COUNT(DISTINCT CASE WHEN pekerjaan.status = "Aktif" THEN pekerjaan.id END) as active_jobs'),
                DB::raw('SUM(CASE WHEN pekerjaan.status = "Aktif" THEN pekerjaan.jumlah_lowongan ELSE 0 END) as total_vacancies')
            ])
            ->groupBy('kecamatan.id_kecamatan', 'kecamatan.nama_kecamatan')
            ->get()
            ->map(function ($item) {
                $item->unemployment_rate = $item->total_workforce > 0 
                    ? round(($item->unemployed / $item->total_workforce) * 100, 2) 
                    : 0;
                $item->job_availability_ratio = $item->total_workforce > 0 
                    ? round($item->total_vacancies / $item->total_workforce, 2) 
                    : 0;
                return $item;
            });
    }

    private function calculateUrgencyScore($gap)
    {
        $score = 0;
        
        // Higher gap = higher urgency
        if (isset($gap['gap']) && $gap['gap'] > 0) {
            $score += min($gap['gap'] * 2, 50);
        }
        
        // Higher gap percentage = higher urgency
        if (isset($gap['gap_percentage'])) {
            $score += min($gap['gap_percentage'], 50);
        }
        
        return $score;
    }
}