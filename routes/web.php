<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AppSwitchController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\BedGroupController;
use App\Http\Controllers\BedTypeController;
use App\Http\Controllers\BirthController;
use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\BloodDonorController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DeathController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DutyRosterController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\FrontOfficeController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\InventoriesController;
use App\Http\Controllers\MedicineCategoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineGroupController;
use App\Http\Controllers\Modules\IpdController;
use App\Http\Controllers\Modules\IpdViewController;
use App\Http\Controllers\Modules\OpdController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PathologyBillingController;
use App\Http\Controllers\PathologyController;
use App\Http\Controllers\PathologyTestController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PharmacyCompanyController;
use App\Http\Controllers\RadiologyBillingController;
use App\Http\Controllers\RadiologyTestController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Setup\CompanyListController;
use App\Http\Controllers\Setup\DosageDurationController;
use App\Http\Controllers\Setup\DoseDurationController;
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
use App\Http\Controllers\Setup\MedicineCategoryController as SetupMedicineCategoryController;
use App\Http\Controllers\Setup\MedicineCompanyController as SetupMedicineCompanyController;
use App\Http\Controllers\Setup\MedicineDosageController;
use App\Http\Controllers\Setup\MedicineDosageController as SetupMedicineDosageController;
use App\Http\Controllers\Setup\MedicineGroupController as SetupMedicineGroupController;
use App\Http\Controllers\Setup\MedicineSupplierController;
use App\Http\Controllers\Setup\MedicineSupplierController as SetupMedicineSupplierController;
use App\Http\Controllers\Setup\MedicineUnitController as SetupMedicineUnitController;
use App\Http\Controllers\Setup\PrefixesController;
use App\Http\Controllers\Setup\ProfileController;
use App\Http\Controllers\Setup\RadiologyController;
use App\Http\Controllers\Setup\UnitController;
use App\Http\Controllers\Setup\UsersController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\TpamanagmentController;
use App\Http\Controllers\VisitorsController;
use App\Http\Controllers\VitalController;
use App\Http\Controllers\TransactionReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.homeScreen');
})->name('homescreen');

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

Route::middleware(['auth'])->get('/hr-portal/redirect', [AppSwitchController::class, 'switchToClient'])->name('hrms.switch');

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
    Route::get('/patient/edit/{id}', [PatientController::class, 'edit'])->name('patient.edit');
    Route::put('/patient/update/{id}', [PatientController::class, 'update'])->name('patient-update');
    Route::delete('/patients/bulk-delete', [PatientController::class, 'bulkDelete'])->name('patients.bulkDelete');
    Route::get('/patients/import', [PatientController::class, 'import'])->name('patient-import');
    Route::post('/patients/bulk-import', [PatientController::class, 'bulkImport'])->name('patients.import');
    Route::get('/patients/export', [PatientController::class, 'exportPatientsExcel'])->name('patients.export');

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
    Route::post('/letterhead/category/store', [LetterHeadController::class, 'storeLetterheadCategory'])
        ->name('letterheadCategory.store');

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
    Route::put('/income/update/{id}', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('/income/destroy', [IncomeController::class, 'destroy'])->name('income.destroy');

    Route::get('/expense', [ExpenseController::class, 'index'])->name('expense');
    Route::post('/expense/create', [ExpenseController::class, 'create'])->name('expense.create');
    Route::put('/expense/update/{id}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('/expense/delete/{id}', [ExpenseController::class, 'delete'])->name('expense.delete');

    Route::get('/birth', [BirthController::class, 'index'])->name('birth');
    Route::post('/birth/create', [BirthController::class, 'create'])->name('birth.create');
    Route::put('/birth/update/{id}', [BirthController::class, 'update'])->name('birth.update');
    Route::delete('/birth/delete/{id}', [BirthController::class, 'delete'])->name('birth.delete');

    Route::get('/death', [DeathController::class, 'index'])->name('death');
    Route::post('/death/create', [DeathController::class, 'create'])->name('death.create');
    Route::put('/death/update/{id}', [DeathController::class, 'update'])->name('death.update');
    Route::delete('/death/delete/{id}', [DeathController::class, 'delete'])->name('death.delete');
    Route::get('/death/patient/{id}', [DeathController::class, 'getPatient'])->name('death.patient');

    Route::get('/visitors', [VisitorsController::class, 'index'])->name('visitors');
    Route::post('/visitors/create', [VisitorsController::class, 'create'])->name('visitors.create');
    Route::put('/visitors/update/{id}', [VisitorsController::class, 'update'])->name('visitors.update');
    Route::delete('/visitors/delete/{id}', [VisitorsController::class, 'delete'])->name('visitors.delete');
    Route::get('/phone-call-log', [VisitorsController::class, 'phoneCallLog'])->name('phone-call-log');
    Route::post('/phone-call-log/create', [VisitorsController::class, 'createCallLog'])->name('phone-call-log.create');

    // Dispatch / Receive (Postal) pages
    Route::get('/dispatch-receive/receive', [\App\Http\Controllers\DispatchReceiveController::class, 'receive'])->name('dispatch.receive');
    Route::get('/dispatch-receive/dispatch', [\App\Http\Controllers\DispatchReceiveController::class, 'dispatch'])->name('dispatch.dispatch');
    Route::get('/dispatch-receive/list-json', [\App\Http\Controllers\DispatchReceiveController::class, 'listJson'])->name('dispatch.list_json');
    Route::get('/dispatch-receive/create', [\App\Http\Controllers\DispatchReceiveController::class, 'create'])->name('dispatch.create');
    Route::post('/dispatch-receive/store', [\App\Http\Controllers\DispatchReceiveController::class, 'store'])->name('dispatch.store');
    Route::put('/dispatch-receive/update/{id}', [\App\Http\Controllers\DispatchReceiveController::class, 'update'])->name('dispatch.update');
    Route::get('/dispatch-receive/{id}', [\App\Http\Controllers\DispatchReceiveController::class, 'show'])->name('dispatch.show');
    Route::delete('/dispatch-receive/delete/{id}', [\App\Http\Controllers\DispatchReceiveController::class, 'destroy'])->name('dispatch.destroy');


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
    Route::get('/get_pathologies', [PathologyController::class, 'getPathologies'])->name('getPathologies');
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
Route::get('/get_radiologies', [RadiologyController::class, 'getRadiologies'])->name('getRadiologies');

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
Route::get('/get_finding_categories', [FindingsController::class, 'getFindingCategories'])->name('getFindingCategories');
Route::post('/get_findings', [FindingsController::class, 'getFindings'])->name('getFindings');

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
Route::get('/getChargeTypes', [OpdController::class, 'getChargeTypes'])->name('getChargeTypes');
Route::get('/getChargeCategoriesByTypeId/{id}', [OpdController::class, 'getChargeCategoriesByTypeId'])->name('getChargeCategoriesByTypeId');
Route::get('/getChargeCategories', [OpdController::class, 'getChargeCategories'])->name('getChargeCategories');
Route::get('/getCharges/{id}', [OpdController::class, 'getCharges'])->name('getCharges');
Route::get('/getSymptomsTypes', [OpdController::class, 'getSymptomsType'])->name('getSymptomsTypes');
Route::post('/getSymptoms', [OpdController::class, 'getSymptoms'])->name('getSymptoms');
Route::get('/opd_view/{id}', [OpdController::class, 'showOpd'])->name('opd.show');
Route::get('/getOpdById/{id}', [OpdController::class, 'getOpdById'])->name('getOpdById');
Route::get('/getOpdMedicineById/{id}', [OpdController::class, 'getOpdMedicineById'])->name('getOpdMedicineById');
Route::post('/add_prescription', [OpdController::class, 'storePrescription'])->name('opd.addPrescription');
Route::post('/opd_medication', [OpdController::class, 'createOpdMedication'])->name('opd.createMedication');
Route::post('/opd_charge', [OpdController::class, 'addOpdCharge'])->name('opd.addOpdCharge');
Route::post('/opd_visit', [OpdController::class, 'storeVisitDetails'])->name('opd.visit.store');

Route::get('/ipd', [IpdController::class, 'index'])->name('ipd');
Route::post('/ipd/store', [IpdController::class, 'store'])->name('ipd.store');
Route::get('/ipd/edit/{id}', [IpdController::class, 'edit'])->name('ipd.edit');
Route::put('/ipd/update/{id}', [IpdController::class, 'update'])->name('ipd.update');
Route::get('/getBedGroups', [IpdController::class, 'getBedGroups'])->name('getBedGroups');
Route::get('/get-available-beds', [IpdController::class, 'getAvailableBeds'])->name('get.available.beds');
Route::get('/getBedNumbers/{id}', [IpdController::class, 'getBedNumbers'])->name('getBedNumbers');
Route::get('/ipd_view/{id}', [IpdViewController::class, 'showIpd'])->name('ipd.show');
Route::post('/ipd_view/medicine/store', [IpdViewController::class, 'store'])->name('medication.store');
Route::put('/ipd_view/update', [IpdViewController::class, 'update'])->name('medication.update');
Route::put('/ipd_view/delete/{id}', [IpdViewController::class, 'delete'])->name('medication.delete');
Route::post('/ipd_view/operation/store', [IpdViewController::class, 'storeOperation'])->name('operation.store');
Route::put('/ipd_view/operation/update/{id}', [IpdViewController::class, 'updateOperation'])->name('operation.update');
Route::get('/getNurses', [IpdController::class, 'getNurses'])->name('getNurses');
Route::get('/getIpdById/{id}', [IpdController::class, 'getIpdById'])->name('getIpdById');
Route::get('/getIpdMedicineById/{id}', [IpdController::class, 'getIpdMedicineById'])->name('getIpdMedicineById');
Route::post('/add_nurse_note', [IpdController::class, 'addNurseNote'])->name('nurseNote.store');
Route::post('/ipd/add_prescription', [IpdController::class, 'storePrescription'])->name('ipd.addPrescription');
Route::post('/ipd_charge', [IpdController::class, 'addIpdCharge'])->name('ipd.addIpdCharge');
Route::post('/assignNewBed', [IpdController::class, 'assignNewBed'])->name('assignNewBed');

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
    Route::get('/get-doctor-fees', [AppointmentController::class, 'getDoctorFees'])->name('appointments.getDoctorFees');

    Route::get('/appointments/{id}/edit', [AppointmentsController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}', [AppointmentsController::class, 'update'])->name('appointments.update');
    Route::get('/doctor-wise', [AppointmentsController::class, 'doctorwise'])->name('appointments.doctor-wise');
    Route::post('/doctor-wise/search', [AppointmentsController::class, 'searchAppointments'])->name('appointments.search');
    Route::get('/queue', function () {
        return view('admin.appointments.queue');
    })->name('appointments.queue');
    Route::get('/queue', function () {
        return view('admin.appointments.queue');
    })->name('appointments.queue');
    Route::get('patient-view/{patient_id}', [AppointmentsController::class, 'show'])->name('patient.view');
    Route::post('/store-patient-vitals', [AppointmentsController::class, 'storePatientVitals'])->name('patient-vitals.store');
    Route::post('/store-patient-timeline', [AppointmentsController::class, 'storePatientTimeline'])->name('patient-timeline.store');
    Route::post('/update-patient-timeline', [AppointmentsController::class, 'updatePatientTimeline'])->name('patient-timeline.update');
    Route::get('/chart-data', [AppointmentsController::class, 'getChartData'])->name('chart.data');

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

Route::prefix('dutyroster')->group(function () {
    Route::get('/', [DutyRosterController::class, 'rosterListDetails'])->name('dutyroster');
    Route::post('/addRoster', [DutyRosterController::class, 'addRoster'])->name('dutyroster.addRoster');
    Route::put('/update/{id}', [DutyRosterController::class, 'updateRoster'])->name('dutyroster.update');
    Route::delete('/destroy/{id}', [DutyRosterController::class, 'destroyRoster'])->name('dutyroster.destroy');
    Route::get('/rosterShift', [DutyRosterController::class, 'showShift'])->name('dutyroster.Shift');
    Route::post('/addShift', [DutyRosterController::class, 'addShift'])->name('dutyroster.addShift');
    Route::put('/updateShift/{id}', [DutyRosterController::class, 'updateShift'])->name('dutyroster.updateShift');
    Route::delete('/destroyShift/{id}', [DutyRosterController::class, 'destroyShift'])->name('dutyroster.destroyShift');
    Route::get('/doctor', [DutyRosterController::class, 'doctorRoster'])->name('dutyroster.doctor');
    Route::post('/assignDoctor', [DutyRosterController::class, 'assignDoctor'])->name('dutyroster.assignDoctor');
    Route::put('/updateDoctorRoster', [DutyRosterController::class, 'updateDoctorRoster'])->name('dutyroster.updateDoctorRoster');
    Route::delete('/destroyDoctorRoster/{code}', [DutyRosterController::class, 'destroyDoctorRoster'])->name('dutyroster.destroyDoctorRoster');
    Route::get('/staff', [DutyRosterController::class, 'staffRoster'])->name('dutyroster.staff');
    Route::post('/assignStaff', [DutyRosterController::class, 'assignStaff'])->name('dutyroster.assignStaff');
    Route::put('/updateStaffRoster', [DutyRosterController::class, 'updateStaffRoster'])->name('dutyroster.updateStaffRoster');
    Route::delete('/destroyStaffRoster/{code}', [DutyRosterController::class, 'destroyStaffRoster'])->name('dutyroster.destroyStaffRoster');

    Route::get('/dutyroster/getDatesByShift', [DutyRosterController::class, 'getDatesByShift'])
        ->name('dutyroster.getDatesByShift');

    // Route::put('/update/{id}', [DutyRosterController::class, 'update'])->name('dutyroster.update');
    // Route::delete('/destroy', [DutyRosterController::class, 'destroy'])->name('dutyroster.destroy');
    // Route::get('/show/{id}', [DutyRosterController::class, 'show'])->name('dutyroster.show');
});
Route::prefix('ambulance')->group(function () {
});
Route::prefix('staffs')->group(function () {

    Route::get('/', [StaffController::class, 'index'])->name('staffs.index');
    Route::get('/create', [StaffController::class, 'create'])->name('createStaff');
    Route::post('/addStaff', [StaffController::class, 'store'])->name('staff.store');

    Route::get('/import', [StaffController::class, 'importStaff'])->name('Staff-import');
    Route::post('/bulkimport', [StaffController::class, 'importStaffExcel'])->name('Staffs.import');

    Route::get('/export-staffs', [StaffController::class, 'exportStaffExcel'])->name('staffs.export');

    Route::get('/edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/update/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/delete', [StaffController::class, 'bulkDelete'])->name('staffs.bulkDelete');
});
Route::prefix('bloodBank')->group(function () {

    Route::get('/donors', [BloodDonorController::class, 'index'])->name('donors.index');
    Route::post('/addDonors', [BloodDonorController::class, 'addDonors'])->name('bloodBank.addDoner');
    Route::put('/edit/{id}', [BloodDonorController::class, 'editDoner'])->name('bloodBank.editDoner');
    Route::put('/update/{id}', [BloodDonorController::class, 'updateDonor'])->name('bloodBank.updateDoner');
    Route::delete('/destroy/{id}', [BloodDonorController::class, 'destroyDonor'])->name('bloodBank.deleteDoner');

    Route::get('/issue', [BloodDonorController::class, 'bloodIssues'])->name('issue-blood.index');
    Route::post('/addDonors', [BloodDonorController::class, 'addDonors'])->name('bloodBank.addDoner');
    Route::put('/edit/{id}', [BloodDonorController::class, 'editDoner'])->name('bloodBank.editDoner');
    Route::put('/update/{id}', [BloodDonorController::class, 'updateDonor'])->name('bloodBank.updateDoner');
    Route::delete('/destroy/{id}', [BloodDonorController::class, 'destroyDonor'])->name('bloodBank.deleteDoner');

});
Route::prefix('certificate')->group(function () {
    Route::get('/', function () {
        return view('admin.certificate.certificate');
    })->name('certificate');
    Route::get('/generate_certificate', function () {
        return view('admin.certificate.generate_certificate');
    })->name('generate_certificate');

    Route::get('/generate_patient_id', function () {
        return view('admin.certificate.generate_patient_id');
    })->name('generate_patient_id');
    Route::get('/patient_id', function () {
        return view('admin.certificate.patient_id');
    })->name('patient_id');
    Route::get('/generate_staff_id', function () {
        return view('admin.certificate.generate_staff_id');
    })->name('generate_staff_id');
    Route::get('/staff_id', function () {
        return view('admin.certificate.staff_id');
    })->name('staff_id');
});
Route::prefix('doctor-details')->group(function () {

    Route::get('/', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/create', [DoctorController::class, 'create'])->name('createDoctor');
    Route::post('/addStaff', [DoctorController::class, 'store'])->name('doctor.store');

    Route::get('/import', [DoctorController::class, 'importDoctor'])->name('doctor-import');
    Route::post('/bulkimport', [DoctorController::class, 'importDoctorExcel'])->name('doctors.import');

    Route::get('/export-staffs', [DoctorController::class, 'exportDoctorExcel'])->name('doctors.export');

    Route::get('/edit/{id}', [DoctorController::class, 'edit'])->name('doctor.edit');
    Route::put('/update/{id}', [DoctorController::class, 'update'])->name('doctor.update');
    Route::delete('/delete', [DoctorController::class, 'bulkDelete'])->name('doctors.bulkDelete');
});
// Pharmacy Routes
Route::prefix('pharmacy')->group(function () {
    // Medicine Management - Index route
    Route::get('/', [App\Http\Controllers\PharmacyController::class, 'index'])->name('pharmacy.index');

    // Purchase Routes - MUST BE BEFORE {id} CATCH-ALL ROUTE
    Route::prefix('purchase')->group(function () {
        Route::get('/debug', function () {
            return 'Purchase route is working! Route order fixed.';
        });
        Route::get('/test-create', function () {
            $suppliers = \App\Models\MedicineSupplier::all();
            $medicines = \App\Models\Pharmacy::where('is_active', 'yes')->get();
            $categories = \App\Models\MedicineCategory::all();
            return view('admin.pharmacy.purchase.test', compact('suppliers', 'medicines', 'categories'));
        });
        Route::get('/', [App\Http\Controllers\PharmacyPurchaseController::class, 'index'])->name('pharmacy.purchase.index');
        Route::get('/create', [App\Http\Controllers\PharmacyPurchaseController::class, 'create'])->name('pharmacy.purchase.create');
        Route::post('/store', [App\Http\Controllers\PharmacyPurchaseController::class, 'store'])->name('pharmacy.purchase.store');
        Route::get('/search/orders', [App\Http\Controllers\PharmacyPurchaseController::class, 'search'])->name('pharmacy.purchase.search');
        Route::get('/api/medicines-by-category', [App\Http\Controllers\PharmacyPurchaseController::class, 'getMedicinesByCategory'])->name('pharmacy.purchase.api.medicines-by-category');
        Route::get('/{id}', [App\Http\Controllers\PharmacyPurchaseController::class, 'show'])->name('pharmacy.purchase.show');
        Route::get('/{id}/edit', [App\Http\Controllers\PharmacyPurchaseController::class, 'edit'])->name('pharmacy.purchase.edit');
        Route::put('/{id}', [App\Http\Controllers\PharmacyPurchaseController::class, 'update'])->name('pharmacy.purchase.update');
        Route::get('/{id}/print', [App\Http\Controllers\PharmacyPurchaseController::class, 'print'])->name('pharmacy.purchase.print');
    });

    // Stock Management - BEFORE {id} CATCH-ALL
    Route::get('/stock/below-min-level', [App\Http\Controllers\PharmacyController::class, 'belowMinLevel'])->name('pharmacy.below-min-level');
    Route::get('/stock/needs-reorder', [App\Http\Controllers\PharmacyController::class, 'needsReorder'])->name('pharmacy.needs-reorder');
    Route::get('/stock/info/{id}', [App\Http\Controllers\PharmacyController::class, 'getStockInfo'])->name('pharmacy.stock-info');

    // Import - BEFORE {id} CATCH-ALL
    Route::get('/import/medicines', [App\Http\Controllers\PharmacyController::class, 'import'])->name('pharmacy.import');
    Route::post('/import/medicines', [App\Http\Controllers\PharmacyController::class, 'import'])->name('pharmacy.import.store');

    // API Routes - BEFORE {id} CATCH-ALL
    Route::get('/api/medicines', [App\Http\Controllers\PharmacyController::class, 'getMedicines'])->name('pharmacy.api.medicines');
    Route::get('/api/batches/{pharmacyId}', [App\Http\Controllers\PharmacyBillingController::class, 'getMedicineBatches'])->name('pharmacy.api.batches');
    Route::get('/api/batch-details', [App\Http\Controllers\PharmacyBillingController::class, 'getBatchDetails'])->name('pharmacy.api.batch-details');
    Route::get('/api/patient-prescriptions/{patientId}', [App\Http\Controllers\PharmacyBillingController::class, 'getPatientPrescriptions'])->name('pharmacy.api.patient-prescriptions');

    // Medicine Management
    Route::get('/create', [App\Http\Controllers\PharmacyController::class, 'create'])->name('pharmacy.create');
    Route::post('/store', [App\Http\Controllers\PharmacyController::class, 'store'])->name('pharmacy.store');
    Route::get('/{id}', [App\Http\Controllers\PharmacyController::class, 'show'])->name('pharmacy.show');
    Route::get('/{id}/edit', [App\Http\Controllers\PharmacyController::class, 'edit'])->name('pharmacy.edit');
    Route::put('/{id}', [App\Http\Controllers\PharmacyController::class, 'update'])->name('pharmacy.update');
    Route::delete('/{id}', [App\Http\Controllers\PharmacyController::class, 'destroy'])->name('pharmacy.destroy');
});

// Test routes
Route::get('/test-pharmacy', function () {
    return 'Pharmacy test route working!';
});

Route::get('/test-pharmacy-controller', [App\Http\Controllers\PharmacyBillingController::class, 'test']);

// Pharmacy Billing Routes - Simplified
Route::get('/pharmacy-billing', [App\Http\Controllers\PharmacyBillingController::class, 'index'])->name('pharmacy.billing.index');
Route::get('/pharmacy-billing/create', [App\Http\Controllers\PharmacyBillingController::class, 'create'])->name('pharmacy.billing.create');
Route::post('/pharmacy-billing/store', [App\Http\Controllers\PharmacyBillingController::class, 'store'])->name('pharmacy.billing.store');
Route::get('/pharmacy-billing/{id}', [App\Http\Controllers\PharmacyBillingController::class, 'show'])->name('pharmacy.billing.show');
Route::get('/pharmacy-billing/{id}/edit', [App\Http\Controllers\PharmacyBillingController::class, 'edit'])->name('pharmacy.billing.edit');
Route::put('/pharmacy-billing/{id}', [App\Http\Controllers\PharmacyBillingController::class, 'update'])->name('pharmacy.billing.update');
Route::delete('/pharmacy-billing/{id}', [App\Http\Controllers\PharmacyBillingController::class, 'destroy'])->name('pharmacy.billing.destroy');
Route::get('/pharmacy-billing/{id}/print', [App\Http\Controllers\PharmacyBillingController::class, 'print'])->name('pharmacy.billing.print');
Route::get('/pharmacy-billing-search', [App\Http\Controllers\PharmacyBillingController::class, 'search'])->name('pharmacy.billing.search');

// Pharmacy Company Routes
Route::prefix('pharmacy/company')->group(function () {
    Route::get('/', [PharmacyCompanyController::class, 'index'])->name('pharmacy.company.index');
    Route::post('/store', [PharmacyCompanyController::class, 'store'])->name('pharmacy.company.store');
    Route::put('/update', [PharmacyCompanyController::class, 'update'])->name('pharmacy.company.update');
    Route::delete('/destroy', [PharmacyCompanyController::class, 'destroy'])->name('pharmacy.company.destroy');
});

// Pathology Test Routes
Route::prefix('pathology/test')->group(function () {
    Route::get('/', [PathologyTestController::class, 'index'])->name('pathology.test.index');
    Route::get('/create', [PathologyTestController::class, 'create'])->name('pathology.test.create');
    Route::post('/store', [PathologyTestController::class, 'store'])->name('pathology.test.store');
    Route::put('/tpa-charge/update', [PathologyTestController::class, 'updateTpaCharge'])->name('pathology.test.update-tpa-charge');
    Route::get('/{id}', [PathologyTestController::class, 'show'])->name('pathology.test.show');
    Route::get('/{id}/edit', [PathologyTestController::class, 'edit'])->name('pathology.test.edit');
    Route::put('/{id}', [PathologyTestController::class, 'update'])->name('pathology.test.update');
    Route::delete('/{id}', [PathologyTestController::class, 'destroy'])->name('pathology.test.destroy');

});

// Pathology Test API Routes
Route::prefix('pathology/api')->group(function () {
    Route::get('/charge-names', [PathologyTestController::class, 'getChargeNames'])->name('pathology.api.charge-names');
    Route::get('/charge-details', [PathologyTestController::class, 'getChargeDetails'])->name('pathology.api.charge-details');
});

// Pathology Billing Routes
Route::prefix('pathology/billing')->group(function () {
    Route::get('/', [PathologyBillingController::class, 'index'])->name('pathology.billing.index');
    Route::get('/create', [PathologyBillingController::class, 'create'])->name('pathology.billing.create');
    Route::post('/store', [PathologyBillingController::class, 'store'])->name('pathology.billing.store');
    Route::get('/{id}', [PathologyBillingController::class, 'show'])->name('pathology.billing.show');
    Route::get('/{id}/edit', [PathologyBillingController::class, 'edit'])->name('pathology.billing.edit');
    Route::put('/{id}', [PathologyBillingController::class, 'update'])->name('pathology.billing.update');
    Route::delete('/{id}', [PathologyBillingController::class, 'destroy'])->name('pathology.billing.destroy');
});

// Pathology Billing API Routes
Route::prefix('pathology/billing/api')->group(function () {
    Route::get('/patient-prescriptions/{patientId}', [PathologyBillingController::class, 'getPatientPrescriptions'])->name('pathology.billing.api.patient-prescriptions');
    Route::get('/test-details', [PathologyBillingController::class, 'getTestDetails'])->name('pathology.billing.api.test-details');
    Route::get('/patient-tpas/{patientId}', [PathologyBillingController::class, 'getPatientTpas'])->name('pathology.billing.api.patient-tpas');
    Route::get('/tpa-charge', [PathologyBillingController::class, 'getTpaCharge'])->name('pathology.billing.api.tpa-charge');
});

// Radiology Test Routes
Route::prefix('radiology/test')->group(function () {
    Route::get('/', [RadiologyTestController::class, 'index'])->name('radiology.test.index');
    Route::get('/create', [RadiologyTestController::class, 'create'])->name('radiology.test.create');
    Route::post('/store', [RadiologyTestController::class, 'store'])->name('radiology.test.store');
    Route::put('/tpa-charge/update', [RadiologyTestController::class, 'updateTpaCharge'])->name('radiology.test.update-tpa-charge');
    Route::get('/{id}', [RadiologyTestController::class, 'show'])->name('radiology.test.show');
    Route::get('/{id}/edit', [RadiologyTestController::class, 'edit'])->name('radiology.test.edit');
    Route::put('/{id}', [RadiologyTestController::class, 'update'])->name('radiology.test.update');
    Route::delete('/{id}', [RadiologyTestController::class, 'destroy'])->name('radiology.test.destroy');
});

// Radiology Test API Routes
Route::prefix('radiology/api')->group(function () {
    Route::get('/charge-names', [RadiologyTestController::class, 'getChargeNames'])->name('radiology.api.charge-names');
    Route::get('/charge-details', [RadiologyTestController::class, 'getChargeDetails'])->name('radiology.api.charge-details');
});

// Radiology Billing Routes
Route::prefix('radiology/billing')->group(function () {
    Route::get('/', [RadiologyBillingController::class, 'index'])->name('radiology.billing.index');
    Route::get('/create', [RadiologyBillingController::class, 'create'])->name('radiology.billing.create');
    Route::post('/store', [RadiologyBillingController::class, 'store'])->name('radiology.billing.store');
    Route::get('/{id}', [RadiologyBillingController::class, 'show'])->name('radiology.billing.show');
    Route::get('/{id}/edit', [RadiologyBillingController::class, 'edit'])->name('radiology.billing.edit');
    Route::put('/{id}', [RadiologyBillingController::class, 'update'])->name('radiology.billing.update');
    Route::delete('/{id}', [RadiologyBillingController::class, 'destroy'])->name('radiology.billing.destroy');
});

// Radiology Billing API Routes
Route::prefix('radiology/billing/api')->group(function () {
    Route::get('/patient-prescriptions/{patientId}', [RadiologyBillingController::class, 'getPatientPrescriptions'])->name('radiology.billing.api.patient-prescriptions');
    Route::get('/test-details', [RadiologyBillingController::class, 'getTestDetails'])->name('radiology.billing.api.test-details');
    Route::get('/patient-tpas/{patientId}', [RadiologyBillingController::class, 'getPatientTpas'])->name('radiology.billing.api.patient-tpas');
    Route::get('/tpa-charge', [RadiologyBillingController::class, 'getTpaCharge'])->name('radiology.billing.api.tpa-charge');
});

// Pharmacy Masters Routes
Route::prefix('setup')->group(function () {
    // Medicine Category
    Route::get('/medicine-category', [SetupMedicineCategoryController::class, 'index'])->name('setup.medicine-category');
    Route::post('/medicine-category/store', [SetupMedicineCategoryController::class, 'store'])->name('setup.medicine-category.store');
    Route::put('/medicine-category/update/{id}', [SetupMedicineCategoryController::class, 'update'])->name('setup.medicine-category.update');
    Route::delete('/medicine-category/destroy/{id}', [SetupMedicineCategoryController::class, 'destroy'])->name('setup.medicine-category.destroy');

    // Medicine Supplier
    Route::get('/medicine-supplier', [SetupMedicineSupplierController::class, 'index'])->name('setup.medicine-supplier');
    Route::post('/medicine-supplier/store', [SetupMedicineSupplierController::class, 'store'])->name('setup.medicine-supplier.store');
    Route::put('/medicine-supplier/update/{id}', [SetupMedicineSupplierController::class, 'update'])->name('setup.medicine-supplier.update');
    Route::delete('/medicine-supplier/destroy/{id}', [SetupMedicineSupplierController::class, 'destroy'])->name('setup.medicine-supplier.destroy');

    // Medicine Dosage
    Route::get('/medicine-dosage', [SetupMedicineDosageController::class, 'index'])->name('setup.medicine-dosage');
    Route::post('/medicine-dosage/store', [SetupMedicineDosageController::class, 'store'])->name('setup.medicine-dosage.store');
    Route::put('/medicine-dosage/update/{id}', [SetupMedicineDosageController::class, 'update'])->name('setup.medicine-dosage.update');
    Route::delete('/medicine-dosage/destroy/{id}', [SetupMedicineDosageController::class, 'destroy'])->name('setup.medicine-dosage.destroy');

    // Dose Interval
    Route::get('/dose-interval', [DoseIntervalController::class, 'index'])->name('setup.dose-interval');
    Route::post('/dose-interval/store', [DoseIntervalController::class, 'store'])->name('setup.dose-interval.store');
    Route::put('/dose-interval/update/{id}', [DoseIntervalController::class, 'update'])->name('setup.dose-interval.update');
    Route::delete('/dose-interval/destroy/{id}', [DoseIntervalController::class, 'destroy'])->name('setup.dose-interval.destroy');

    // Dose Duration
    Route::get('/dose-duration', [DoseDurationController::class, 'index'])->name('setup.dose-duration');
    Route::post('/dose-duration/store', [DoseDurationController::class, 'store'])->name('setup.dose-duration.store');
    Route::put('/dose-duration/update/{id}', [DoseDurationController::class, 'update'])->name('setup.dose-duration.update');
    Route::delete('/dose-duration/destroy/{id}', [DoseDurationController::class, 'destroy'])->name('setup.dose-duration.destroy');

    // Medicine Unit
    Route::get('/medicine-unit', [SetupMedicineUnitController::class, 'index'])->name('setup.medicine-unit');
    Route::post('/medicine-unit/store', [SetupMedicineUnitController::class, 'store'])->name('setup.medicine-unit.store');
    Route::put('/medicine-unit/update/{id}', [SetupMedicineUnitController::class, 'update'])->name('setup.medicine-unit.update');
    Route::delete('/medicine-unit/destroy/{id}', [SetupMedicineUnitController::class, 'destroy'])->name('setup.medicine-unit.destroy');

    // Medicine Company
    Route::get('/medicine-company', [SetupMedicineCompanyController::class, 'index'])->name('setup.medicine-company');
    Route::post('/medicine-company/store', [SetupMedicineCompanyController::class, 'store'])->name('setup.medicine-company.store');
    Route::put('/medicine-company/update/{id}', [SetupMedicineCompanyController::class, 'update'])->name('setup.medicine-company.update');
    Route::delete('/medicine-company/destroy/{id}', [SetupMedicineCompanyController::class, 'destroy'])->name('setup.medicine-company.destroy');

    // Medicine Group
    Route::get('/medicine-group', [SetupMedicineGroupController::class, 'index'])->name('setup.medicine-group');
    Route::post('/medicine-group/store', [SetupMedicineGroupController::class, 'store'])->name('setup.medicine-group.store');
    Route::put('/medicine-group/update/{id}', [SetupMedicineGroupController::class, 'update'])->name('setup.medicine-group.update');
    Route::delete('/medicine-group/destroy/{id}', [SetupMedicineGroupController::class, 'destroy'])->name('setup.medicine-group.destroy');
});

Route::get('/getMedicineCategories', [MedicineController::class, 'getCategories'])->name('getMedicineCategories');
Route::get('/getMedicines/{categoryId}', [MedicineController::class, 'getMedicines'])->name('getMedicines');
Route::get('/getDoses/{categoryId}', [MedicineController::class, 'getDoses'])->name('getDoses');
Route::get('/getDoseIntervals', [MedicineController::class, 'getIntervals'])->name('getDoseIntervals');
Route::get('/getDoseDurations', [MedicineController::class, 'getDurations'])->name('getDoseDurations');

Route::get('/blood_bank_status', function () {
    return view('admin.blood-bank-doner.blood_bank_status');
})->name('blood_bank_status');
Route::get('/pathology_test', [ExcelImportController::class, 'importPathology'])->name('pathology.test.import');
Route::post('/pathology_import', [ExcelImportController::class, 'importPathologyExcel'])->name('pathology.import');
Route::get('/pathology_test_export', [ExcelImportController::class, 'exportPathologyTestExcel'])->name('pathologyTests.export');

Route::get('/radiology_test', [ExcelImportController::class, 'importRadiology'])->name('radiology.test.import');
Route::post('/radiology_import', [ExcelImportController::class, 'importRadiologyExcel'])->name('radiology.import');
Route::get('/radiology_test_export', [ExcelImportController::class, 'exportRadiologyTestExcel'])->name('radiologyTests.export');

Route::get('/finance', function () {
    return view('admin.reports.finance.index');
})->name('finance');
Route::get('/reports/dailyTransactionReport', [TransactionReportController::class, 'dailyTransactionReport'])->name('reports.daily.transaction');

Route::get('/allTransactionReport', function () {
    return view('admin.reports.finance.all-transaction-report');
})->name('allTransactionReport');
Route::get('/incomeReport', function () {
    return view('admin.reports.finance.income-report');
})->name('incomeReport');
Route::get('/incomeGroupReport', function () {
    return view('admin.reports.finance.income-group-report');
})->name('incomeGroupReport');
Route::get('/expenseReport', function () {
    return view('admin.reports.finance.expense-report');
})->name('expenseReport');
Route::get('/expenseGroupReport', function () {
    return view('admin.reports.finance.expense-group-report');
})->name('expenseGroupReport');
Route::get('/patientBillReport', function () {
    return view('admin.reports.finance.patient-bill-report');
})->name('patientBillReport');
Route::get('/processingTransactionReport', function () {
    return view('admin.reports.finance.processing-transaction-report');
})->name('processingTransactionReport');


// OPD
Route::get('/opdReportsIndex', function () {
    return view('admin.reports.opd.index');
})->name('opdReportsIndex');
Route::get('/opdReports', function () {
    return view('admin.reports.opd.opd_reports');
})->name('opdReports');
Route::get('/opdBalanceReports', function () {
    return view('admin.reports.opd.opd_balance_reports');
})->name('opdBalanceReports');
Route::get('/opdDischargePatient', function () {
    return view('admin.reports.opd.opd_discharge_patient');
})->name('opdDischargePatient');

// ipd
Route::get('/ipdReportsIndex', function () {
    return view('admin.reports.ipd.index');
})->name('ipdReportsIndex');
Route::get('/ipdReports', function () {
    return view('admin.reports.ipd.ipd_reports');
})->name('ipdReports');
Route::get('/ipdBalanceReports', function () {
    return view('admin.reports.ipd.ipd_balance_reports');
})->name('ipdBalanceReports');
Route::get('/ipdDischargePatient', function () {
    return view('admin.reports.ipd.ipd_discharge_patient');
})->name('ipdDischargePatient');