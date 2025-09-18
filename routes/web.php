<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Setup\LanguagesController;
use App\Http\Controllers\Setup\PrefixesController;
use App\Http\Controllers\Setup\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Setup\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontOfficeController;

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

    Route::get('/email-setting', [EmailController::class, 'index'])->name('email-setting');
    Route::post('/email-setting', [EmailController::class, 'saveSetting'])->name('email-setting-save');

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
    Route::put('/roles/update/{id}', [RolesController::class, 'update'])->name('roles.update');
    Route::delete('/roles/destroy/{id}', [RolesController::class, 'destroy'])->name('roles.destroy');
    Route::get('/languages', function () {
        return view('admin.setup.languages');
    })->name('languages');

    Route::get('/modules', [PermissionController::class, 'modules'])->name('permissions.modules');
    Route::post('/permissions/toggle', [PermissionController::class, 'toggle'])
        ->name('permissions.toggle');
    Route::post('/modules/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/roles/{role}/permissions', [PermissionController::class, 'permissions'])->name('permissions');
    Route::post('/roles/permissions/save', [PermissionController::class, 'savePermissions'])->name('roles.permissions.save');

    Route::get('/languages', [LanguagesController::class, 'index'])->name('languages');
    Route::post('/languages/create', [LanguagesController::class, 'store'])->name('languages.store');
    Route::post('/languages/updateStatus/{id}', [LanguagesController::class, 'updateStatus'])->name('languages.updateStatus');
    Route::post('/languages/updateRtl/{id}', [LanguagesController::class, 'updateRtl'])->name('languages.updateRtl');
    Route::get('/languages/search', [LanguagesController::class, 'search'])->name('languages.search');

    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/patients', function () {
        return view('admin.setup.patient');
    })->name('patients');
    Route::get('/charges', function () {
        return view('admin.setup.charges');
    })->name('charges');
    Route::get('/import', function () {
        return view('admin.setup.import_patient');
    })->name('import');
    Route::get('/disable', function () {
        return view('admin.setup.disable_patient');
    })->name('disable');
    Route::get('/appointment', function () {
        return view('admin.setup.appointment_head_foot');
    })->name('appointment');
    Route::get('/operation', function () {
        return view('admin.setup.operation');
    })->name('operation');
    Route::get('/operation-category', function () {
        return view('admin.setup.operation_category');
    })->name('operation-category');
    Route::get('/charge-category', function () {
        return view('admin.setup.charge_category');
    })->name('charge-category');
    Route::get('/charge-type', function () {
        return view('admin.setup.charge_type');
    })->name('charge-type');
    Route::get('/tax-category', function () {
        return view('admin.setup.tax_category');
    })->name('tax-category');
    Route::get('/unit-type', function () {
        return view('admin.setup.unit_type');
    })->name('unit-type');
    Route::get('/medicine-category', function () {
        return view('admin.setup.medicine_category');
    })->name('medicine-category');
    Route::get('/supplier', function () {
        return view('admin.setup.supplier');
    })->name('supplier');
    Route::get('/medicine-dosage', function () {
        return view('admin.setup.medicine_dosage');
    })->name('medicine-dosage');

    Route::get('/purpose', [FrontOfficeController::class, 'purposes'])->name('purpose');
    Route::post('/purpose/store', [FrontOfficeController::class, 'storePurpose'])->name('purposes.store');
    Route::put('/purpose/update/{id}', [FrontOfficeController::class, 'updatePurpose'])->name('purposes.update');
    Route::delete('/purpose/destroy/{id}', [FrontOfficeController::class, 'destroyPurpose'])->name('purposes.destroy');

    Route::get('/complaint', [FrontOfficeController::class, 'complaintTypes'])->name('complaint');
    Route::post('/complaint/store', [FrontOfficeController::class, 'storeComplaint'])->name('complaint-types.store');
    Route::put('/complaintType/update/{id}', [FrontOfficeController::class, 'updateComplaint'])->name('complaint.update');
    Route::delete('/complaint/destroy/{id}', [FrontOfficeController::class, 'destroyComplaint'])->name('complaint-types.destroy');

    Route::get('/sources', [FrontOfficeController::class, 'sources'])->name('sources');
    Route::post('/sources/store', [FrontOfficeController::class, 'storeSources'])->name('sources.store');
    Route::put('/sources/update/{id}', [FrontOfficeController::class, 'updateSources'])->name('sources.update');
    Route::delete('/sources/destroy/{id}', [FrontOfficeController::class, 'destroySources'])->name('sources.destroy');

});

Route::get('/dosage-interval', function () {
    return view('admin.setup.dosage_interval');
})->name('dosage-interval');
Route::get('/dosage-duration', function () {
    return view('admin.setup.dosage_duration');
})->name('dosage-duration');