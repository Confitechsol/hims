<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PrefixesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\PatientController;

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
    // Route::get('/profile', function () {
    //     return view('admin.setup.profile');
    // })->name('profile');

    Route::get('/email-setting', [EmailController::class,'index'])->name('email-setting');
    Route::post('/email-setting',[EmailController::class,'saveSetting'])->name('email-setting-save');

    Route::get('/database/backup', [DatabaseController::class, 'backup'])->name('database.backup');
    Route::post('/database/restore', [DatabaseController::class, 'restore'])->name('database.restore');

    Route::get('/database/backups', [DatabaseController::class, 'listBackups'])->name('database.backups');
Route::get('/database/backups/download/{filename}', [DatabaseController::class, 'download'])->name('database.download');
Route::delete('/database/backups/delete/{filename}', [DatabaseController::class, 'delete'])->name('database.delete');
// Route::post('/database/restore', [DatabaseController::class, 'restore'])->name('database.restore');
// Route::get('/database/backup', [DatabaseController::class, 'backup'])->name('database.backup'); // optional link
Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/store', [ProfileController::class, 'store'])->name('profile.store');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/prefix', [PrefixesController::class, 'index'])->name('prefix');
    Route::post('/prefix/store', [PrefixesController::class, 'store'])->name('prefix.store');
    Route::put('/prefixes/update', [PrefixesController::class, 'update'])->name('prefixes.update');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles');
    Route::post('/roles/store', [RolesController::class, 'store'])->name('roles.store');
    Route::get('/roles/edit', [RolesController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/destroy', [RolesController::class, 'destroy'])->name('roles.destroy');
    Route::get('/languages', function () {
        return view('admin.setup.languages');
    })->name('languages');

    
    Route::get('/modules', [PermissionController::class, 'modules'])->name('permissions.modules');
    Route::post('/permissions/toggle', [PermissionController::class, 'toggle'])
    ->name('permissions.toggle');
    Route::post('/modules/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/roles/{role}/permissions', [PermissionController::class, 'permissions'])->name('permissions');
Route::post('/roles/permissions/save', [PermissionController::class, 'savePermissions'])->name('roles.permissions.save');

    Route::get('/patients', function () {
        return view('admin.setup.patient');
    })->name('patients');
    Route::get('/charges', function () {
        return view('admin.setup.charges');
    })->name('charges');
    Route::get('/import', function () {
        return view('admin.setup.import_patient');
    })->name('import');
});
