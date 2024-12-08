<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\TaskController;

// Resource routes
Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);

// Organization-related routes
Route::get('/my-organizations', [TeamController::class, 'listUserTeams'])->name('organizations.index');

Route::middleware(['auth'])->group(function () {
    // Nested routes for organizations
    Route::prefix('organizations/{team}')->group(function () {
        Route::get('overview', [TeamController::class, 'overview'])->name('organizations.overview');
        Route::get('projects', [TeamController::class, 'projects'])->name('organizations.projects');
        Route::get('members', [TeamController::class, 'members'])->name('organizations.members');
    });

    // Nested routes for projects
    Route::prefix('projects/{project}')->group(function () {
        Route::get('discussion', [ProjectController::class, 'discussion'])->name('projects.discussion');
        Route::get('tasks', [ProjectController::class, 'tasks'])->name('projects.tasks');
        Route::get('milestones', [ProjectController::class, 'milestones'])->name('projects.milestones');
        Route::get('members', [ProjectController::class, 'members'])->name('projects.members');
    });

    // Personal-related routes
    Route::prefix('personal/')->group(function () {
        Route::get('dashboard', [PersonalController::class, 'dashboard'])->name('personal.dashboard');
        Route::get('projects', [PersonalController::class, 'projects'])->name('personal.projects');
    });
});

// API routes
Route::get('/api/projects', [ProjectController::class, 'getProjects'])->name('projects.api');
Route::get('/api/tasks', [TaskController::class, 'getTasks'])->name('tasks.api');

// Default authentication routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
