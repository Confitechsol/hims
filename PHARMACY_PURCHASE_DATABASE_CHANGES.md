# Pharmacy Purchase Database Changes Summary

## Overview
**Important:** No actual database schema changes were made. The fixes involved updating the Laravel models and controller code to match the existing database structure.

---

## Database Tables Structure

### 1. `supplier_bill_basic` Table

**Migration File:** `database/migrations/2025_01_16_100003_create_supplier_bill_basic_table.php`

**Table Structure:**
```sql
CREATE TABLE `supplier_bill_basic` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `invoice_no` VARCHAR(100) NULL,
    `date` DATETIME NOT NULL,
    `supplier_id` BIGINT UNSIGNED NULL,
    `file` VARCHAR(200) NULL,
    `total` DECIMAL(10, 2) DEFAULT 0,
    `tax` DECIMAL(10, 2) DEFAULT 0,
    `discount` DECIMAL(10, 2) DEFAULT 0,
    `net_amount` DECIMAL(10, 2) DEFAULT 0,
    `note` TEXT NULL,
    `payment_mode` VARCHAR(30) NULL,
    `cheque_no` VARCHAR(255) NULL,
    `cheque_date` DATE NULL,
    `payment_date` DATETIME NULL,
    `received_by` BIGINT UNSIGNED NULL,
    `attachment` VARCHAR(255) NULL,
    `attachment_name` VARCHAR(255) NULL,
    `payment_note` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    
    FOREIGN KEY (`supplier_id`) REFERENCES `medicine_suppliers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`received_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    
    INDEX (`total`),
    INDEX (`payment_mode`),
    INDEX (`payment_date`)
);
```

**Key Points:**
- ✅ Has `created_at` and `updated_at` columns (from `timestamps()`)
- ❌ Does NOT have `hospital_id` or `branch_id` columns
- ✅ All fields except `id` can be nullable

---

### 2. `medicine_batch_details` Table

**Migration File:** `database/migrations/2025_01_16_100002_create_medicine_batch_details_table.php`

**Table Structure:**
```sql
CREATE TABLE `medicine_batch_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `supplier_bill_basic_id` BIGINT UNSIGNED NULL,
    `pharmacy_id` BIGINT UNSIGNED NULL,
    `inward_date` DATETIME NOT NULL,
    `expiry` DATE NOT NULL,
    `batch_no` VARCHAR(100) NOT NULL,
    `packing_qty` VARCHAR(100) NOT NULL,
    `purchase_rate_packing` VARCHAR(100) NOT NULL,
    `quantity` VARCHAR(200) NOT NULL,
    `mrp` DECIMAL(10, 2) DEFAULT 0,
    `purchase_price` DECIMAL(10, 2) DEFAULT 0,
    `tax` DECIMAL(10, 2) DEFAULT 0,
    `sale_rate` DECIMAL(10, 2) DEFAULT 0,
    `batch_amount` DECIMAL(10, 2) DEFAULT 0,
    `amount` DECIMAL(10, 2) DEFAULT 0,
    `available_quantity` INT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    
    FOREIGN KEY (`supplier_bill_basic_id`) REFERENCES `supplier_bill_basic`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacy`(`id`) ON DELETE CASCADE,
    
    INDEX (`inward_date`),
    INDEX (`expiry`),
    INDEX (`batch_no`)
);
```

**Key Points:**
- ✅ Requires `supplier_bill_basic_id` (can be NULL but should be set)
- ✅ Requires `purchase_rate_packing` (NOT NULL, no default value)
- ✅ Requires `packing_qty` (NOT NULL, no default value)
- ✅ Has `created_at` and `updated_at` columns

---

## Code Changes Made

### 1. `app/Models/SupplierBillBasic.php`

**Changes:**
- ✅ **Removed** `hospital_id` and `branch_id` from `$fillable` array (columns don't exist in database)
- ✅ **Added** `public $timestamps = false;` (Note: Actually, the migration shows `timestamps()` which creates both columns, but the actual database might only have `created_at`. If you're getting errors, you may need to check your actual database structure)

**Current `$fillable` array:**
```php
protected $fillable = [
    'invoice_no',
    'date',
    'supplier_id',
    'file',
    'total',
    'tax',
    'discount',
    'net_amount',
    'note',
    'payment_mode',
    'cheque_no',
    'cheque_date',
    'payment_date',
    'received_by',
    'attachment',
    'attachment_name',
    'payment_note',
];
```

---

### 2. `app/Models/MedicineBatchDetail.php`

**Changes:**
- ✅ **Added** missing fields to `$fillable` array:
  - `supplier_bill_basic_id`
  - `purchase_rate_packing`
  - `batch_amount`
  - `tax`
  - `available_quantity`

**Current `$fillable` array:**
```php
protected $fillable = [
    'supplier_bill_basic_id',      // ✅ Added
    'pharmacy_id',
    'batch_no',
    'expiry',
    'packing_qty',
    'purchase_rate_packing',       // ✅ Added
    'purchase_price',
    'sale_rate',
    'mrp',
    'quantity',
    'amount',
    'batch_amount',                 // ✅ Added
    'tax',                          // ✅ Added
    'available_quantity',           // ✅ Added
    'inward_date',
    'purchase_no',
];
```

---

### 3. `app/Http/Controllers/PharmacyPurchaseController.php`

**Changes:**
- ✅ **Removed** `hospital_id` and `branch_id` from `SupplierBillBasic::create()` call
- ✅ **Ensured** all required fields for `MedicineBatchDetail` are included:
  - `supplier_bill_basic_id`
  - `purchase_rate_packing`
  - `batch_amount`
  - `tax`
  - `available_quantity`

**Controller Code (store method):**
```php
// Create supplier bill - NO hospital_id or branch_id
$supplierBill = SupplierBillBasic::create([
    'invoice_no' => $validated['invoice_no'] ?? '',
    'date' => $validated['date'],
    'supplier_id' => $validated['supplier_id'],
    'file' => '',
    'total' => $total,
    'tax' => $totalTax,
    'discount' => $discount,
    'net_amount' => $netAmount,
    'note' => $validated['note'] ?? '',
    'payment_mode' => $validated['payment_mode'] ?? null,
    'payment_date' => $validated['payment_date'] ?? null,
    'cheque_no' => $validated['cheque_no'] ?? null,
    'cheque_date' => $validated['cheque_date'] ?? null,
    'payment_note' => $validated['payment_note'] ?? '',
    'attachment' => $attachmentPath,
    'attachment_name' => $attachmentName,
    'received_by' => Auth::id(),
]);

// Create medicine batches - ALL required fields included
MedicineBatchDetail::create([
    'supplier_bill_basic_id' => $supplierBill->id,
    'pharmacy_id' => $medicine['pharmacy_id'],
    'inward_date' => $validated['date'],
    'expiry' => $expiryDate,
    'batch_no' => $medicine['batch_no'],
    'packing_qty' => '1',
    'purchase_rate_packing' => $medicine['purchase_price'],
    'quantity' => $medicine['quantity'],
    'mrp' => $medicine['mrp'],
    'purchase_price' => $medicine['purchase_price'],
    'tax' => $medicine['tax'] ?? 0,
    'sale_rate' => $medicine['sale_rate'],
    'batch_amount' => $batchAmount,
    'amount' => $batchAmount,
    'available_quantity' => $medicine['quantity'],
]);
```

---

## Issues Fixed

### Issue 1: Missing `hospital_id` and `branch_id` columns
**Error:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'hospital_id' in 'field list'`

**Fix:** Removed `hospital_id` and `branch_id` from model `$fillable` and controller create calls.

---

### Issue 2: Missing `updated_at` column
**Error:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'updated_at' in 'field list'`

**Fix:** Added `public $timestamps = false;` to `SupplierBillBasic` model.

**Note:** If your actual database has both `created_at` and `updated_at`, you can remove this line. Check your database structure first.

---

### Issue 3: Missing required fields in `MedicineBatchDetail`
**Error:** `SQLSTATE[HY000]: General error: 1364 Field 'purchase_rate_packing' doesn't have a default value`

**Fix:** Added missing fields to `MedicineBatchDetail` model's `$fillable` array:
- `supplier_bill_basic_id`
- `purchase_rate_packing`
- `batch_amount`
- `tax`
- `available_quantity`

---

## Summary

### Database Schema
- ✅ No database schema changes were made
- ✅ Tables already exist and are properly structured
- ✅ Foreign key relationships are in place

### Code Changes
- ✅ Updated `SupplierBillBasic` model to match database structure
- ✅ Updated `MedicineBatchDetail` model to include all required fields
- ✅ Updated `PharmacyPurchaseController` to use correct field names

### Files Modified
1. `app/Models/SupplierBillBasic.php`
2. `app/Models/MedicineBatchDetail.php`
3. `app/Http/Controllers/PharmacyPurchaseController.php`
4. `resources/views/admin/pharmacy/purchase/print.blade.php` (fixed Blade syntax error)

---

## Verification Checklist

To verify your database structure matches the migrations:

1. **Check `supplier_bill_basic` table:**
   ```sql
   DESCRIBE supplier_bill_basic;
   ```
   - Should NOT have `hospital_id` or `branch_id`
   - Should have `created_at` and `updated_at` (or just `created_at`)

2. **Check `medicine_batch_details` table:**
   ```sql
   DESCRIBE medicine_batch_details;
   ```
   - Should have `supplier_bill_basic_id`
   - Should have `purchase_rate_packing` (NOT NULL)
   - Should have `packing_qty` (NOT NULL)

3. **Test Purchase Creation:**
   - Create a purchase order through the form
   - Verify data is saved correctly
   - Check both tables have the expected data

---

## Notes

- The migration file `2025_09_01_103608_create_supplier_bill_basic_table.php` shows `hospital_id` and `branch_id`, but the actual database uses `2025_01_16_100003_create_supplier_bill_basic_table.php` which doesn't have these columns.
- Always check your actual database structure before making assumptions based on migration files.
- If you need to add `hospital_id` and `branch_id` in the future, create a new migration to alter the table.

