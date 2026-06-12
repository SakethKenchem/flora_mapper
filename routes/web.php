<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatasetController;
use Illuminate\Support\Facades\Route;

// Guest / Public Routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/map', function () {
    return view('public.map');
})->name('map');

// Public API for map data loading
Route::get('/api/vulnerability-data', [DatasetController::class, 'getVulnerabilityData'])->name('api.vulnerability_data');

// Authentication routes (Guest only)
Route::middleware('guest')->group(function () {
    // General Public Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Researcher Register
    Route::get('/register/researcher', [AuthController::class, 'showRegisterResearcher'])->name('register.researcher');
    Route::post('/register/researcher', [AuthController::class, 'registerResearcher'])->name('register.researcher.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Logout route (Authenticated only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Role-protected Routes
Route::middleware('auth')->group(function () {
    
    // General Public Area
    Route::middleware('role:GENERAL_PUBLIC,RESEARCHER,SYSTEM_ADMINISTRATOR')->group(function () {
        Route::get('/public/dashboard', function () {
            return view('public.dashboard');
        })->name('public.dashboard');
    });

    // Researcher Area
    Route::middleware('role:RESEARCHER,SYSTEM_ADMINISTRATOR')->group(function () {
        Route::get('/researcher/dashboard', function () {
            // Count total datasets
            $datasetsCount = \App\Models\Dataset::where('upload_status', 'Validated')->count();
            // Count total climate records
            $climateCount = \App\Models\ClimateData::count();
            // Count total vegetation records
            $vegCount = \App\Models\VegetationData::count();
            // Count assessments
            $assessmentsCount = \App\Models\VulnerabilityAssessment::count();

            return view('researcher.dashboard', compact('datasetsCount', 'climateCount', 'vegCount', 'assessmentsCount'));
        })->name('researcher.dashboard');

        // Climate Dataset Ingestion
        Route::get('/researcher/datasets/climate/upload', [DatasetController::class, 'showUploadClimate'])->name('researcher.datasets.climate.upload');
        Route::post('/researcher/datasets/climate/upload', [DatasetController::class, 'uploadClimate'])->name('researcher.datasets.climate.upload.submit');

        // Vegetation Dataset Ingestion
        Route::get('/researcher/datasets/vegetation/upload', [DatasetController::class, 'showUploadVegetation'])->name('researcher.datasets.vegetation.upload');
        Route::post('/researcher/datasets/vegetation/upload', [DatasetController::class, 'uploadVegetation'])->name('researcher.datasets.vegetation.upload.submit');

        // Analysis console
        Route::get('/researcher/analysis', [DatasetController::class, 'showAnalysis'])->name('researcher.analysis');
        Route::post('/researcher/analysis', [DatasetController::class, 'runAnalysis'])->name('researcher.analysis.submit');
    });

    // System Administrator Area
    Route::middleware('role:SYSTEM_ADMINISTRATOR')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
});
