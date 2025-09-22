<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardStatController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Maintenance\CategoryController;
use App\Http\Controllers\Micro\ApplicationController as MicroApplicationController;
use App\Http\Controllers\Micro\IncidenceController;
use App\Http\Controllers\StaffController;

Route::middleware('auth')->group(function () {

    Route::get('/api/stats/incidences',             [DashboardStatController::class, 'incidences']);
    Route::get('/api/stats/types',                  [DashboardStatController::class, 'countTypes']);
    Route::get('/api/stats/categories',             [DashboardStatController::class, 'countCategories']);
    Route::get('/api/stats/months',                 [DashboardStatController::class, 'incidencesByMonth']);
    Route::get('/api/stats/levels',                 [DashboardStatController::class, 'incidencesByLevels']);
    Route::get('/api/stats/category',               [DashboardStatController::class, 'incidenceByCategories']);
    Route::get('/api/stats/usersdrill',             [DashboardStatController::class, 'incidencesbyUsers']);
    Route::get('/api/stats/areadrill',              [DashboardStatController::class, 'incidencebyAreas']);

    Route::get('/api/users',                        [UserController::class, 'index']);
    Route::post('/api/users',                       [UserController::class, 'store']);
    Route::patch('/api/users/{user}/change-role',   [UserController::class, 'changeRole']);
    Route::put('/api/users/{user}',                 [UserController::class, 'update']);
    Route::delete('/api/users/{user}',              [UserController::class, 'destroy']);
    Route::delete('/api/users',                     [UserController::class, 'bulkDelete']);
    
    Route::get('/api/clients',                      [ClientController::class, 'index']);

    Route::get('/api/settings',                     [SettingController::class, 'index']);
    Route::post('/api/settings',                    [SettingController::class, 'update']);

    Route::get('/api/profile',                      [ProfileController::class, 'index']);
    Route::put('/api/profile',                      [ProfileController::class, 'update']);
    Route::post('/api/upload-image',                [ProfileController::class, 'uploadImage']);
    Route::post('/api/change-user-password',        [ProfileController::class, 'changePassword']);

    Route::get('/api/categories',                   [CategoryController::class, 'index']);
    Route::post('/api/categories',                  [CategoryController::class, 'store']);
    Route::get('/api/categories/{category}/edit',   [CategoryController::class, 'edit']);
    Route::put('/api/categories/{category}',        [CategoryController::class, 'update']);
    Route::delete('/api/categories/{category}',     [CategoryController::class, 'destroy']);

    Route::get('/api/applications',                     [MicroApplicationController::class, 'index']);
    Route::post('/api/applications',                    [MicroApplicationController::class, 'store']);
    Route::get('/api/applications/{application}/edit',  [MicroApplicationController::class, 'edit']);
    Route::put('/api/applications/{application}',       [MicroApplicationController::class, 'update']);
    Route::get('/api/applications/getApps',             [MicroApplicationController::class, 'getApps']);
    Route::delete('/api/applications/{application}',    [MicroApplicationController::class, 'destroy']);

    Route::get('/api/incidences',                   [IncidenceController::class, 'index']);
    Route::post('/api/incidences/create',           [IncidenceController::class, 'store']);
    Route::get('/api/incidences/getStaff',          [IncidenceController::class, 'getStaff']);
    Route::get('/api/incidences/{incidence}/edit',  [IncidenceController::class, 'edit']);
    Route::put('/api/incidences/{incidence}/edit',  [IncidenceController::class, 'update']);
    Route::delete('/api/incidences/{id}',           [IncidenceController::class, 'destroy']);
    Route::get('/api/incidence-category',           [IncidenceController::class, 'getIncidenceCategory']);
    Route::get('/api/incidence-type',               [IncidenceController::class, 'getIncidenceType']);

    Route::get('/api/staff',                        [StaffController::class, 'index']);
    Route::post('/api/staff/create',                [StaffController::class, 'store']);
    Route::get('/api/staff/search',                 [StaffController::class, 'search']);
    Route::get('/api/staff/{staff}/edit',           [StaffController::class, 'edit']);
    Route::put('/api/staff/{staff}/edit',           [StaffController::class, 'update']);
    Route::delete('/api/staff/{staff}',             [StaffController::class, 'destroy']);
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');