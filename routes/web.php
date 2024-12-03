<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {return view('welcome');});
Route::resource('organizations', \App\Http\Controllers\OrganizationController::class);
Route::get('my-organizations', [\App\Http\Controllers\OrganizationController::class, 'ownedOrganizations'])->name('organizations.owned');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
