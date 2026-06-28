<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatasetController;
use App\Models\Dataset;
use App\Models\Flora;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Guest / Public Routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/map', function () {
    return view('public.map');
})->name('map');

Route::get('/api/vulnerability-data', [DatasetController::class, 'getVulnerabilityData'])->name('api.vulnerability_data');
Route::get('/api/regions/{region_id}/details', [DatasetController::class, 'getRegionDetails'])->name('api.region_details');


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

    // Shared "My Account" settings page
    Route::get('/account', [AuthController::class, 'showAccount'])->name('account');
    Route::post('/account', [AuthController::class, 'updateAccount'])->name('account.update');

    // General Public Area
    Route::middleware('role:GENERAL_PUBLIC,RESEARCHER,SYSTEM_ADMINISTRATOR')->group(function () {
        Route::get('/public/dashboard', function () {
            $user = auth()->user();
            $myObservations = \App\Models\ObservationReport::where('public_id', $user->user_id)->latest()->get();
            $myObservationsCount = $myObservations->count();
            $registeredFlora = \App\Models\Flora::select('flora_id', 'scientific_name', 'common_name')->get();
            return view('public.dashboard', compact('myObservations', 'myObservationsCount', 'registeredFlora'));
        })->name('public.dashboard');

        // Search registry
        Route::get('/public/search', [DatasetController::class, 'publicSearch'])->name('public.search');

        // Submit observation report
        Route::get('/public/observations/submit', [DatasetController::class, 'showSubmitObservation'])->name('public.observations.create');
        Route::post('/public/observations/submit', [DatasetController::class, 'submitObservation'])->name('public.observations.submit');
        Route::post('/public/observations/{observation_id}/delete', [DatasetController::class, 'deleteObservation'])->name('public.observations.delete');
    });

    // Researcher Area
    Route::middleware('role:RESEARCHER,SYSTEM_ADMINISTRATOR')->group(function () {
        Route::get('/researcher/dashboard', function () {
            $datasetsCount = \App\Models\Dataset::where('upload_status', 'Validated')->count();
            $climateCount = \App\Models\ClimateData::count();
            $vegCount = \App\Models\VegetationData::count();
            $floraCount = \App\Models\Flora::count();
            $assessmentsCount = \App\Models\VulnerabilityAssessment::count();
            $observations = \App\Models\ObservationReport::with(['observer', 'reviewer'])->latest()->get();

            return view('researcher.dashboard', compact('datasetsCount', 'climateCount', 'vegCount', 'floraCount', 'assessmentsCount', 'observations'));
        })->name('researcher.dashboard');

        // Dynamic details and reviews for observation reports
        Route::get('/researcher/observations/{observation_id}/details', [DatasetController::class, 'getObservationDetails'])->name('researcher.observations.details');
        Route::post('/researcher/observations/{observation_id}/review', [DatasetController::class, 'reviewObservation'])->name('researcher.observations.review');

        // Climate Dataset Ingestion
        Route::get('/researcher/datasets/climate/upload', [DatasetController::class, 'showUploadClimate'])->name('researcher.datasets.climate.upload');
        Route::post('/researcher/datasets/climate/upload', [DatasetController::class, 'uploadClimate'])->name('researcher.datasets.climate.upload.submit');

        // Vegetation Dataset Ingestion
        Route::get('/researcher/datasets/vegetation/upload', [DatasetController::class, 'showUploadVegetation'])->name('researcher.datasets.vegetation.upload');
        Route::post('/researcher/datasets/vegetation/upload', [DatasetController::class, 'uploadVegetation'])->name('researcher.datasets.vegetation.upload.submit');

        // Flora Registry & Dataset Ingestion
        Route::get('/researcher/flora/manage', [DatasetController::class, 'showManageFlora'])->name('researcher.flora.manage');
        Route::post('/researcher/datasets/flora/upload', [DatasetController::class, 'uploadFlora'])->name('researcher.datasets.flora.upload.submit');
        Route::post('/researcher/flora/new', [DatasetController::class, 'createFlora'])->name('researcher.flora.store');


        // Analysis console
        Route::get('/researcher/analysis', [DatasetController::class, 'showAnalysis'])->name('researcher.analysis');
        Route::post('/researcher/analysis', [DatasetController::class, 'runAnalysis'])->name('researcher.analysis.submit');

        //delete all uploaded datasets
        Route::post('/researcher/datasets/delete', [DatasetController::class, 'deleteAllUploads'])->name('researcher.deleteUploads');
    });

    // System Administrator Area
    Route::middleware('role:SYSTEM_ADMINISTRATOR')->group(function () {
        Route::get('/admin/dashboard', function () {
            $users = \App\Models\User::with('role')->get();
            $totalUsers = $users->count();
            $activeObservers = \App\Models\User::where('role_id', 1)->where('account_status', 'Active')->count();
            $thresholdCount = \App\Models\VulnerabilityThreshold::count();
            $backupsCount = 12;

            return view('admin.dashboard', compact('users', 'totalUsers', 'activeObservers', 'thresholdCount', 'backupsCount'));
        })->name('admin.dashboard');

        // User status manager (approve, reject, suspend, activate)
        Route::post('/admin/users/{user_id}/status', [AuthController::class, 'updateUserStatus'])->name('admin.users.status');

        // User details editor
        Route::get('/admin/users/{user_id}/edit', [AuthController::class, 'editUser'])->name('admin.users.edit');
        Route::post('/admin/users/{user_id}/update', [AuthController::class, 'updateUser'])->name('admin.users.update');
    });
});
