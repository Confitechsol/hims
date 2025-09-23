<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\BedGroupController;
use App\Http\Controllers\BedTypeController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\FrontOfficeController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PathologyController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Setup\LanguagesController;
use App\Http\Controllers\Setup\LetterHeadController;
use App\Http\Controllers\Setup\PrefixesController;
use App\Http\Controllers\Setup\ProfileController;
use App\Http\Controllers\Setup\UsersController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\VitalController;

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

    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::post('/patient', [PatientController::class, 'store'])->name('patient-store');
    Route::delete('/patients/bulk-delete', [PatientController::class, 'bulkDelete'])->name('patients.bulkDelete');
    Route::get('/patients/import', [PatientController::class, 'import'])->name('patient-import');
    Route::post('/patients/import', [PatientController::class, 'import'])->name('patients.import');

    Route::get('/languages', [LanguagesController::class, 'index'])->name('languages');
    Route::post('/languages/create', [LanguagesController::class, 'store'])->name('languages.store');
    Route::post('/languages/updateStatus/{id}', [LanguagesController::class, 'updateStatus'])->name('languages.updateStatus');
    Route::post('/languages/updateRtl/{id}', [LanguagesController::class, 'updateRtl'])->name('languages.updateRtl');
    Route::get('/languages/search', [LanguagesController::class, 'search'])->name('languages.search');

    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::post('/users/updatedrstatus/{id}', [UsersController::class, 'updateDrStatus'])->name('users.updateDrStatus');
    Route::post('/users/updatestaffstatus/{id}', [UsersController::class, 'updateStaffStatus'])->name('users.updateStaffStatus');

    Route::get('/charges', function () {
        return view('admin.setup.charges');
    })->name('charges');
    Route::get('/disable', function () {
        return view('admin.setup.disable_patient');
    })->name('disable');

    Route::get('/letterhead', [LetterHeadController::class, 'index'])->name('letterHead');
    Route::post('/letterhead/store/{categoryId}', [LetterHeadController::class, 'store'])
        ->name('letterhead.store');

    Route::prefix('operations')->group(function () {
        Route::get('/', [OperationController::class, 'Operations'])->name('operations');
        Route::post('/store', [OperationController::class, 'store'])->name('operations.store');
        Route::put('/update/{id}', [OperationController::class, 'updateCategory'])->name('operations.update');
        Route::delete('/destroy/{id}', [OperationController::class, 'destroyCategory'])->name('operations.destroy');
    });
    Route::prefix('operation-category')->group(function () {
        Route::get('/', [OperationController::class, 'operationCategories'])->name('operation-category');
        Route::post('/store', [OperationController::class, 'storeCategory'])->name('operation-category.store');
        Route::put('/update/{id}', [OperationController::class, 'updateCategory'])->name('operation-category.update');
        Route::delete('/destroy/{id}', [OperationController::class, 'destroyCategory'])->name('operation-category.destroy');
    });
    Route::get('/beds', [BedController::class, 'index'])->name('bed');
    Route::get('/bed-status', [BedController::class, 'status'])->name('bed-status');
    Route::put('/beds/update', [BedController::class, 'update'])->name('beds.update');
    Route::post('/beds/store', [BedController::class, 'store'])->name('beds.store');
    Route::delete('/beds/destroy', [BedController::class, 'destroy'])->name('beds.destroy');

    Route::get('/bed-groups', [BedGroupController::class, 'index'])->name('bed-groups.index');
    Route::post('/bed-groups/store', [BedGroupController::class, 'store'])->name('bed-groups.store');
    Route::put('/bed-groups/update', [BedGroupController::class, 'update'])->name('bed-groups.update');
    Route::delete('/bed-groups/destroy', [BedGroupController::class, 'destroy'])->name('bed-groups.destroy');

    Route::get('/bed-types', [BedTypeController::class, 'index'])->name('bed-types.index');
    Route::post('/bed-types/store', [BedTypeController::class, 'store'])->name('bed-types.store');
    Route::put('/bed-types/update', [BedTypeController::class, 'update'])->name('bed-types.update');
    Route::delete('/bed-types/destroy', [BedTypeController::class, 'destroy'])->name('bed-types.destroy');

    Route::get('/floors', [FloorController::class, 'index'])->name('floors.index');
    Route::post('/floors/store', [FloorController::class, 'store'])->name('floors.store');
    Route::put('/floors/update', [FloorController::class, 'update'])->name('floors.update');
    Route::delete('/floors/destroy', [FloorController::class, 'destroy'])->name('floors.destroy');
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
Route::get('/unit-list', function () {
    return view('admin.setup.unit_list');
})->name('unit-list');
Route::get('/company-list', function () {
    return view('admin.setup.company_list');
})->name('company-list');
Route::get('/medicine-group', function () {
    return view('admin.setup.medicine_group');
})->name('medicine-group');
Route::prefix('pathology-category')->group(function () {
Route::get('/', [PathologyController::class, 'pathologyCategories'])->name('pathology-category');
Route::post('/store', [PathologyController::class, 'storeCategory'])->name('pathology-category.store');
Route::put('/update/{id}', [PathologyController::class, 'updateCategory'])->name('pathology-category.update');
Route::delete('/destroy/{id}', [PathologyController::class, 'destroyCategory'])->name('pathology-category.destroy');

});
Route::prefix('/pathology-unit') ->group(function () {
    Route::get('/', [PathologyController::class, 'pathologyUnits'])->name('pathology-unit');
    Route::post('/store', [PathologyController::class, 'storeUnit'])->name('pathology-unit.store');
    Route::put('/update/{id}', [PathologyController::class, 'updateUnit'])->name('pathology-unit.update');
    Route::delete('/destroy/{id}', [PathologyController::class, 'destroyUnit'])->name('pathology-unit.destroy');  
});
Route::prefix('/pathology-parameter')->group(function () {
    Route::get('/', [PathologyController::class, 'pathologyParameters'])->name('pathology-parameter');
    Route::post('/store', [PathologyController::class, 'storeParameter'])->name('pathology-parameter.store');
    Route::put('/update/{id}', [PathologyController::class, 'updateParameter'])->name('pathology-parameter.update');
    Route::delete('/destroy/{id}', [PathologyController::class, 'destroyParameter'])->name('pathology-parameter.destroy');
});
Route::get('/radiology-category', function () {
    return view('admin.setup.radiology_category');
})->name('radiology-category');
Route::get('/radiology-unit', function () {
    return view('admin.setup.radiology_unit');
})->name('radiology-unit');
Route::get('/radiology-parameter', function () {
    return view('admin.setup.radiology_parameter');
})->name('radiology-parameter');
Route::prefix('/blood-bank-products')->group(function () {
    Route::get('/', [BloodBankController::class, 'products'])->name('blood-bank-products');
    Route::post('/store', [BloodBankController::class, 'storeProduct'])->name('blood-bank-products.store');
    Route::put('/update/{id}', [BloodBankController::class, 'updateProduct'])->name('blood-bank-products.update');
    Route::delete('/destroy/{id}', [BloodBankController::class, 'destroyProduct'])->name('blood-bank-products.destroy');
});
Route::prefix('/symptoms-type')->group(function () {
    Route::get('/', [SymptomController::class, 'symptomType'])->name('symptoms-type');
    Route::post('/store', [SymptomController::class, 'storeSymptomType'])->name('symptoms-type.store');
    Route::put('/update/{id}', [SymptomController::class, 'updateSymptomType'])->name('symptoms-type.update');
    Route::delete('/destroy/{id}', [SymptomController::class, 'destroySymptomType'])->name('symptoms-type.destroy');
});
Route::prefix('/symptoms-head')->group(function () {
    Route::get('/', [SymptomController::class, 'symptomHead'])->name('symptoms-head');
    Route::post('/store', [SymptomController::class, 'storeSymptomHead'])->name('symptoms-head.store');
    Route::put('/update/{id}', [SymptomController::class, 'updateSymptomHead'])->name('symptoms-head.update');
    Route::delete('/destroy/{id}', [SymptomController::class, 'destroySymptomHead'])->name('symptoms-head.destroy');
});

Route::get('/finding', function () {
    return view('admin.setup.finding');
})->name('finding');
Route::get('/finding-category', function () {
    return view('admin.setup.finding_category');
})->name('finding-category');
Route::prefix('/vital') ->group(function () {
    Route::get('/', [VitalController::class, 'index'])->name('vitals');
    Route::post('/store', [VitalController::class, 'store'])->name('vital.store');
    Route::put('/update/{id}', [VitalController::class, 'update'])->name('vital.update');
    Route::delete('/destroy/{id}', [VitalController::class, 'destroy'])->name('vital.destroy');
});
Route::get('/income-head', function () {
    return view('admin.setup.income_head');
})->name('income-head');
Route::get('/expense-head', function () {
    return view('admin.setup.expense_head');
})->name('expense-head');
Route::get('/leave-type', function () {
    return view('admin.setup.leave_type');
})->name('leave-type');
Route::get('/department', function () {
    return view('admin.setup.department');
})->name('department');
Route::get('/designation ', function () {
    return view('admin.setup.designation');
})->name('designation');
Route::get('/specialist ', function () {
    return view('admin.setup.specialist');
})->name('specialist');
Route::get('/item-category ', function () {
    return view('admin.setup.item_category');
})->name('item-category');
Route::get('/item-store ', function () {
    return view('admin.setup.item_store');
})->name('item-store');
Route::get('/item-supplier ', function () {
    return view('admin.setup.item_supplier');
})->name('item-supplier');
Route::get('/slots ', function () {
    return view('admin.setup.slots');
})->name('slots');
Route::get('/doctor-shift ', function () {
    return view('admin.setup.doctor_shift');
})->name('doctor-shift');
Route::get('/shift ', function () {
    return view('admin.setup.shift');
})->name('shift');
Route::get('/appointment-priority ', function () {
    return view('admin.setup.appointment_priority');
})->name('appointment-priority');