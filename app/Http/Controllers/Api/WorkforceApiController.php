<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Sektor;
use App\Models\Pendidikan;
use App\Models\Usia;
use Illuminate\Support\Facades\DB;

class WorkforceApiController extends Controller
{
    /**
     * Get workforce statistics overview
     * GET /api/v1/workforce/overview
     */
    public function overview()
    {
        try {
            $totalWorkforce = User::count();
        $pencariKerja = User::where('status_kerja', 'Pencari Kerja')->count();
        $bekerja = User::where('status_kerja', 'Bekerja')->count();
        $menganggur = User::where('status_kerja', 'Menganggur')->count();
        
        // Workforce with certification
        $bersertifikat = User::whereNotNull('jenis_sertifikasi')->count();
        $terverifikasi = User::where('sertifikat_verified', true)->count();
        
        // Skills distribution
        $skillsData = User::whereNotNull('skills')
            ->select('skills')
            ->get()
            ->flatMap(function ($user) {
                return array_map('trim', explode(',', $user->skills));
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(10);

            return response()->json([
                'success' => true,
                'message' => 'Workforce overview retrieved successfully',
                'data' => [
                    'total_workforce' => $totalWorkforce,
                    'employment_status' => [
                        'pencari_kerja' => $pencariKerja,
                        'bekerja' => $bekerja,
                        'menganggur' => $menganggur,
                        'unemployment_rate' => $totalWorkforce > 0 ? round(($menganggur / $totalWorkforce) * 100, 2) : 0
                    ],
                    'certification_status' => [
                        'total_certified' => $bersertifikat,
                        'verified' => $terverifikasi,
                        'certification_rate' => $totalWorkforce > 0 ? round(($bersertifikat / $totalWorkforce) * 100, 2) : 0
                    ],
                    'top_skills' => $skillsData
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
     * Get workforce data by kecamatan
     * GET /api/v1/workforce/by-kecamatan
     */
    public function byKecamatan()
    {
        try {
            // Simplified approach using Eloquent relationships
            $workforceByKecamatan = Kecamatan::withCount([
                'users as total_workforce',
                'users as pencari_kerja' => function ($query) {
                    $query->where('status_kerja', 'Pencari Kerja');
                },
                'users as bekerja' => function ($query) {
                    $query->where('status_kerja', 'Bekerja');
                },
                'users as menganggur' => function ($query) {
                    $query->where('status_kerja', 'Menganggur');
                },
                'users as bersertifikat' => function ($query) {
                    $query->whereNotNull('jenis_sertifikasi');
                }
            ])
            ->get()
            ->map(function ($item) {
                $item->unemployment_rate = $item->total_workforce > 0 
                    ? round(($item->menganggur / $item->total_workforce) * 100, 2) 
                    : 0;
                return $item;
            });

            return response()->json([
                'success' => true,
                'message' => 'Workforce data by kecamatan retrieved successfully',
                'data' => $workforceByKecamatan
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
     * Get workforce data by sector
     * GET /api/v1/workforce/by-sector
     */
    public function bySector()
    {
        try {
            // Basic workforce by employment status
            $totalUsers = User::count();
            $pencariKerja = User::where('status_kerja', 'Pencari Kerja')->count();
            $bekerja = User::where('status_kerja', 'Bekerja')->count();
            $menganggur = User::where('status_kerja', 'Menganggur')->count();
            
            $workforceBySector = [
                'total_workforce' => $totalUsers,
                'employment_breakdown' => [
                    'pencari_kerja' => $pencariKerja,
                    'bekerja' => $bekerja,
                    'menganggur' => $menganggur
                ],
                'note' => 'Sector data will be available after sector field is added to user table'
            ];

            return response()->json([
                'success' => true,
                'message' => 'Workforce data by sector retrieved successfully',
                'data' => $workforceBySector
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
     * Get workforce demographics
     * GET /api/v1/workforce/demographics
     */
    public function demographics()
    {
        try {
            // By gender
            $byGender = User::select('jenis_kelamin', DB::raw('COUNT(*) as total'))
                ->groupBy('jenis_kelamin')
                ->get();

            // By education - simplified using relationships
            $byEducation = User::with('pendidikan')
                ->get()
                ->groupBy('pendidikan.tingkatan_pendidikan')
                ->map(function ($group, $key) {
                    return [
                        'tingkatan_pendidikan' => $key,
                        'total' => $group->count()
                    ];
                })
                ->values();

            // By age group - simplified
            $byAge = User::with('usia')
                ->get()
                ->groupBy('usia.kelompok_usia')
                ->map(function ($group, $key) {
                    return [
                        'kelompok_usia' => $key,
                        'total' => $group->count()
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'Workforce demographics retrieved successfully',
                'data' => [
                    'by_gender' => $byGender,
                    'by_education' => $byEducation,
                    'by_age_group' => $byAge
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
     * Get workforce search/filter
     * GET /api/v1/workforce/search
     */
    public function search(Request $request)
    {
        try {
            $query = User::with(['kecamatan', 'pendidikan', 'usia']);

            // Filter by kecamatan
            if ($request->has('kecamatan_id')) {
                $query->where('kecamatan_id', $request->kecamatan_id);
            }

            // Filter by status kerja
            if ($request->has('status_kerja')) {
                $query->where('status_kerja', $request->status_kerja);
            }

            // Filter by education
            if ($request->has('pendidikan_id')) {
                $query->where('id_pendidikan', $request->pendidikan_id);
            }

            // Filter by certification
            if ($request->has('has_certification')) {
                if ($request->has_certification == 'true') {
                    $query->whereNotNull('jenis_sertifikasi');
                } else {
                    $query->whereNull('jenis_sertifikasi');
                }
            }

            // Search by skills
            if ($request->has('skills')) {
                $skills = explode(',', $request->skills);
                foreach ($skills as $skill) {
                    $query->where('skills', 'LIKE', '%' . trim($skill) . '%');
                }
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $workforce = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Workforce search completed successfully',
                'data' => $workforce
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
     * Get workforce skills analysis
     * GET /api/v1/workforce/skills-analysis
     */
    public function skillsAnalysis()
    {
        // Most common skills
        $allSkills = User::whereNotNull('skills')
            ->pluck('skills')
            ->flatMap(function ($skills) {
                return array_map('trim', explode(',', $skills));
            })
            ->filter()
            ->countBy()
            ->sortDesc();

        // Skills by employment status
        $skillsByStatus = User::whereNotNull('skills')
            ->get()
            ->groupBy('status_kerja')
            ->map(function ($users) {
                return $users->flatMap(function ($user) {
                    return array_map('trim', explode(',', $user->skills));
                })->filter()->countBy()->sortDesc()->take(5);
            });

        return response()->json([
            'success' => true,
            'message' => 'Skills analysis completed successfully',
            'data' => [
                'all_skills' => $allSkills->take(20),
                'skills_by_employment_status' => $skillsByStatus
            ]
        ]);
    }
}