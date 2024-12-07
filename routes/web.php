<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeamController;

use App\Http\Controllers\ProjectController;

Route::resource('projects', ProjectController::class);


Route::get('/', function () {return view('welcome');});

Route::get('/organizations', [TeamController::class, 'listUserTeams'])->name('teams.index');
//Route::get('organization/{id}', [TeamController::class, 'showTeam'])->name('team.show');

Route::middleware(['auth'])->group(function () {
    Route::prefix('organizations/{team}')->group(function () {
        Route::get('dashboard', [TeamController::class, 'dashboard'])->name('organizations.dashboard');
        Route::get('projects', [TeamController::class, 'projects'])->name('organizations.projects');
        Route::get('members', [TeamController::class, 'members'])->name('organizations.members');
        // Add more sections as needed
    });
});

Route::get('/api/projects', [ProjectController::class, 'getProjects'])->name('projects.api');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
