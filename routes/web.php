<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\TaskController;
use App\Livewire\ProjectsTable;

// Root route
Route::get('/', function () {
    return view('welcome');
});

// Organization-related routes
Route::middleware(['auth'])->group(function () {
    // Route for listing user organizations
//    Route::get('/my-organizations', [TeamController::class, 'listUserTeams'])->name('organizations.index');

    // Nested routes for organizations
    Route::prefix('{id}/{organization_alias}')->group(function () {
        Route::get('/overview', [TeamController::class, 'overview'])->name('organizations.overview');
        Route::get('/members', [TeamController::class, 'members'])->name('organizations.members');
        Route::get('/create-project', [ProjectController::class, 'create'])->name('organizations.create-project');

        // Nested project routes within organizations
        Route::prefix('projects')->group(function () {
            Route::get('/', [TeamController::class, 'projects'])->name('organizations.projects');
            Route::get('{project_id}', [ProjectController::class, 'show'])->name('organizations.projects.show');
            Route::get('{project_id}/discussion', [ProjectController::class, 'discussion'])->name('organizations.projects.discussion');
            Route::get('{project_id}/tasks', [ProjectController::class, 'tasks'])->name('organizations.projects.tasks');
            Route::get('{project_id}/milestones', [ProjectController::class, 'milestones'])->name('organizations.projects.milestones');
            Route::get('{project_id}/members', [ProjectController::class, 'members'])->name('organizations.projects.members');

        });
    });
});

// Resource routes for projects and tasks (optional for other contexts)
Route::resource('projects', ProjectController::class)->except(['show']); // `show` is overridden
Route::resource('tasks', TaskController::class);

// Personal-related routes
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [PersonalController::class, 'dashboard'])->name('personal.dashboard');
    Route::get('profile', [PersonalController::class, 'profile'])->name('personal.profile');
    Route::get('organizations', [PersonalController::class, 'organizations'])->name('personal.organizations');
    Route::get('organizations/create', [PersonalController::class, 'createOrganization'])->name('personal.organizations.create');
});

// Default authentication routes
//Route::middleware([
//    'auth:sanctum',
//    config('jetstream.auth_session'),
//    'verified',
//])->group(function () {
//    Route::get('/dashboard', function () {
//        return view('dashboard');
//    })->name('dashboard');
//});
