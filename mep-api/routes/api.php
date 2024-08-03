<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\CompetenceController;
use App\Http\Controllers\RequirementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/version', [AppController::class, 'version'])->name('version');

// Temporary migration endpoints

Route::get('/migration/reset', [AppController::class, 'reset'])->name('migrate.reset');
Route::get('/migration/competences', [AppController::class, 'migrateCompetences'])->name('migrate.competences');
Route::get('/migration/clusters', [AppController::class, 'migrateClusters'])->name('migrate.clusters');
Route::get('/migration/profiles', [AppController::class, 'migrateProfiles'])->name('migrate.profiles');
Route::get('/migration/requirements', [AppController::class, 'migrateRequirements'])->name('migrate.requirements');


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::get('/', [AuthController::class, 'auth'])->name('login');
    Route::get('/callback', [AuthController::class, 'callback'])->name('callback');
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

// Login via Keycloak. Do not change.
//Route::get('login', 'App\Http\Controllers\UserController@redirect')->name('login');
//Route::get('/auth/callback', 'App\Http\Controllers\UserController@callback');


// Cluster routes
Route::prefix('clusters')->group(function () {
    Route::get('/', [ClusterController::class, 'index'])->name('clusters');
    Route::post('/', [ClusterController::class, 'createOrUpdateCluster'])->name('clusters.update');
    Route::delete('/', [ClusterController::class, 'deleteCluster'])->name('clusters.delete');
    Route::get('/profiles', [ClusterController::class, 'loadProfileList'])->name('clusters.profiles.list');
    Route::post('/profiles', [ClusterController::class, 'createOrUpdateLoadProfile'])->name('clusters.profiles.update');
    Route::delete('/profiles', [ClusterController::class, 'deleteLoadProfile'])->name('clusters.profiles.delete');
    Route::delete('/profiles/changes', [ClusterController::class, 'deleteProfileChange'])->name('clusters.profiles.change.delete');
    // Detail routes, must be always last rows!
    Route::get('/{clusterId}/profiles', [ClusterController::class, 'loadProfiles'])->name('clusters.profiles');
    Route::get('/{clusterId}/profiles/{profileId}', [ClusterController::class, 'loadProfile'])->name('clusters.profile');
    Route::get('/{clusterId}/profiles/{profileId}/chart', [ClusterController::class, 'chartData'])->name('clusters.profiles.chart');
    Route::post('/{clusterId}/profiles/{profileId}/changes', [ClusterController::class, 'createOrUpdateProfileChange'])->name('clusters.profiles.change');
    Route::get('/{clusterId}', [ClusterController::class, 'clusterDetail'])->name('cluster');
});


// Competences
Route::prefix('competences')->group(function () {
    Route::get('/', [CompetenceController::class, 'index'])->name('competences');
    Route::post('/', [CompetenceController::class, 'createOrUpdateCompetence'])->name('competences.update');
    Route::delete('/', [CompetenceController::class, 'deleteCompetence'])->name('competences.delete');
    Route::put('/profiles', [CompetenceController::class, 'addCompetenceToLoadProfile'])->name('competences.profiles.add');
    Route::delete('/profiles', [CompetenceController::class, 'removeCompetenceFromLoadProfile'])->name('competences.profiles.remove');
    // Detail route, must be always last row!
    Route::get('/{competenceId}', [CompetenceController::class, 'getCompetenceById'])->name('competence');
});

// Requirements
Route::prefix('requirements')->group(function () {
    Route::get('/', [RequirementController::class, 'index'])->name('requirements');
    Route::post('/', [RequirementController::class, 'createOrUpdateRequirement'])->name('requirements.update');
    Route::post('/state', [RequirementController::class, 'changeRequirementState'])->name('requirements.state.change');
    Route::delete('/', [RequirementController::class, 'deleteRequirement'])->name('requirements.delete');
    Route::post('/phase', [RequirementController::class, 'createOrUpdatePhase'])->name('requirements.phase.update');
    Route::delete('/phase', [RequirementController::class, 'deletePhase'])->name('requirements.phase.delete');
    Route::post('/phase/participation', [RequirementController::class, 'createOrUpdateParticipation'])->name('requirements.phase.participation.update');
    Route::delete('/phase/participation', [RequirementController::class, 'deleteParticipation'])->name('requirements.phase.participation.delete');
    Route::get('/dropdown-data', [RequirementController::class, 'dropdownData'])->name('requirements.dropdown');
    // Detail routes, must be always last rows!
    Route::get('/phase/participation/{participationId}', [RequirementController::class, 'getParticipationById'])->name('requirements.phases.participation');
    Route::get('/phase/{phaseId}', [RequirementController::class, 'phaseDetail'])->name('requirements.phases');
    Route::get('/{requirementId}', [RequirementController::class, 'requirementDetail'])->name('requirement');
});



