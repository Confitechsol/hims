<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PrefixesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.homeScreen');
})->name('dashboard');

Route::get('/doctors', function () {
    return view('home.doctors');
})->name('doctors');

Route::get('/appointments', [AppointmentsController::class, 'index'])->name('appointments');

Route::get('/userlogin', function () {
    return view('home.userlogin');
})->name('userLogin');

Route::get('/ufpassword', function () {
    return view('home.ufpassword');
})->name('ufPassword');

// Admin Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/store', [ProfileController::class, 'store'])->name('profile.store');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/prefix', [PrefixesController::class, 'index'])->name('prefix');
    Route::post('/prefix/store', [PrefixesController::class, 'store'])->name('prefix.store');
    Route::put('/prefixes/update', [PrefixesController::class, 'update'])->name('prefixes.update');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles');
    Route::post('/roles/store', [RolesController::class, 'store'])->name('roles.store');

    Route::get('/languages', function () {
        return view('admin.setup.languages');
    })->name('languages');

    
    Route::get('/modules', [PermissionController::class, 'modules'])->name('permissions.modules');
    Route::post('/permissions/toggle', [PermissionController::class, 'toggle'])
    ->name('permissions.toggle');
    Route::post('/modules/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/roles/{role}/permissions', [PermissionController::class, 'permissions'])->name('permissions');

    Route::get('/patient', function () {
        return view('admin.setup.patient');
    })->name('patient');
});
