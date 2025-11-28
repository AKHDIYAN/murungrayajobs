<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PekerjaanApiController;
use App\Http\Controllers\Api\LamaranApiController;
use App\Http\Controllers\Api\PerusahaanApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\PelatihanApiController;
use App\Http\Controllers\Api\PelatihanApiControllerEnhanced;
use App\Http\Controllers\Api\StatistikApiController;
use App\Http\Controllers\Api\WorkforceApiController;
use App\Http\Controllers\Api\DSSApiController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication endpoints
Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    // Protected auth endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });
});

// Public API endpoints (read-only, no authentication required)
Route::prefix('v1')->group(function () {
    
    // Pekerjaan/Lowongan endpoints
    Route::prefix('pekerjaan')->group(function () {
        Route::get('/', [PekerjaanApiController::class, 'index']);
        Route::get('/statistics', [PekerjaanApiController::class, 'statistics']);
        Route::get('/{id}', [PekerjaanApiController::class, 'show']);
    });

    // Lamaran endpoints
    Route::prefix('lamaran')->group(function () {
        Route::get('/', [LamaranApiController::class, 'index']);
        Route::get('/statistics', [LamaranApiController::class, 'statistics']);
        Route::get('/{id}', [LamaranApiController::class, 'show']);
    });

    // Perusahaan endpoints
    Route::prefix('perusahaan')->group(function () {
        Route::get('/', [PerusahaanApiController::class, 'index']);
        Route::get('/statistics', [PerusahaanApiController::class, 'statistics']);
        Route::get('/{id}', [PerusahaanApiController::class, 'show']);
    });

    // Users/Pencari Kerja endpoints
    Route::prefix('users')->group(function () {
        Route::get('/', [UserApiController::class, 'index']);
        Route::get('/statistics', [UserApiController::class, 'statistics']);
        Route::get('/{id}', [UserApiController::class, 'show']);
    });

    // Enhanced Pelatihan endpoints
    Route::prefix('pelatihan')->group(function () {
        Route::get('/', [PelatihanApiControllerEnhanced::class, 'index']);
        Route::get('/statistics', [PelatihanApiControllerEnhanced::class, 'statistics']);
        Route::get('/{id}', [PelatihanApiControllerEnhanced::class, 'show']);
        // Training registration requires authentication
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/{id}/register', [PelatihanApiControllerEnhanced::class, 'register']);
        });
        Route::get('/{id}/participants', [PelatihanApiControllerEnhanced::class, 'participants']);
        Route::get('/user/{user_id}', [PelatihanApiControllerEnhanced::class, 'userTrainings']);
        Route::get('/available/{user_id}', [PelatihanApiControllerEnhanced::class, 'availableForUser']);
    });

    // Workforce API endpoints (NEW - HIGH PRIORITY)
    Route::prefix('workforce')->group(function () {
        Route::get('/overview', [WorkforceApiController::class, 'overview']);
        Route::get('/by-kecamatan', [WorkforceApiController::class, 'byKecamatan']);
        Route::get('/by-sector', [WorkforceApiController::class, 'bySector']);
        Route::get('/demographics', [WorkforceApiController::class, 'demographics']);
        Route::get('/search', [WorkforceApiController::class, 'search']);
        Route::get('/skills-analysis', [WorkforceApiController::class, 'skillsAnalysis']);
    });

    // Decision Support System API (NEW - HIGH PRIORITY)
    Route::prefix('dss')->group(function () {
        Route::get('/analytics', [DSSApiController::class, 'analytics']);
        Route::get('/supply-demand', [DSSApiController::class, 'supplyDemand']);
        Route::get('/skills-gap', [DSSApiController::class, 'skillsGap']);
        Route::get('/training-recommendations', [DSSApiController::class, 'trainingRecommendations']);
        Route::get('/job-absorption', [DSSApiController::class, 'jobAbsorption']);
    });

    // Enhanced Statistik endpoints
    Route::prefix('statistik')->group(function () {
        Route::get('/dashboard', [StatistikApiController::class, 'dashboard']);
        Route::get('/kecamatan', [StatistikApiController::class, 'kecamatan']);
        Route::get('/sektor', [StatistikApiController::class, 'sektor']);
        Route::get('/trend', [StatistikApiController::class, 'trend']);
        Route::get('/report', [StatistikApiController::class, 'report']);
    });
});

// Protected API endpoints (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // User specific data
    Route::prefix('my')->group(function () {
        Route::get('/applications', function(Request $request) {
            $user = $request->user();
            if ($user instanceof \App\Models\User) {
                $applications = \App\Models\Lamaran::with(['pekerjaan.perusahaan'])
                    ->where('id_user', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                return response()->json(['success' => true, 'data' => $applications]);
            }
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        });
        
        Route::get('/trainings', function(Request $request) {
            $user = $request->user();
            if ($user instanceof \App\Models\User) {
                $trainings = \App\Models\PelatihanPeserta::with(['pelatihan.sektor'])
                    ->where('id_user', $user->id)
                    ->orderBy('tanggal_daftar', 'desc')
                    ->paginate();
                return response()->json(['success' => true, 'data' => $trainings]);
            }
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        });
    });

    // Company specific endpoints
    Route::prefix('company')->group(function () {
        Route::get('/jobs', function(Request $request) {
            $company = $request->user();
            if ($company instanceof \App\Models\Perusahaan) {
                $jobs = \App\Models\Pekerjaan::with(['kategori', 'kecamatan'])
                    ->where('id_perusahaan', $company->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                return response()->json(['success' => true, 'data' => $jobs]);
            }
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        });
        
        Route::get('/applications', function(Request $request) {
            $company = $request->user();
            if ($company instanceof \App\Models\Perusahaan) {
                $applications = \App\Models\Lamaran::with(['user', 'pekerjaan'])
                    ->whereHas('pekerjaan', function($q) use ($company) {
                        $q->where('id_perusahaan', $company->id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                return response()->json(['success' => true, 'data' => $applications]);
            }
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        });
    });
    
    // Admin endpoints (future implementation)
    Route::prefix('admin')->group(function () {
        // Will add admin-specific endpoints here
        Route::get('/analytics', function() {
            return response()->json(['message' => 'Admin analytics - coming soon']);
        });
    });
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API Murung Raya Job Portal is running',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String()
    ]);
});
