<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\BedGroupController;
use App\Http\Controllers\BedTypeController;
use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\FrontOfficeController;
use App\Http\Controllers\InventoriesController;
use App\Http\Controllers\MedicineCategoryController;
use App\Http\Controllers\MedicineGroupController;
use App\Http\Controllers\Modules\OpdController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PathologyController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Setup\CompanyListController;
use App\Http\Controllers\Setup\DosageDurationController;
use App\Http\Controllers\Setup\DoseIntervalController;
use App\Http\Controllers\Setup\FindingsController;
use App\Http\Controllers\Setup\HospitalChargeCategoryController;
use App\Http\Controllers\Setup\HospitalChargesController;
use App\Http\Controllers\Setup\HospitalChargeTypeController;
use App\Http\Controllers\Setup\HospitalTaxCategoryController;
use App\Http\Controllers\Setup\HospitalUnitTypeController;
use App\Http\Controllers\Setup\HrController;
use App\Http\Controllers\Setup\InventoryController;
use App\Http\Controllers\Setup\LanguagesController;
use App\Http\Controllers\Setup\LetterHeadController;
use App\Http\Controllers\Setup\MedicineDosageController;
use App\Http\Controllers\Setup\MedicineSupplierController;
use App\Http\Controllers\Setup\PrefixesController;
use App\Http\Controllers\Setup\ProfileController;
use App\Http\Controllers\Setup\RadiologyController;
use App\Http\Controllers\Setup\UnitController;
use App\Http\Controllers\Setup\UsersController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\TpamanagmentController;
use App\Http\Controllers\VitalController;
use App\Http\Controllers\IncomeController;
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
    Route::get('/getPatients', [PatientController::class, 'getPatients'])->name('getPatients');
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

    Route::get('/charges', [HospitalChargesController::class, 'index'])->name('charges');
    Route::post('/charges', [HospitalChargesController::class, 'store'])->name('charges.store');
    Route::put('/charges/update', [HospitalChargesController::class, 'update'])->name('charges.update');
    Route::delete('/charges/destroy', [HospitalChargesController::class, 'destroy'])->name('charges.destroy');

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

    Route::get('/charge-category', [HospitalChargeCategoryController::class, 'index'])->name('charge_categories');
    Route::post('/charge-category', [HospitalChargeCategoryController::class, 'store'])->name('charge_categories.store');
    route::put('/charge-category/update', [HospitalChargeCategoryController::class, 'update'])->name('charge_categories.update');
    route::delete('/charge-category/destroy', [HospitalChargeCategoryController::class, 'destroy'])->name('charge_categories.destroy');

    Route::get('/charge-type', [HospitalChargeTypeController::class, 'index'])->name('charge_type_module');
    Route::post('/charge-type', [HospitalChargeTypeController::class, 'store'])->name('charge_type_module.store');
    Route::put('/charge-type/update', [HospitalChargeTypeController::class, 'update'])->name('charge_type_module.update');
    Route::delete('/charge-type/destroy', [HospitalChargeTypeController::class, 'destroy'])->name('charge_type_module.destroy');
    Route::post('/update-charge-type-module', [HospitalChargeTypeController::class, 'updateChargeTypeModule'])->name('updateChargeTypeModule');

    Route::get('/tax-category', [HospitalTaxCategoryController::class, 'index'])->name('tax_category');
    Route::post('/tax-category', [HospitalTaxCategoryController::class, 'store'])->name('tax_category.store');
    Route::put('/tax-category/update', [HospitalTaxCategoryController::class, 'update'])->name('tax_category.update');
    Route::delete('/tax-category/destroy', [HospitalTaxCategoryController::class, 'destroy'])->name('tax_category.destroy');

    Route::get('/unit-type', [HospitalUnitTypeController::class, 'index'])->name('charge_units');
    Route::post('/unit-type', [HospitalUnitTypeController::class, 'store'])->name('charge_units.store');
    Route::put('/unit-type/update', [HospitalUnitTypeController::class, 'update'])->name('charge_units.update');
    Route::delete('/unit-type/destroy', [HospitalUnitTypeController::class, 'destroy'])->name('charge_units.destroy');

    Route::get('/medicine-category', function () {
        return view('admin.setup.medicine_category');
    })->name('medicine-category');

    Route::get('/supplier', [MedicineSupplierController::class, 'index'])->name('supplier');
    Route::post('/supplier/store', [MedicineSupplierController::class, 'store'])->name('supplier-store');
    Route::delete('/supplier/destroy', [MedicineSupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::put('/supplier/update', [MedicineSupplierController::class, 'update'])->name('supplier.update');

    Route::get('/medicine-dosage', [MedicineDosageController::class, 'index'])->name('medicine-dosage');
    Route::put('/medicine-dosage/update', [MedicineDosageController::class, 'update'])->name('medicine-dosage.update');
    Route::post('/medicine-dosage/store', [MedicineDosageController::class, 'store'])->name('medicine-dosage.store');
    Route::delete('/medicine-dosage/destroy', [MedicineDosageController::class, 'destroy'])->name('medicine-dosage.destroy');

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

    Route::get('/company-list', [CompanyListController::class, 'index'])->name('company-list');
    Route::post('/company-list/store', [CompanyListController::class, 'store'])->name('company-list.store');
    Route::put('/company-list/update', [CompanyListController::class, 'update'])->name('company-list.update');
    Route::delete('/company-list/destroy', [CompanyListController::class, 'destroy'])->name('company-list.destroy');

    Route::get('/dosage-interval', [DoseIntervalController::class, 'index'])->name('dosage-interval');
    Route::put('/dosage-interval/update', [DoseIntervalController::class, 'update'])->name('dosage-interval.update');
    Route::post('/dosage-interval/store', [DoseIntervalController::class, 'store'])->name('dosage-interval.store');
    Route::delete('/dosage-interval/destroy', [DoseIntervalController::class, 'destroy'])->name('dosage-interval.destroy');

    Route::get('/dosage-duration', [DosageDurationController::class, 'index'])->name('dosage-duration');
    Route::put('/dosage-duration/update', [DosageDurationController::class, 'update'])->name('dosage-duration.update');
    Route::post('/dosage-duration/store', [DosageDurationController::class, 'store'])->name('dosage-duration.store');
    Route::delete('/dosage-duration/destroy', [DosageDurationController::class, 'destroy'])->name('dosage-duration.destroy');

    Route::get('/unit-list', [UnitController::class, 'index'])->name('unit-list');
    Route::post('/unit-list/store', [UnitController::class, 'store'])->name('unit-list.store');
    Route::put('/unit-list/update', [UnitController::class, 'update'])->name('unit-list.update');
    Route::delete('/unit-list/destroy', [UnitController::class, 'destroy'])->name('unit-list.destroy');

    Route::get('/medicine-group', [MedicineGroupController::class, 'index'])->name('medicine-group');
    Route::post('/medicine-group/store-multiple', [MedicineGroupController::class, 'storeMultiple'])->name('medicine-group.storeMultiple');
    Route::put('/medicine-group/{id}', [MedicineGroupController::class, 'update'])->name('medicine-group.update');
    Route::delete('/medicine-group/destroy', [MedicineGroupController::class, 'destroy'])->name('medicine-group.destroy');

    Route::get('/medicine-categories', [MedicineCategoryController::class, 'index'])->name('medicine-categories');
    Route::post('/medicine-categories/store-multiple', [MedicineCategoryController::class, 'storeMultiple'])->name('medicine-categories.storeMultiple');
    Route::put('/medicine-categories/{id}', [MedicineCategoryController::class, 'update'])->name('medicine-categories.update');
    Route::delete('/medicine-categories/destroy', [MedicineCategoryController::class, 'destroy'])->name('medicine-categories.destroy');

    Route::get('/tpamanagement', [TpamanagmentController::class, 'index'])->name('tpamanagement');
    Route::post('/tpamanagement/store', [TpamanagmentController::class, 'store'])->name('tpamanagement.store');
    Route::put('/tpamanagement/update', [TpamanagmentController::class, 'update'])->name('tpamanagement.update');
    Route::delete('/tpamanagement/destroy', [TpamanagmentController::class, 'destroy'])->name('tpamanagement.destroy');

    Route::get('/tpa_details/{id?}', [TpamanagmentController::class, 'detailsshow'])->name('tpa_details.show');
    Route::post('/tpa_details/{id?}', [TpamanagmentController::class, 'detailsshow'])->name('tpa_details.chragetype');
    Route::delete('/tpa_details/destroy', [TpamanagmentController::class, 'destroyTpaDetails'])->name('tpa_details.destroy');
    Route::put('/tpa_details/update', [TpamanagmentController::class, 'updateTpaDetails'])->name('tpa_details.update');

    Route::get('/income', [IncomeController::class, 'index'])->name('income');
    Route::post('/income/create', [IncomeController::class, 'create'])->name('income.create');
    Route::put('/income/update', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('/income/destroy', [IncomeController::class, 'destroy'])->name('income.destroy');


});

// Route::get('/medicine-group', function () {
//     return view('admin.setup.medicine_group');
// })->name('medicine-group');

Route::prefix('pathology-category')->group(function () {
    Route::get('/', [PathologyController::class, 'pathologyCategories'])->name('pathology-category');
    Route::post('/store', [PathologyController::class, 'storeCategory'])->name('pathology-category.store');
    Route::put('/update/{id}', [PathologyController::class, 'updateCategory'])->name('pathology-category.update');
    Route::delete('/destroy/{id}', [PathologyController::class, 'destroyCategory'])->name('pathology-category.destroy');

});
Route::prefix('/pathology-unit')->group(function () {
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

Route::get('/radiology-category', [RadiologyController::class, 'radiologyCategoryIndex'])->name('radiology-category');
Route::post('/radiology-category/store', [RadiologyController::class, 'store'])->name('radiology-category.store');
Route::put('/radiology-category/update', [RadiologyController::class, 'update'])->name('radiology-category.update');
Route::post('/radiology-category/updateStatus/{id}', [RadiologyController::class, 'updateStatus'])->name('radiology-category.updateStatus');
Route::delete('/radiology-category/delete/{id}', [RadiologyController::class, 'delete'])->name('radiology-category.delete');

Route::get('/radiology-unit', [RadiologyController::class, 'radiologyUnitIndex'])->name('radiology-unit');
Route::post('/radiology-unit/store', [RadiologyController::class, 'storeUnit'])->name('radiology-unit.store');
Route::put('/radiology-unit/updateUnit', [RadiologyController::class, 'updateUnit'])->name('radiology-unit.updateUnit');
Route::delete('/radiology-unit/deleteUnit/{id}', [RadiologyController::class, 'deleteUnit'])->name('radiology-unit.deleteUnit');

Route::get('/radiology-parameter', [RadiologyController::class, 'radiologyParameterIndex'])->name('radiology-parameter');
Route::post('/radiology-parameter/store', [RadiologyController::class, 'storeParameter'])->name('radiology-parameter.store');
Route::put('/radiology-parameter/update', [RadiologyController::class, 'updateParameter'])->name('radiology-parameter.update');
Route::delete('/radiology-parameter/delete/{id}', [RadiologyController::class, 'deleteParameter'])->name('radiology-parameter.delete');

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

Route::get('/finding', [FindingsController::class, 'index'])->name('finding');
Route::post('/finding/store', [FindingsController::class, 'store'])->name('finding.store');
Route::put('/finding/update', [FindingsController::class, 'update'])->name('finding.update');
Route::delete('/finding/delete/{id}', [FindingsController::class, 'delete'])->name('finding.delete');
Route::get('/finding-category', [FindingsController::class, 'indexCategory'])->name('finding-category');
Route::post('/finding-category/storeCategory', [FindingsController::class, 'storeCategory'])->name('finding-category.storeCategory');
Route::put('/finding-category/updateCategory', [FindingsController::class, 'updateCategory'])->name('finding-category.updateCategory');
Route::delete('/finding-category/deleteCategory/{id}', [FindingsController::class, 'deleteCategory'])->name('finding-category.deleteCategory');

Route::prefix('/vital')->group(function () {
    Route::get('/', [VitalController::class, 'index'])->name('vitals');
    Route::post('/store', [VitalController::class, 'store'])->name('vital.store');
    Route::put('/update/{id}', [VitalController::class, 'update'])->name('vital.update');
    Route::delete('/destroy/{id}', [VitalController::class, 'destroy'])->name('vital.destroy');
});
Route::prefix('/income-head')->group(function () {
    Route::get('/', [FinanceController::class, 'income'])->name('income-head');
    Route::post('/store', [FinanceController::class, 'incomeStore'])->name('income-head.store');
    Route::put('/update/{id}', [FinanceController::class, 'incomeUpdate'])->name('income-head.update');
    Route::delete('/destroy/{id}', [FinanceController::class, 'incomeDestroy'])->name('income-head.destroy');
});
Route::prefix('/expense-head')->group(function () {
    Route::get('/', [FinanceController::class, 'expense'])->name('expense-head');
    Route::post('/store', [FinanceController::class, 'expenseStore'])->name('expense-head.store');
    Route::put('/update/{id}', [FinanceController::class, 'expenseUpdate'])->name('expense-head.update');
    Route::delete('/destroy/{id}', [FinanceController::class, 'expenseDestroy'])->name('expense-head.destroy');
});
Route::get('/leave-type', [HrController::class, 'index'])->name('leave-type');
Route::post('/leave-type/store', [HrController::class, 'store'])->name('leave-type.store');
Route::post('/leave-type/updateStatus/{id}', [HrController::class, 'updateStatus'])->name('leave-type.updateStatus');
Route::put('/leave-type/update', [HrController::class, 'update'])->name('leave-type.update');
Route::delete('/leave-type/delete/{id}', [HrController::class, 'delete'])->name('leave-type.delete');

Route::get('/department', [HrController::class, 'indexDepartment'])->name('department');
Route::post('/department/store', [HrController::class, 'storeDepartment'])->name('department.store');
Route::post('/department/updateStatus/{id}', [HrController::class, 'updateDepartmentStatus'])->name('department.updateStatus');
Route::put('/department/update', [HrController::class, 'updateDepartment'])->name('department.update');
Route::delete('/department/delete/{id}', [HrController::class, 'deleteDepartment'])->name('department.delete');

Route::get('/designation', [HrController::class, 'indexDesignation'])->name('designation');
Route::post('/designation/store', [HrController::class, 'storeDesignation'])->name('designation.store');
Route::post('/designation/updateStatus/{id}', [HrController::class, 'updateDesignationStatus'])->name('designation.updateStatus');
Route::put('/designation/update', [HrController::class, 'updateDesignation'])->name('designation.update');
Route::delete('/designation/delete/{id}', [HrController::class, 'deleteDesignation'])->name('designation.delete');

Route::get('/specialist', [HrController::class, 'indexSpecialist'])->name('specialist');
Route::post('/specialist/store', [HrController::class, 'storeSpecialist'])->name('specialist.store');
Route::post('/specialist/updateSpecialistStatus/{id}', [HrController::class, 'updateSpecialistStatus'])->name('specialist.updateSpecialistStatus');
Route::put('/specialist/updateSpecialist', [HrController::class, 'updateSpecialist'])->name('specialist.updateSpecialist');
Route::delete('/specialist/deleteSpecialist/{id}', [HrController::class, 'deleteSpecialist'])->name('specialist.deleteSpecialist');
Route::get('/item-category', [InventoryController::class, 'index'])->name('item-category');
Route::post('/item-category/store', [InventoryController::class, 'store'])->name('item-category.store');
Route::put('/item-category/update', [InventoryController::class, 'update'])->name('item-category.update');
Route::post('/item-category/updateStatus/{id}', [InventoryController::class, 'updateStatus'])->name('item-category.updateStatus');
Route::delete('/item-category/delete/{id}', [InventoryController::class, 'delete'])->name('item-category.delete');

Route::get('/item-store', [InventoryController::class, 'indexStore'])->name('item-store');
Route::post('/item-store/store', [InventoryController::class, 'storeItemStore'])->name('item-store.store');
Route::put('/item-store/update', [InventoryController::class, 'updateStore'])->name('item-store.update');
Route::post('/item-store/updateStatus/{id}', [InventoryController::class, 'updateStoreStatus'])->name('item-store.updateStatus');
Route::delete('/item-store/delete/{id}', [InventoryController::class, 'deleteStore'])->name('item-store.delete');

Route::get('/item-supplier', [InventoryController::class, 'indexSupplier'])->name('item-supplier');
Route::post('/item-supplier/store', [InventoryController::class, 'storeItemSupplier'])->name('item-supplier.store');
Route::post('/item-supplier/updateStatus/{id}', [InventoryController::class, 'updateSupplierStatus'])->name('item-supplier.updateStatus');
Route::put('/item-supplier/update', [InventoryController::class, 'updateSupplier'])->name('item-supplier.update');
Route::delete('/item-supplier/delete/{id}', [InventoryController::class, 'deleteSupplier'])->name('item-supplier.delete');

Route::prefix('/slots')->group(function () {
    Route::get('/', [AppointmentController::class, 'slots'])->name('slots');
    Route::post('/store', [AppointmentController::class, 'slotsStore'])->name('slots.store');
    Route::put('/update/{id}', [AppointmentController::class, 'slotsUpdate'])->name('slots.update');
    Route::delete('/destroy/{id}', [AppointmentController::class, 'slotsDestroy'])->name('slots.destroy');
    Route::get('/search', [AppointmentController::class, 'searchSlots'])->name('slots.search');
    Route::get('/get-charges/{id}', [AppointmentController::class, 'getCharges'])->name('get.charges');
});
Route::get('/doctor/{id}/shifts', [AppointmentController::class, 'getDoctorShifts'])
    ->name('doctor.shifts');

Route::prefix('/doctor-shift')->group(function () {
    Route::get('/', [AppointmentController::class, 'doctorShift'])->name('doctor-shift');
    Route::post('/store', [AppointmentController::class, 'doctorShiftStore'])->name('doctor-shift.store');
    Route::put('/update/{id}', [AppointmentController::class, 'shiftUpdate'])->name('doctor-shift.update');
    Route::delete('/destroy/{id}', [AppointmentController::class, 'shiftDestroy'])->name('doctor-shift.destroy');
    Route::post('/toggle', [AppointmentController::class, 'toggleDoctorShift'])->name('doctor-shift.toggle');
    Route::get('/fetchShifts/{doctor}', [AppointmentController::class, 'getDoctorShifts'])->name('doctor.shifts');

});
Route::prefix('/shift')->group(function () {
    Route::get('/', [AppointmentController::class, 'shift'])->name('shift');
    Route::post('/store', [AppointmentController::class, 'shiftStore'])->name('shift.store');
    Route::put('/update/{id}', [AppointmentController::class, 'shiftUpdate'])->name('shift.update');
    Route::delete('/destroy/{id}', [AppointmentController::class, 'shiftDestroy'])->name('shift.destroy');

});
Route::prefix('/appointment-priority')->group(function () {
    Route::get('/', [AppointmentController::class, 'appointmentPriority'])->name('appointment-priority');
    Route::post('/store', [AppointmentController::class, 'appointmentPriorityStore'])->name('appointment-priority.store');
    Route::put('/update/{id}', [AppointmentController::class, 'appointmentPriorityUpdate'])->name('appointment-priority.update');
    Route::delete('/destroy/{id}', [AppointmentController::class, 'appointmentPriorityDestroy'])->name('appointment-priority.destroy');
});

Route::get('/opd', [OpdController::class, 'index'])->name('opd');
Route::post('/opd/store', [OpdController::class, 'store'])->name('opd.store');
Route::get('/opd/edit/{id}', [OpdController::class, 'edit'])->name('opd.edit');
Route::put('/opd/update/{id}', [OpdController::class, 'update'])->name('opd.update');
Route::get('/getOrganizations', [PatientController::class, 'organizations'])->name('getOrganizations');
Route::get('/getDoctors', [OpdController::class, 'getDoctors'])->name('getDoctors');
Route::get('/getChargeCategories', [OpdController::class, 'getChargeCategories'])->name('getChargeCategories');
Route::get('/getCharges/{id}', [OpdController::class, 'getCharges'])->name('getCharges');
Route::get('/getSymptomsTypes', [OpdController::class, 'getSymptomsType'])->name('getSymptomsTypes');
Route::post('/getSymptoms', [OpdController::class, 'getSymptoms'])->name('getSymptoms');

Route::get('/billing', function () {
    return view('admin.billing.billing');
})->name('billing');
Route::get('/patient_profile', function () {
    return view('admin.patient_profile');
})->name('patient_profile');
Route::get('/patient_details', function () {
    return view('admin.patient_details');
})->name('patient_details');

Route::prefix('/appointment-details')->group(function () {
    Route::get('/', [AppointmentsController::class, 'appointmentDetails'])->name('appointment-details');
    Route::post('/store', [AppointmentsController::class, 'store'])->name('appointments.store');
    Route::get('/get-doctor-shifts/{id}', [AppointmentController::class, 'getDoctorShifts'])->name('doctor.shifts');
    Route::get('/get-doctor-slots/{doctorId}/{shiftId}', [AppointmentController::class, 'getDoctorSlots'])->name('doctor.slots');
    Route::get('/get-appointment-priorities', [AppointmentController::class, 'getAppointmentPriorities'])->name('appointment.priorities');

    Route::get('/appointments/{id}/edit', [AppointmentsController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}', [AppointmentsController::class, 'update'])->name('appointments.update');
    Route::get('/doctor-wise', [AppointmentsController::class, 'doctorwise'])->name('appointments.doctor-wise');
    Route::post('/doctor-wise/search', [AppointmentsController::class, 'searchAppointments'])->name('appointments.search');
    Route::get('/queue', function () {return view('admin.appointments.queue');})->name('appointments.queue');
    Route::get('/queue', function () {return view('admin.appointments.queue');})->name('appointments.queue');
    Route::get('patient-view/{patient_id}', [AppointmentsController::class, 'show'])->name('patient.view');

    

});

Route::prefix('/inventory')->group(function () {
    Route::get('/', [InventoriesController::class, 'index'])->name('inventory-details');
    Route::get('/get-items/{categoryId}', [InventoriesController::class, 'getItems'])->name('get.items');
    Route::post('/store', [InventoriesController::class, 'store'])->name('itemstock.store');
    Route::get('/edit/{id}', [InventoriesController::class, 'edit'])->name('itemstock.edit');
    Route::get('/update', [InventoriesController::class, 'update'])->name('itemstock.update');
    Route::get('/destroy', [InventoriesController::class, 'destroy'])->name('itemstock.destroy');

    Route::get('/items', [InventoriesController::class, 'items'])->name('items');
    Route::post('/item-store', [InventoriesController::class, 'storeItem'])->name('items.store');
    Route::get('/item-edit/{id}', [InventoriesController::class, 'editItem'])->name('items.edit');
    Route::get('/item-update', [InventoriesController::class, 'updateItem'])->name('items.update');
    Route::delete('/item-destroy/{id}', [InventoriesController::class, 'destroyItem'])->name('items.destroy');

    Route::get('/get-staff-by-department', [InventoriesController::class, 'getStaffByDepartment'])->name('get-staff-by-department');
    
    Route::get('/issue-items', [InventoriesController::class, 'issueItems'])->name('issue-items');
    Route::post('/issue-store', [InventoriesController::class, 'storeIssuedItem'])->name('issue-items.store');
    Route::get('/issue-edit/{id}', [InventoriesController::class, 'editIssuedItem'])->name('issue-items.edit');
    Route::get('/get-items-by-category', [InventoriesController::class, 'getItemsByCategory'])->name('get-items-by-category');
    Route::get('/issue-update/{id}', [InventoriesController::class, 'updateIssuedItem'])->name('issue-items.update');
    Route::delete('/issue-destroy/{id}', [InventoriesController::class, 'destroyIssuedItem'])->name('issue-items.destroy');
});

Route::get('/opd-billing', function () {
    return view('admin.billing.opd');
})->name('opd.billing');
Route::get('/visit_details', function () {
    return view('admin.visit_details');
})->name('visit_details');
Route::get('/opd_view', function () {
    return view('admin.opd.opd_view');
})->name('opd_view');
Route::get('/generate_certificate', function () {
    return view('admin.certificate.generate_certificate');
})->name('generate_certificate');
Route::get('/certificate', function () {
    return view('admin.certificate.certificate');
})->name('certificate');
