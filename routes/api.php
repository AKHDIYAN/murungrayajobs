<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PekerjaanApiController;
use App\Http\Controllers\Api\LamaranApiController;
use App\Http\Controllers\Api\PerusahaanApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\PelatihanApiController;
use App\Http\Controllers\Api\StatistikApiController;

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

    // Pelatihan endpoints
    Route::prefix('pelatihan')->group(function () {
        Route::get('/', [PelatihanApiController::class, 'index']);
        Route::get('/statistics', [PelatihanApiController::class, 'statistics']);
        Route::get('/{id}', [PelatihanApiController::class, 'show']);
        Route::get('/{id}/peserta', [PelatihanApiController::class, 'peserta']);
    });

    // Statistik endpoints
    Route::prefix('statistik')->group(function () {
        Route::get('/dashboard', [StatistikApiController::class, 'dashboard']);
        Route::get('/kecamatan', [StatistikApiController::class, 'kecamatan']);
        Route::get('/sektor', [StatistikApiController::class, 'sektor']);
        Route::get('/trend', [StatistikApiController::class, 'trend']);
        Route::get('/report', [StatistikApiController::class, 'report']);
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
