<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\LabelController;
use App\Livewire\FileUpload;

// Root route
Route::get('/', function () {
    return view('welcome');
});

// Organization-related routes
Route::middleware(['auth'])->group(function () {
    // Route for listing user organizations
//    Route::get('/my-organizations', [TeamController::class, 'listUserTeams'])->name('organizations.index');
    Route::post('/upload-file-endpoint', [FileUpload::class, 'handleFileDrop'])->name('fileupload.drop');

    // Nested routes for organizations
    Route::prefix('{id}/{organization_alias}')->group(function () {
        // General Organization Routes
        Route::get('/overview', [TeamController::class, 'overview'])->name('organizations.overview');
        Route::get('/members', [TeamController::class, 'members'])->name('organizations.members');
        Route::get('/create-project', [ProjectController::class, 'create'])->name('organizations.create-project');
        Route::get('/members-management', [TeamController::class, 'membersManagement'])->name('organizations.members.management');
        Route::get('/settings', [TeamController::class, 'settings'])->name('organizations.settings');
        Route::get('/task-labels', [TeamController::class, 'taskLabels'])->name('organizations.task-labels');

        // Tasks Routes
        Route::prefix('tasks')->group(function () {
            Route::get('/', [TeamController::class, 'tasks'])->name('organizations.tasks');
            Route::get('/{task_id}', [TaskController::class, 'show'])->name('organizations.tasks.show');
        });
        // Board Routes
        Route::prefix('board')->group(function () {
            Route::get('/main', [TeamController::class, 'board'])->name('organizations.board');
        });


        // Projects Routes
        Route::prefix('projects')->group(function () {
            Route::get('/', [TeamController::class, 'projects'])->name('organizations.projects'); // List all projects

            Route::get('{project_id}/{tab?}', [ProjectController::class, 'show'])
                ->where('tab', 'overview|discussion|files|tasks|milestones|members|settings|board') // Limit valid tabs
                ->name('organizations.projects.show');

            Route::resource('milestones', MilestoneController::class)->except(['show']);
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
