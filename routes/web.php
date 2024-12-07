<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeamController;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PersonalController;

Route::resource('projects', ProjectController::class);


Route::get('/', function () {return view('welcome');});

Route::get('/my-organizations', [TeamController::class, 'listUserTeams'])->name('organizations.index');
//Route::get('organization/{id}', [TeamController::class, 'showTeam'])->name('team.show');

Route::middleware(['auth'])->group(function () {
    Route::prefix('organizations/{team}')->group(function () {
        Route::get('dashboard', [TeamController::class, 'dashboard'])->name('organizations.dashboard');
        Route::get('projects', [TeamController::class, 'projects'])->name('organizations.projects');
        Route::get('members', [TeamController::class, 'members'])->name('organizations.members');
        // Add more sections as needed
    });
    Route::prefix('projects/{project}')->group(function () {
        Route::get('', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('discussion', [ProjectController::class, 'discussion'])->name('projects.discussion');
        Route::get('tasks', [ProjectController::class, 'tasks'])->name('projects.tasks');
        Route::get('milestones', [ProjectController::class, 'milestones'])->name('projects.milestones');
        Route::get('members', [ProjectController::class, 'members'])->name('projects.members');
        // Add more sections as needed
    });
    Route::prefix('personal/')->group(function () {
        Route::get('dashboard', [PersonalController::class, 'dashboard'])->name('personal.dashboard');
        Route::get('projects', [PersonalController::class, 'projects'])->name('personal.projects');
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
