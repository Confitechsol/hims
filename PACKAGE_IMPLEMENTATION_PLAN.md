# Package Management Implementation Plan for HIMS

## Overview
This document outlines the implementation plan for creating a package management system that allows hospitals to create predefined packages with various hospital charges and apply them to IPD patients.

## Database Schema Design

### 1. `packages` Table
Stores the main package information.

**Fields:**
- `id` (primary key)
- `hospital_id` (string, 8)
- `branch_id` (string, 8, nullable)
- `name` (string, 255) - Package name (e.g., "PACKAGE (B/L INGUNIAL HERNIOPLASTY)")
- `account_head` (string, 100) - Account head (e.g., "CASH")
- `gst_amount` (decimal, 10, 2) - GST amount
- `package_rate` (decimal, 10, 2) - Total package rate
- `description` (text, nullable) - Package description
- `status` (string, 50) - Active/Inactive
- `is_active` (boolean, default true)
- `created_by` (unsignedBigInteger, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

### 2. `package_charges` Table
Stores the charges included in each package.

**Fields:**
- `id` (primary key)
- `package_id` (unsignedBigInteger, foreign key to packages)
- `charge_type` (string, 100) - Type of charge (e.g., "Bed Charges", "O.T. Charges", "Doctor Charges", etc.)
- `charge_category_id` (unsignedBigInteger, nullable, foreign key to charge_categories)
- `charge_id` (unsignedBigInteger, nullable, foreign key to charges)
- `amount` (decimal, 10, 2) - Amount for this charge type
- `is_percentage` (boolean, default false) - If true, amount is percentage of package rate
- `display_order` (integer) - Order of display
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Charge Types:**
- Other Charges
- Bed Charges
- O.T. Charges (Operating Theater)
- Doctor Charges
- Diagnostic Charges
- Medicine Charges
- Service Charges

### 3. `package_excludes` Table (Optional)
Stores charges explicitly excluded from the package.

**Fields:**
- `id` (primary key)
- `package_id` (unsignedBigInteger, foreign key to packages)
- `charge_category_id` (unsignedBigInteger, nullable, foreign key to charge_categories)
- `charge_id` (unsignedBigInteger, nullable, foreign key to charges)
- `description` (text, nullable) - Description of exclusion
- `created_at` (timestamp)
- `updated_at` (timestamp)

### 4. `ipd_packages` Table
Links IPD patients to packages.

**Fields:**
- `id` (primary key)
- `ipd_id` (unsignedBigInteger, foreign key to ipd_details)
- `package_id` (unsignedBigInteger, foreign key to packages)
- `applied_date` (date) - Date package was applied
- `applied_by` (unsignedBigInteger, nullable, foreign key to users)
- `package_rate` (decimal, 10, 2) - Rate at time of application
- `discount_percentage` (decimal, 5, 2, default 0) - Discount applied
- `discount_amount` (decimal, 10, 2, default 0) - Discount amount
- `gst_amount` (decimal, 10, 2) - GST amount
- `final_amount` (decimal, 10, 2) - Final amount after discount and GST
- `status` (string, 50) - Applied/Completed/Cancelled
- `note` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

## Models

### 1. Package Model
- Relationships:
  - `hasMany(PackageCharge::class)`
  - `hasMany(PackageExclude::class)`
  - `hasMany(IpdPackage::class)`
  - `belongsTo(User::class, 'created_by')`

### 2. PackageCharge Model
- Relationships:
  - `belongsTo(Package::class)`
  - `belongsTo(ChargeCategory::class)`
  - `belongsTo(Charge::class)`

### 3. PackageExclude Model
- Relationships:
  - `belongsTo(Package::class)`
  - `belongsTo(ChargeCategory::class)`
  - `belongsTo(Charge::class)`

### 4. IpdPackage Model
- Relationships:
  - `belongsTo(IpdDetail::class, 'ipd_id')`
  - `belongsTo(Package::class)`
  - `belongsTo(User::class, 'applied_by')`

## Controllers

### PackageController
**Location:** `app/Http/Controllers/Setup/PackageController.php`

**Methods:**
- `index()` - List all packages
- `create()` - Show create form
- `store(Request $request)` - Save new package
- `show($id)` - Show package details
- `edit($id)` - Show edit form
- `update(Request $request, $id)` - Update package
- `destroy($id)` - Delete package
- `getPackageCharges($id)` - API endpoint to get package charges (JSON)

## Views

### 1. Package Index (`resources/views/admin/setup/packages/index.blade.php`)
- List view with search and filter
- Action buttons: New, Edit, Delete, View
- Display: Name, Account Head, Package Rate, Status

### 2. Package Form (`resources/views/admin/setup/packages/form.blade.php`)
- Package Details Section:
  - Name (text input)
  - Account Head (select/dropdown)
  - GST Amount (number input)
  - Package Rate (number input, calculated from charges)
  - Description (textarea)
  - Status (select: Active/Inactive)

- Service Includes Section:
  - Dynamic rows for each charge type:
    - Other Charges
    - Bed Charges
    - O.T. Charges
    - Doctor Charges
    - Diagnostic Charges
    - Medicine Charges
    - Service Charges
  - Each row: Charge Type (read-only), Amount (number input)
  - Add/Remove rows functionality
  - Auto-calculate Package Rate

- Service Excludes Section (Optional):
  - Dynamic list of excluded charges
  - Add/Remove functionality

- Action Buttons:
  - New (clear form)
  - Edit (load existing)
  - Save (create/update)
  - Delete
  - Undo (reset form)
  - Print
  - Exit

### 3. Package List View (Left Panel)
- Searchable list of packages
- Click to select and load in form

## Integration with IPD Management

### 1. Add Package Selection to IPD Patient Form
**Location:** `resources/views/components/modals/ipd-create-modal.blade.php` or IPD edit form

**Fields to Add:**
- Package dropdown (select package)
- Display applied package details
- Option to remove package

### 2. Apply Package Charges to IPD Patient
When a package is applied to an IPD patient:

1. Create record in `ipd_packages` table
2. Create corresponding charges in `ipd_charges` or `patient_charges` table:
   - For each charge in `package_charges`:
     - Create charge entry linked to IPD patient
     - Set amounts based on package charge amounts
     - Mark as package charge

3. Update IPD detail totals

### 3. Package Charge Application Logic
**Location:** `app/Http/Controllers/Modules/IpdController.php`

**New Method:** `applyPackage(Request $request, $ipdId)`
- Validate package selection
- Check if package is already applied
- Create `IpdPackage` record
- Create charge entries for each package charge
- Update IPD totals
- Return success/error response

## Routes

```php
// Package Management Routes
Route::prefix('setup')->group(function () {
    Route::resource('packages', PackageController::class);
    Route::get('packages/{id}/charges', [PackageController::class, 'getPackageCharges'])->name('packages.charges');
});

// IPD Package Application Routes
Route::prefix('ipd')->group(function () {
    Route::post('{id}/apply-package', [IpdController::class, 'applyPackage'])->name('ipd.apply-package');
    Route::delete('{id}/remove-package', [IpdController::class, 'removePackage'])->name('ipd.remove-package');
});
```

## API Endpoints

### Get Package Charges
`GET /setup/packages/{id}/charges`
Returns JSON with package charges structure.

### Apply Package to IPD
`POST /ipd/{id}/apply-package`
Request: `{ package_id: 1, discount_percentage: 0, note: "..." }`
Response: Success/Error message

## Implementation Steps

1. ✅ Create database migrations
2. ✅ Create Eloquent models
3. ✅ Create PackageController
4. ✅ Create package management views
5. ✅ Add routes
6. ✅ Integrate package selection into IPD forms
7. ✅ Implement package charge application logic
8. ✅ Add package display in IPD patient view
9. ✅ Testing and refinement

## UI/UX Considerations

1. **Package Form:**
   - Left panel: Package list (searchable)
   - Center: Package detail form
   - Right panel: Can show related information or patient details
   - Auto-calculation of package rate
   - Real-time validation

2. **IPD Integration:**
   - Package dropdown in IPD form
   - Show package details when selected
   - Display package charges in IPD charges section
   - Option to modify individual charges (if allowed)

3. **Charge Types:**
   - Predefined charge types for consistency
   - Allow linking to actual charge categories/charges for detailed tracking
   - Support both fixed amounts and percentage-based charges

## Business Logic

1. **Package Rate Calculation:**
   - Sum of all charge amounts in "Service Includes"
   - Can be manually overridden
   - Auto-update when charges change

2. **Package Application:**
   - One package per IPD patient (can be changed)
   - When applied, create all package charges
   - Charges can be modified individually if needed
   - Track original package rate vs. applied rate

3. **Package Modifications:**
   - If package is modified after application, existing applications remain unchanged
   - New applications use updated package

4. **Package Exclusions:**
   - Charges listed in exclusions are not automatically applied
   - Can be added manually if needed

## Future Enhancements

1. Package templates/versions
2. Package validity dates
3. Package discounts/promotions
4. Package reports and analytics
5. Integration with billing system
6. Package approval workflow
7. Multi-package support per patient

