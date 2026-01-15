# TPA Implementation - Table Changes Summary

## Overview
The TPA (Third Party Administrator) functionality was added to Pathology and Radiology billing sections. **No new database migrations were required** as the necessary columns already existed in the tables.

---

## 1. Pathology Billing Tables

### `pathology_billing` Table
**Existing Column Used for TPA:**
- `organisation_id` (unsignedBigInteger, nullable, indexed)
  - Foreign key to `organisation` table
  - Stores the TPA organization ID when TPA is activated
  - Already existed in the original migration: `2025_09_01_103536_create_pathology_billing_table.php`

**Table Structure (TPA-related columns only):**
```sql
organisation_id BIGINT UNSIGNED NULL,
INDEX (organisation_id),
FOREIGN KEY (organisation_id) REFERENCES organisation(id) ON DELETE SET NULL
```

### `pathology_report` Table
**No TPA-specific columns** - TPA charges are applied at the billing level, not at the individual report level.

---

## 2. Radiology Billing Tables

### `radiology_billing` Table
**Existing Column Used for TPA:**
- `organisation_id` (unsignedBigInteger, nullable, indexed)
  - Foreign key to `organisation` table
  - Stores the TPA organization ID when TPA is activated
  - Already existed in the original migration: `2025_09_01_103549_create_radiology_billing_table.php`

**Table Structure (TPA-related columns only):**
```sql
organisation_id BIGINT UNSIGNED NULL,
INDEX (organisation_id),
FOREIGN KEY (organisation_id) REFERENCES organisation(id) ON DELETE SET NULL
```

### `radiology_report` Table
**No TPA-specific columns** - TPA charges are applied at the billing level, not at the individual report level.

**Note:** The `radiology_report` table has a `consultant_doctor` column with a **10-character limit**, which required truncation in the implementation.

---

## 3. Related Tables Used

### `organisation` Table
This is the main TPA/Insurance organization table that stores:
- Organization name
- Organization code
- Other TPA details

**Relationship:**
- `pathology_billing.organisation_id` → `organisation.id`
- `radiology_billing.organisation_id` → `organisation.id`

### `organisations_charge` Table
This table stores TPA-specific charges for each charge/test:
- Links `organisation_id` with `charge_id`
- Stores `org_charge` (TPA-specific charge amount)
- Used to fetch TPA charges for pathology and radiology tests

**Key Columns:**
- `organisation_id` - TPA organization
- `charge_id` - Charge/test ID
- `org_charge` - TPA-specific charge amount

---

## 4. Implementation Details

### How TPA Works:
1. **Billing Level:**
   - When creating/editing a bill, user can check "Activate TPA"
   - If checked, `organisation_id` is stored in `pathology_billing` or `radiology_billing` table
   - The selected TPA organization is linked to the bill

2. **Charge Calculation:**
   - When TPA is activated, the system checks `organisations_charge` table
   - For each test, it looks up the TPA charge using `charge_id` and `organisation_id`
   - If TPA charge exists, it uses that; otherwise, uses standard charge
   - Charges are applied dynamically via JavaScript before form submission

3. **Data Flow:**
   ```
   User selects TPA → organisation_id stored in billing table
   → For each test → Check organisations_charge table
   → Apply TPA charge if exists, else standard charge
   → Calculate totals with TPA charges
   ```

---

## 5. Model Changes

### Models Updated:
1. **PathologyBilling Model:**
   - Already had `organisation_id` in fillable array
   - Added `organisation` relationship method

2. **RadiologyBilling Model:**
   - Already had `organisation_id` in fillable array
   - Added `organisation` relationship method

3. **PathologyReport Model:**
   - Added `public $timestamps = false;` (table doesn't have timestamps)

4. **RadiologyReport Model:**
   - Added `public $timestamps = false;` (table doesn't have timestamps)
   - Note: `consultant_doctor` field has 10-character limit

---

## 6. Summary

### ✅ What Was Already in Database:
- `pathology_billing.organisation_id` column
- `radiology_billing.organisation_id` column
- `organisation` table (TPA organizations)
- `organisations_charge` table (TPA-specific charges)

### ✅ What Was Added in Code:
- TPA checkbox and dropdown in create/edit views
- JavaScript logic to fetch and apply TPA charges
- API endpoints to fetch patient TPAs and prescription tests
- Controller logic to handle TPA selection and charge calculation
- Model relationships for `organisation`

### ❌ What Was NOT Changed:
- No new database migrations
- No new columns added to existing tables
- No schema modifications

---

## 7. SQL Queries for Verification

### Check if TPA columns exist:
```sql
-- Pathology Billing
SHOW COLUMNS FROM pathology_billing LIKE 'organisation_id';

-- Radiology Billing
SHOW COLUMNS FROM radiology_billing LIKE 'organisation_id';

-- Check foreign key constraints
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME IN ('pathology_billing', 'radiology_billing')
    AND COLUMN_NAME = 'organisation_id';
```

### Check TPA data:
```sql
-- View bills with TPA
SELECT id, patient_id, organisation_id, total, net_amount, date
FROM pathology_billing
WHERE organisation_id IS NOT NULL;

SELECT id, patient_id, organisation_id, total, net_amount, date
FROM radiology_billing
WHERE organisation_id IS NOT NULL;
```

---

## 8. Important Notes

1. **No Migration Required:** All necessary columns already existed in the database.

2. **TPA Charges:** TPA charges are stored in `organisations_charge` table, not in billing tables. The billing tables only store which TPA organization was selected.

3. **Dynamic Calculation:** TPA charges are calculated dynamically on the frontend using JavaScript and applied before form submission.

4. **Column Limitations:**
   - `radiology_report.consultant_doctor` has a 10-character limit (requires truncation)
   - `pathology_billing.doctor_name` has a 100-character limit
   - `radiology_billing.doctor_name` has a 100-character limit

5. **Foreign Key:** Both billing tables have foreign key constraints on `organisation_id` that set to NULL on delete (ON DELETE SET NULL).

---

## 9. Files Modified (No Database Changes)

### Controllers:
- `app/Http/Controllers/PathologyBillingController.php`
- `app/Http/Controllers/RadiologyBillingController.php`

### Views:
- `resources/views/admin/pathology/billing/create.blade.php`
- `resources/views/admin/pathology/billing/edit.blade.php`
- `resources/views/admin/pathology/billing/index.blade.php`
- `resources/views/admin/pathology/billing/show.blade.php`
- `resources/views/admin/radiology/billing/create.blade.php`
- `resources/views/admin/radiology/billing/edit.blade.php`
- `resources/views/admin/radiology/billing/index.blade.php`
- `resources/views/admin/radiology/billing/show.blade.php`

### Models:
- `app/Models/PathologyBilling.php` (relationship added)
- `app/Models/RadiologyBilling.php` (relationship added)
- `app/Models/PathologyReport.php` (timestamps disabled)
- `app/Models/RadiologyReport.php` (timestamps disabled)

### Routes:
- Added API routes for fetching patient TPAs and prescription tests

---

**Conclusion:** The TPA functionality was implemented entirely through code changes. No database migrations or schema modifications were required as all necessary columns already existed in the tables.

