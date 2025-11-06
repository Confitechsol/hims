# 🇮🇳 Indian GST Implementation for Pharmacy Module

## Overview
The pharmacy module has been updated to use **Indian GST (Goods and Services Tax)** rates instead of VAT.

---

## ✅ Changes Made

### 1. **Database Migration Updated**
**File:** `database/migrations/2025_01_16_100000_create_pharmacy_table.php`

**Changed:**
```php
// OLD (VAT)
$table->float('vat')->nullable();
$table->string('vat_ac', 50)->nullable();

// NEW (GST)
$table->decimal('gst_percentage', 5, 2)->nullable()
      ->comment('GST Rate: 5%, 12%, 18%, 28%');
```

**Note:** `vat_ac` field removed as it's not needed for GST calculation.

---

### 2. **Pharmacy Model Updated**
**File:** `app/Models/Pharmacy.php`

**Changed:**
```php
// OLD
protected $fillable = [
    ...
    'vat',
    'vat_ac',
    ...
];

protected $casts = [
    'vat' => 'float',
];

// NEW
protected $fillable = [
    ...
    'gst_percentage',
    ...
];

protected $casts = [
    'gst_percentage' => 'decimal:2',
];
```

---

### 3. **Controller Validation Updated**
**File:** `app/Http/Controllers/PharmacyController.php`

**Changed in `store()` and `update()` methods:**
```php
// OLD
'vat' => 'nullable|numeric',

// NEW
'gst_percentage' => 'nullable|numeric|min:0|max:100',
```

**Changed in `import()` method:**
```php
// CSV column 8 changed from VAT to GST
'gst_percentage' => $row[8] ?? null,
```

---

### 4. **Create Medicine View Updated**
**File:** `resources/views/admin/pharmacy/create.blade.php`

**Changed:**
```php
// OLD (VAT Input)
<input type="number" step="0.01" name="vat" class="form-control">

// NEW (GST Dropdown)
<select name="gst_percentage" class="form-select">
    <option value="">Select GST Rate</option>
    <option value="0">0% (Exempt)</option>
    <option value="5">5%</option>
    <option value="12">12%</option>
    <option value="18">18%</option>
    <option value="28">28%</option>
</select>
<small class="text-muted">Standard Indian GST Rates</small>
```

---

### 5. **Edit Medicine View Updated**
**File:** `resources/views/admin/pharmacy/edit.blade.php`

**Changed:** Same as create view, but with pre-selected value from database.

---

### 6. **Show Medicine View Updated**
**File:** `resources/views/admin/pharmacy/show.blade.php`

**Changed:**
```php
// OLD
<tr>
    <th>VAT:</th>
    <td>{{ $medicine->vat ? $medicine->vat . '%' : '-' }}</td>
</tr>

// NEW
<tr>
    <th>GST Rate:</th>
    <td>
        @if($medicine->gst_percentage !== null)
            <span class="badge bg-info">{{ $medicine->gst_percentage }}% GST</span>
        @else
            -
        @endif
    </td>
</tr>
```

---

### 7. **Import Medicine View Updated**
**File:** `resources/views/admin/pharmacy/import.blade.php`

**Changed:**
- CSV column header updated to "GST Percentage"
- Sample data updated with Indian GST rates
- Added GST rate guide for reference

**New Sample CSV:**
```csv
Medicine Name,Category ID,Company ID,Composition,Group ID,Unit ID,Min Level,Reorder Level,GST%,Unit Packing,Rack Number
Paracetamol 500mg,2,1,Paracetamol IP 500mg,6,1,20,50,12,10 Tablets,A1
Azithromycin 250mg,1,2,Azithromycin 250mg,1,1,10,30,18,6 Tablets,B2
Life Saving Drug,3,1,Essential Medicine,2,1,15,40,5,15 Tablets,C3
```

---

## 📊 Indian GST Rate Structure

### Standard GST Rates for Medicines

| GST Rate | Category | Examples |
|----------|----------|----------|
| **0%** | Exempt | Some life-saving drugs |
| **5%** | Essential/Life-saving | Life-saving medicines, essential drugs |
| **12%** | Essential Medicines | Common essential medicines |
| **18%** | General Medicines | General pharmaceutical products |
| **28%** | Luxury/Non-essential | Health supplements, cosmetic items |

---

## 🔄 Migration Steps

### If Database Already Exists:

1. **Create a new migration to update existing table:**

```bash
php artisan make:migration update_pharmacy_table_vat_to_gst
```

2. **Migration content:**

```php
public function up()
{
    Schema::table('pharmacy', function (Blueprint $table) {
        // Remove old VAT columns
        $table->dropColumn(['vat', 'vat_ac']);
        
        // Add GST column
        $table->decimal('gst_percentage', 5, 2)->nullable()
              ->comment('GST Rate: 5%, 12%, 18%, 28%')
              ->after('reorder_level');
    });
}

public function down()
{
    Schema::table('pharmacy', function (Blueprint $table) {
        $table->dropColumn('gst_percentage');
        
        // Restore VAT columns
        $table->float('vat')->nullable();
        $table->string('vat_ac', 50)->nullable();
    });
}
```

3. **Run migration:**

```bash
php artisan migrate
```

### If Fresh Installation:

Simply run:
```bash
php artisan migrate
```

The updated migration file will create the table with GST field from the start.

---

## 💡 Usage Examples

### Adding Medicine with GST

**In Create Form:**
1. Select appropriate GST rate from dropdown (0%, 5%, 12%, 18%, 28%)
2. System will store the rate in `gst_percentage` field

**In Billing:**
When calculating medicine price, GST is applied:
```php
$basePrice = 100;
$gstRate = 12; // 12%
$gstAmount = ($basePrice * $gstRate) / 100; // 12
$finalPrice = $basePrice + $gstAmount; // 112
```

---

## 🎯 Business Logic

### GST Calculation in Billing

The GST percentage from medicine master is used during:

1. **Pharmacy Bill Creation** - GST applied on sale price
2. **Purchase Orders** - GST on purchase price
3. **Stock Valuation** - GST included in cost calculation

### Example:
```
Medicine: Paracetamol 500mg
Sale Rate: ₹100
GST Rate: 12%

Calculation:
Base Price: ₹100
GST Amount: ₹100 × 12% = ₹12
Final Price: ₹100 + ₹12 = ₹112
```

---

## 📝 Field Specifications

### gst_percentage Field

```php
Type: DECIMAL(5,2)
Nullable: Yes
Valid Values: 0, 5, 12, 18, 28 (or any decimal up to 100.00)
Example Values: 5.00, 12.00, 18.00, 28.00
```

**Precision:**
- 5 digits total
- 2 decimal places
- Allows: 0.00 to 999.99
- Typical: 0, 5, 12, 18, 28

---

## 🔍 Key Differences: VAT vs GST

| Aspect | VAT (Old) | GST (New) |
|--------|-----------|-----------|
| Field Name | `vat` | `gst_percentage` |
| Data Type | FLOAT | DECIMAL(5,2) |
| Input Type | Number input | Dropdown select |
| Standard Rates | Variable | 0%, 5%, 12%, 18%, 28% |
| Additional Field | `vat_ac` | None |
| Accuracy | Less precise | Precise to 2 decimals |

---

## ✅ Compatibility

### Backward Compatibility
If you have existing data with VAT:
1. Run the update migration
2. Manually map VAT rates to equivalent GST rates
3. Update `vat_ac` data if needed for records

### Forward Compatibility
- New medicines will only use GST
- All forms now show GST dropdown
- Import templates updated for GST

---

## 🚨 Important Notes

1. **GST is mandatory in India** for all pharmaceutical products
2. **Different medicines may have different GST rates** - always verify with tax authorities
3. **Life-saving drugs often have 5% or exempt rates**
4. **General medicines typically have 12% GST**
5. **Some supplements may have 18% or 28% GST**

---

## 📞 Support

For questions about GST rates or implementation:
- Refer to Indian GST Council notifications
- Consult with tax advisor for specific medicine categories
- Check government HSN codes for pharmaceuticals

---

## 🎉 Summary

✅ **VAT removed** from pharmacy module  
✅ **GST implemented** with standard Indian rates  
✅ **Dropdown selection** for easy rate assignment  
✅ **Decimal precision** for accurate calculations  
✅ **Import template** updated for GST  
✅ **All views** updated to show GST  
✅ **Migration ready** for deployment  

**Status:** ✅ **Complete and Ready for Use**

---

**Updated:** January 16, 2025  
**Compliance:** Indian GST Standards  
**Framework:** Laravel 12.x

