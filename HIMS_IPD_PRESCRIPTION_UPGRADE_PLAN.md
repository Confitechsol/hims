# HIMS IPD Prescription Module Upgrade Plan

## Objective
Upgrade the HIMS IPD Prescription module to match the Hospital system's functionality while maintaining Laravel best practices and modern architecture.

---

## Phase 1: Database Structure Enhancement

### 1.1 Create Migration for `ipd_prescription_test` Table
**File**: `database/migrations/YYYY_MM_DD_HHMMSS_create_ipd_prescription_test_table.php`

**Purpose**: Normalize pathology/radiology test storage

**Table Structure**:
```php
Schema::create('ipd_prescription_test', function (Blueprint $table) {
    $table->id();
    $table->string('hospital_id', 8);
    $table->string('branch_id', 8);
    $table->unsignedBigInteger('ipd_prescription_id')->index();
    $table->unsignedBigInteger('pathology_id')->nullable()->index();
    $table->unsignedBigInteger('radiology_id')->nullable()->index();
    $table->timestamps();
    
    $table->foreign('ipd_prescription_id')
          ->references('id')
          ->on('ipd_prescription')
          ->onDelete('cascade');
    $table->foreign('pathology_id')
          ->references('id')
          ->on('pathology')
          ->onDelete('set null');
    $table->foreign('radiology_id')
          ->references('id')
          ->on('radio')
          ->onDelete('set null');
});
```

### 1.2 Update `ipd_prescription` Table Migration
**File**: Modify existing migration or create new migration

**Changes**:
- Remove `pathology_id` and `radiology_id` string columns
- Add `prescribe_by` field (unsignedBigInteger, nullable)
- Add `attachment` field (string, nullable)
- Add `attachment_name` field (string, nullable)
- Add `visit_details_id` field (unsignedBigInteger, nullable) for future OPD integration

### 1.3 Data Migration Script
**File**: `database/migrations/YYYY_MM_DD_HHMMSS_migrate_ipd_prescription_tests.php`

**Purpose**: Migrate existing comma-separated test IDs to normalized table

**Logic**:
```php
// For each ipd_prescription record:
// 1. Parse comma-separated pathology_id string
// 2. Create ipd_prescription_test records for each pathology_id
// 3. Parse comma-separated radiology_id string
// 4. Create ipd_prescription_test records for each radiology_id
```

---

## Phase 2: Model Updates

### 2.1 Update `IpdPrescription` Model
**File**: `app/Models/IpdPrescription.php`

**Add Relationships**:
```php
// Relationship with tests
public function tests()
{
    return $this->hasMany(IpdPrescriptionTest::class, 'ipd_prescription_id');
}

// Relationship with pathology tests
public function pathologyTests()
{
    return $this->hasMany(IpdPrescriptionTest::class, 'ipd_prescription_id')
                ->whereNotNull('pathology_id');
}

// Relationship with radiology tests
public function radiologyTests()
{
    return $this->hasMany(IpdPrescriptionTest::class, 'ipd_prescription_id')
                ->whereNotNull('radiology_id');
}

// Relationship with prescribing doctor
public function prescribedBy()
{
    return $this->belongsTo(Doctor::class, 'prescribe_by');
}
```

**Update Fillable**:
```php
protected $fillable = [
    'prescription_number',
    'ipd_id',
    'visit_details_id',  // NEW
    'header_note',
    'footer_note',
    'finding_description',
    'finding_categories',
    'findings',
    'is_finding_print',
    'date',
    'notification_to',
    'prescribed_by',      // NEW
    'attachment',         // NEW
    'attachment_name',    // NEW
];
```

### 2.2 Create `IpdPrescriptionTest` Model
**File**: `app/Models/IpdPrescriptionTest.php`

**Structure**:
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpdPrescriptionTest extends Model
{
    protected $table = 'ipd_prescription_test';
    
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'ipd_prescription_id',
        'pathology_id',
        'radiology_id',
    ];
    
    // Relationships
    public function prescription()
    {
        return $this->belongsTo(IpdPrescription::class, 'ipd_prescription_id');
    }
    
    public function pathology()
    {
        return $this->belongsTo(Pathology::class, 'pathology_id');
    }
    
    public function radiology()
    {
        return $this->belongsTo(Radio::class, 'radiology_id');
    }
}
```

---

## Phase 3: Controller Enhancements

### 3.1 Update `storePrescription()` Method
**File**: `app/Http/Controllers/Modules/IpdController.php`

**Changes**:
1. Add validation for `prescribe_by` field
2. Handle file upload for attachments
3. Store tests in normalized table instead of comma-separated strings
4. Add custom fields support (if custom fields module exists)

**Updated Logic**:
```php
public function storePrescription(Request $request)
{
    $validated = $request->validate([
        'ipd_id' => 'required|exists:ipd_details,id',
        'prescribe_by' => 'required|exists:doctors,id',  // NEW
        'header_note' => 'nullable|string',
        'footer_note' => 'nullable|string',
        'finding_description' => 'nullable|string',
        'finding_print' => 'nullable|string',
        'finding_type' => 'nullable|array',
        'findings' => 'nullable|array',
        'pathology' => 'nullable|array',
        'pathology.*' => 'exists:pathology,id',
        'radiology' => 'nullable|array',
        'radiology.*' => 'exists:radio,id',
        'medicines' => 'nullable|array',
        'document' => 'nullable|file|max:10240',  // NEW
        // ... other validations
    ]);
    
    DB::beginTransaction();
    try {
        // Handle file upload
        $attachment = null;
        $attachmentName = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $attachmentName = $file->getClientOriginalName();
            $attachment = $file->store('prescription_documents', 'public');
        }
        
        // Create prescription
        $prescription = IpdPrescription::create([
            'prescription_number' => $this->generatePrescriptionNumber(),
            'ipd_id' => $validated['ipd_id'],
            'prescribe_by' => $validated['prescribe_by'],  // NEW
            'header_note' => $request->header_note,
            'footer_note' => $request->footer_note,
            'finding_description' => $request->finding_description,
            'is_finding_print' => $request->finding_print ?? 'no',
            'finding_categories' => implode(", ", $findingTypes),
            'findings' => implode(", ", $findings),
            'notification_to' => implode(", ", $notification_to),
            'attachment' => $attachment,           // NEW
            'attachment_name' => $attachmentName,   // NEW
            'date' => Carbon::now()->toDateString(),
        ]);
        
        // Store tests in normalized table
        if (!empty($validated['pathology'])) {
            foreach ($validated['pathology'] as $pathologyId) {
                IpdPrescriptionTest::create([
                    'ipd_prescription_id' => $prescription->id,
                    'pathology_id' => $pathologyId,
                    'hospital_id' => Auth::user()->hospital_id ?? '',
                    'branch_id' => Auth::user()->branch_id ?? '',
                ]);
            }
        }
        
        if (!empty($validated['radiology'])) {
            foreach ($validated['radiology'] as $radiologyId) {
                IpdPrescriptionTest::create([
                    'ipd_prescription_id' => $prescription->id,
                    'radiology_id' => $radiologyId,
                    'hospital_id' => Auth::user()->hospital_id ?? '',
                    'branch_id' => Auth::user()->branch_id ?? '',
                ]);
            }
        }
        
        // Store medicines (existing logic)
        // ...
        
        DB::commit();
        return redirect()->back()->with('success', 'Prescription created successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
```

### 3.2 Add `editPrescription()` Method
**File**: `app/Http/Controllers/Modules/IpdController.php`

**Purpose**: Load prescription data for editing

**Implementation**:
```php
public function editPrescription($id)
{
    $prescription = IpdPrescription::with([
        'ipd.patient',
        'ipd.doctor',
        'prescribedBy',
        'tests.pathology',
        'tests.radiology',
        'medicines.pharmacy',
        'medicines.medicineDosage',
        'medicines.doseInterval',
        'medicines.doseDuration'
    ])->findOrFail($id);
    
    // Load dropdown data
    $doctors = Doctor::where('is_active', true)->get();
    $pathologyTests = Pathology::all();
    $radiologyTests = Radio::all();
    $findingCategories = FindingCategory::all();
    
    return response()->json([
        'prescription' => $prescription,
        'doctors' => $doctors,
        'pathologyTests' => $pathologyTests,
        'radiologyTests' => $radiologyTests,
        'findingCategories' => $findingCategories,
    ]);
}
```

### 3.3 Add `updatePrescription()` Method
**File**: `app/Http/Controllers/Modules/IpdController.php`

**Purpose**: Update existing prescription

**Implementation**:
```php
public function updatePrescription(Request $request, $id)
{
    $prescription = IpdPrescription::findOrFail($id);
    
    $validated = $request->validate([
        // Same validation as store
    ]);
    
    DB::beginTransaction();
    try {
        // Handle file upload if new file provided
        if ($request->hasFile('document')) {
            // Delete old file if exists
            if ($prescription->attachment) {
                Storage::disk('public')->delete($prescription->attachment);
            }
            // Upload new file
            $file = $request->file('document');
            $prescription->attachment_name = $file->getClientOriginalName();
            $prescription->attachment = $file->store('prescription_documents', 'public');
        }
        
        // Update prescription
        $prescription->update([
            'prescribe_by' => $validated['prescribe_by'],
            'header_note' => $request->header_note,
            'footer_note' => $request->footer_note,
            // ... other fields
        ]);
        
        // Update tests (delete old, insert new)
        IpdPrescriptionTest::where('ipd_prescription_id', $prescription->id)->delete();
        
        // Insert new tests
        if (!empty($validated['pathology'])) {
            foreach ($validated['pathology'] as $pathologyId) {
                IpdPrescriptionTest::create([
                    'ipd_prescription_id' => $prescription->id,
                    'pathology_id' => $pathologyId,
                    // ...
                ]);
            }
        }
        
        // Similar for radiology and medicines
        
        DB::commit();
        return redirect()->back()->with('success', 'Prescription updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
```

### 3.4 Add `showPrescription()` Method
**File**: `app/Http/Controllers/Modules/IpdController.php`

**Purpose**: View prescription details

### 3.5 Add `deletePrescription()` Method
**File**: `app/Http/Controllers/Modules/IpdController.php`

**Purpose**: Delete prescription with cascade delete

### 3.6 Add `printPrescription()` Method
**File**: `app/Http/Controllers/Modules/IpdController.php`

**Purpose**: Generate printable prescription view

---

## Phase 4: View Updates

### 4.1 Update Add Prescription Modal
**File**: `resources/views/components/modals/add-prescription-modal.blade.php`

**Additions**:
1. **Prescribe By Field**:
```blade
<div class="col-md-3">
    <label class="form-label">Prescribe By <span class="text-danger">*</span></label>
    <select name="prescribe_by" id="prescribe_by" class="form-select" required>
        <option value="">Select Doctor</option>
        @foreach($doctors as $doctor)
            <option value="{{ $doctor->id }}">
                Dr. {{ $doctor->name }} {{ $doctor->surname }} ({{ $doctor->doctor_id }})
            </option>
        @endforeach
    </select>
</div>
```

2. **File Upload Field**:
```blade
<div class="col-sm-12 mt-2">
    <label class="form-label">Attachment</label>
    <input type="file" class="form-control" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
    <small class="text-muted">Max size: 10MB</small>
</div>
```

3. **Update Form Action**:
```blade
// Add hidden field for edit mode
<input type="hidden" name="prescription_id" id="prescription_id" value="">
<input type="hidden" name="action" id="prescription_action" value="create">
```

### 4.2 Create Edit Prescription Modal
**File**: `resources/views/components/modals/edit-prescription-modal.blade.php`

**Purpose**: Separate modal for editing (or reuse add modal with edit mode)

### 4.3 Create Show Prescription Modal
**File**: `resources/views/components/modals/show-prescription-modal.blade.php`

**Purpose**: Display prescription details (read-only)

**Features**:
- Display all prescription information
- Show medicines list
- Show pathology/radiology tests
- Show findings
- Download attachment button
- Print button

### 4.4 Create Print Prescription View
**File**: `resources/views/admin/ipd/prescription/print.blade.php`

**Purpose**: Printable prescription format

**Features**:
- Header with hospital logo
- Patient information
- Prescription details
- Medicines list
- Tests list
- Findings
- Footer with doctor signature

### 4.5 Update IPD View to Show Prescriptions
**File**: `resources/views/admin/ipd/ipd_view.blade.php`

**Additions**:
- Prescription list section
- Edit/Delete buttons for each prescription
- View prescription button
- Print prescription button

---

## Phase 5: Routes Updates

### 5.1 Add New Routes
**File**: `routes/web.php`

**Add Routes**:
```php
// IPD Prescription Routes
Route::prefix('ipd/prescription')->group(function () {
    Route::get('/{id}', [IpdController::class, 'showPrescription'])->name('ipd.prescription.show');
    Route::get('/{id}/edit', [IpdController::class, 'editPrescription'])->name('ipd.prescription.edit');
    Route::put('/{id}', [IpdController::class, 'updatePrescription'])->name('ipd.prescription.update');
    Route::delete('/{id}', [IpdController::class, 'deletePrescription'])->name('ipd.prescription.delete');
    Route::get('/{id}/print', [IpdController::class, 'printPrescription'])->name('ipd.prescription.print');
    Route::get('/{id}/download', [IpdController::class, 'downloadAttachment'])->name('ipd.prescription.download');
});
```

---

## Phase 6: API Endpoints (for AJAX)

### 6.1 Add API Routes
**File**: `routes/web.php` or `routes/api.php`

**Routes**:
```php
// Get prescription data for editing
Route::get('/api/ipd/prescription/{id}', [IpdController::class, 'getPrescriptionData']);

// Get doctors for prescribe by dropdown
Route::get('/api/ipd/{id}/doctors', [IpdController::class, 'getIpdDoctors']);
```

---

## Phase 7: JavaScript Enhancements

### 7.1 Update Modal JavaScript
**File**: `resources/views/components/modals/add-prescription-modal.blade.php`

**Additions**:
1. **Edit Mode Handling**:
```javascript
// When edit button clicked
function loadPrescriptionForEdit(prescriptionId) {
    fetch(`/api/ipd/prescription/${prescriptionId}`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('prescription_id').value = data.id;
            document.getElementById('prescription_action').value = 'edit';
            document.getElementById('prescribe_by').value = data.prescribe_by;
            // ... populate other fields
            
            // Load selected tests
            data.pathologyTests.forEach(test => {
                // Select in pathology dropdown
            });
            
            // Load medicines
            loadMedicinesForEdit(data.medicines);
        });
}
```

2. **Form Submission Handler**:
```javascript
document.getElementById('prescriptionForm').addEventListener('submit', function(e) {
    const action = document.getElementById('prescription_action').value;
    const prescriptionId = document.getElementById('prescription_id').value;
    
    if (action === 'edit') {
        this.action = `/ipd/prescription/${prescriptionId}`;
        // Change method to PUT
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        this.appendChild(methodInput);
    }
});
```

---

## Phase 8: Finding Management Enhancement

### 8.1 Update Finding Selection UI
**File**: `resources/views/components/modals/add-prescription-modal.blade.php`

**Enhancement**: Add custom filter input similar to Hospital system

```blade
<div class="col-md-3">
    <label class="form-label">Finding List</label>
    <div class="finding-filter-container">
        <input type="text" class="form-control" id="finding_filter" placeholder="Filter findings...">
        <select class="form-control multiselect2" name="findings[]" id="finding" multiple>
            <option value="">Select</option>
        </select>
    </div>
</div>
```

**JavaScript**:
```javascript
// Add filter functionality
document.getElementById('finding_filter').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const options = document.querySelectorAll('#finding option');
    options.forEach(option => {
        const text = option.textContent.toLowerCase();
        option.style.display = text.includes(filter) ? '' : 'none';
    });
});
```

---

## Phase 9: Notification System Integration

### 9.1 Create Notification Service
**File**: `app/Services/PrescriptionNotificationService.php`

**Purpose**: Send notifications to selected roles

**Implementation**:
```php
class PrescriptionNotificationService
{
    public function sendPrescriptionNotifications($prescription, $notificationRoles)
    {
        foreach ($notificationRoles as $roleId) {
            // Get users with this role
            $users = User::where('role', $roleId)->get();
            
            foreach ($users as $user) {
                // Send email notification
                // Send SMS notification (if configured)
                // Create in-app notification
            }
        }
    }
}
```

### 9.2 Integrate in Controller
**File**: `app/Http/Controllers/Modules/IpdController.php`

**Add to storePrescription()**:
```php
if (!empty($notification_to)) {
    $notificationService = new PrescriptionNotificationService();
    $notificationService->sendPrescriptionNotifications($prescription, $notification_to);
}
```

---

## Phase 10: Custom Fields Support (Optional)

### 10.1 Check if Custom Fields Module Exists
- If exists, integrate with prescription form
- Add custom fields section in modal
- Store custom field values

---

## Phase 11: Testing Checklist

### 11.1 Functional Testing
- [ ] Create new prescription
- [ ] Edit existing prescription
- [ ] Delete prescription
- [ ] View prescription details
- [ ] Print prescription
- [ ] Download attachment
- [ ] Add multiple medicines
- [ ] Add pathology tests
- [ ] Add radiology tests
- [ ] Add findings
- [ ] Select prescribe by doctor
- [ ] Upload attachment
- [ ] Send notifications

### 11.2 Data Migration Testing
- [ ] Verify existing prescriptions migrate correctly
- [ ] Verify test relationships are preserved
- [ ] Verify no data loss during migration

### 11.3 UI/UX Testing
- [ ] Modal opens correctly
- [ ] Form validation works
- [ ] Dynamic medicine rows work
- [ ] Test selection works
- [ ] Finding filter works
- [ ] File upload works
- [ ] Print preview looks correct

---

## Phase 12: Implementation Order

### Step 1: Database Changes (Phase 1)
1. Create `ipd_prescription_test` migration
2. Create migration to update `ipd_prescription` table
3. Create data migration script
4. Run migrations
5. Test data migration

### Step 2: Model Updates (Phase 2)
1. Create `IpdPrescriptionTest` model
2. Update `IpdPrescription` model
3. Test relationships

### Step 3: Controller - Create/Store (Phase 3.1)
1. Update `storePrescription()` method
2. Add file upload handling
3. Add normalized test storage
4. Test creation flow

### Step 4: Views - Add Modal (Phase 4.1)
1. Add prescribe by field
2. Add file upload field
3. Update form structure
4. Test form submission

### Step 5: Controller - Edit/Update (Phase 3.2, 3.3)
1. Add `editPrescription()` method
2. Add `updatePrescription()` method
3. Test edit flow

### Step 6: Views - Edit/Show/Print (Phase 4.2-4.4)
1. Create edit modal/view
2. Create show modal/view
3. Create print view
4. Test all views

### Step 7: Controller - Delete/Print (Phase 3.4-3.6)
1. Add `deletePrescription()` method
2. Add `printPrescription()` method
3. Test delete and print

### Step 8: Routes (Phase 5)
1. Add all new routes
2. Test route accessibility

### Step 9: JavaScript Enhancements (Phase 7)
1. Add edit mode handling
2. Add form submission logic
3. Test JavaScript functionality

### Step 10: Finding Enhancement (Phase 8)
1. Add finding filter
2. Test filter functionality

### Step 11: Notifications (Phase 9)
1. Create notification service
2. Integrate in controller
3. Test notifications

### Step 12: Final Testing (Phase 11)
1. Complete functional testing
2. Fix any issues
3. Performance testing

---

## Estimated Timeline

| Phase | Estimated Time | Priority |
|-------|---------------|----------|
| Phase 1: Database | 2-3 hours | High |
| Phase 2: Models | 1-2 hours | High |
| Phase 3: Controllers | 6-8 hours | High |
| Phase 4: Views | 8-10 hours | High |
| Phase 5: Routes | 1 hour | High |
| Phase 6: API | 2-3 hours | Medium |
| Phase 7: JavaScript | 4-6 hours | High |
| Phase 8: Finding UI | 2-3 hours | Medium |
| Phase 9: Notifications | 4-6 hours | Low |
| Phase 10: Custom Fields | 3-4 hours | Low |
| Phase 11: Testing | 4-6 hours | High |
| **Total** | **37-52 hours** | |

---

## Risk Assessment

### High Risk
- **Data Migration**: Existing prescriptions with comma-separated IDs need careful migration
- **Breaking Changes**: Changes to database structure may affect existing code

### Medium Risk
- **File Storage**: Need to configure storage disk for attachments
- **Notification System**: Depends on existing notification infrastructure

### Low Risk
- **UI Changes**: Mostly additive, shouldn't break existing functionality
- **New Features**: Can be added incrementally

---

## Rollback Plan

1. **Database Rollback**: Keep backup of original `ipd_prescription` table
2. **Code Rollback**: Use Git version control
3. **Data Rollback**: Migration script should be reversible

---

## Success Criteria

✅ All CRUD operations work correctly  
✅ Tests stored in normalized table  
✅ File attachments work  
✅ Prescribe by field functional  
✅ Edit/Update functionality complete  
✅ Print functionality works  
✅ Notifications sent correctly  
✅ No data loss during migration  
✅ UI matches Hospital system functionality  
✅ Performance is acceptable  

---

## Notes

- This plan maintains Laravel best practices
- All changes are backward compatible where possible
- Migration script ensures no data loss
- Can be implemented incrementally
- Each phase can be tested independently

