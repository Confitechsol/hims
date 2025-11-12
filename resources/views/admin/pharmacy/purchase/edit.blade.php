@extends('layouts.adminLayout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 text-end">
                        <span style="color: #750096"><strong>Purchase Date:</strong> {{ \Carbon\Carbon::parse($purchase->date)->format('m/d/Y g:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('pharmacy.purchase.update', $purchase->id) }}" method="POST" id="purchaseForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="date" value="{{ $purchase->date }}">
                    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                    
                    {{-- Bill No --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Bill No</label>
                            <input type="text" name="invoice_no" class="form-control" value="{{ old('invoice_no', $purchase->invoice_no) }}">
                        </div>
                    </div>

                    {{-- Medicine Details Table --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Medicine Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="medicineTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="12%">Medicine Category <span class="text-danger">*</span></th>
                                            <th width="12%">Medicine Name <span class="text-danger">*</span></th>
                                            <th width="10%">Batch No <span class="text-danger">*</span></th>
                                            <th width="10%">Expiry Date <span class="text-danger">*</span></th>
                                            <th width="10%">MRP (INR) <span class="text-danger">*</span></th>
                                            <th width="8%">Batch Amount (INR)</th>
                                            <th width="10%">Sale Price (INR) <span class="text-danger">*</span></th>
                                            <th width="8%">Packing Qty</th>
                                            <th width="8%">Quantity <span class="text-danger">*</span></th>
                                            <th width="10%">Purchase Price (INR) <span class="text-danger">*</span></th>
                                            <th width="8%">Tax <span class="text-danger">*</span></th>
                                            <th width="10%">Amount (INR) <span class="text-danger">*</span></th>
                                            <th width="4%">
                                                <button type="button" class="btn btn-sm btn-primary" id="addRow">
                                                    <i class="ti ti-plus"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicineTableBody">
                                        @foreach($purchase->batches as $index => $batch)
                                        <tr class="medicine-row" data-batch-id="{{ $batch->id }}">
                                            <td>
                                                <select class="form-select medicine-category" required>
                                                    <option value="">Select</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $batch->pharmacy->medicine_category_id == $category->id ? 'selected' : '' }}>
                                                            {{ $category->medicine_category }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="medicines[{{ $index }}][pharmacy_id]" class="form-select medicine-name" required>
                                                    <option value="{{ $batch->pharmacy_id }}">{{ $batch->pharmacy->medicine_name }}</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="medicines[{{ $index }}][batch_no]" class="form-control" value="{{ $batch->batch_no }}" required></td>
                                            <td>
                                                <input type="text" name="medicines[{{ $index }}][expiry]" class="form-control expiry-date monthpicker" 
                                                       value="{{ \Carbon\Carbon::parse($batch->expiry)->format('M/Y') }}" 
                                                       data-db-value="{{ \Carbon\Carbon::parse($batch->expiry)->format('Y-m') }}" required placeholder="Mar/2026" readonly>
                                            </td>
                                            <td><input type="number" name="medicines[{{ $index }}][mrp]" class="form-control mrp" value="{{ $batch->mrp }}" step="0.01" min="0" required></td>
                                            <td><input type="number" name="medicines[{{ $index }}][batch_amount]" class="form-control batch-amount" value="{{ $batch->batch_amount }}" step="0.01" min="0"></td>
                                            <td><input type="number" name="medicines[{{ $index }}][sale_rate]" class="form-control sale-price" value="{{ $batch->sale_rate }}" step="0.01" min="0" required></td>
                                            <td><input type="text" name="medicines[{{ $index }}][packing_qty]" class="form-control packing-qty" value="{{ $batch->packing_qty }}"></td>
                                            <td><input type="number" name="medicines[{{ $index }}][quantity]" class="form-control quantity" value="{{ $batch->quantity }}" min="1" required></td>
                                            <td><input type="number" name="medicines[{{ $index }}][purchase_price]" class="form-control purchase-price" value="{{ $batch->purchase_price }}" step="0.01" min="0" required></td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="medicines[{{ $index }}][tax]" class="form-control tax" value="{{ $batch->tax }}" step="0.01" min="0">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </td>
                                            <td><input type="number" class="form-control amount" value="{{ $batch->amount }}" step="0.01" readonly></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                                <input type="hidden" name="medicines[{{ $index }}][batch_id]" value="{{ $batch->id }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Notes and Summary Section --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3">{{ old('note', $purchase->note) }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Attach Document</label>
                                <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Allowed: PDF, JPG, JPEG, PNG (Max: 2MB)</small>
                                @if($purchase->attachment)
                                    <div class="mt-2">
                                        <small>Current: <a href="{{ asset('storage/' . $purchase->attachment) }}" target="_blank">{{ $purchase->attachment_name }}</a></small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Total (INR)</th>
                                        <td class="text-end">
                                            <input type="number" name="total" id="total" class="form-control text-end" value="{{ $purchase->total }}" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Discount %</th>
                                        <td class="text-end">
                                            <div class="input-group">
                                                <input type="number" id="discount_percent" class="form-control text-end" step="0.01" min="0" max="100">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Discount (INR)</th>
                                        <td class="text-end">
                                            <input type="number" name="discount" id="discount" class="form-control text-end" step="0.01" min="0" value="{{ $purchase->discount }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tax (INR)</th>
                                        <td class="text-end">
                                            <input type="number" id="tax_total" class="form-control text-end" value="{{ $purchase->tax }}" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold">Net Amount (INR)</th>
                                        <td class="text-end">
                                            <input type="number" name="net_amount" id="net_amount" class="form-control text-end fw-bold" value="{{ $purchase->net_amount }}" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-2 mt-4">
                        <a href="{{ route('pharmacy.purchase.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>Update Purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let rowIndex = {{ $purchase->batches->count() }};
const allMedicines = @json($medicines);

$(document).ready(function() {
    console.log('Edit page loaded!');
    
    // Calculate initial totals
    calculateTotals();
    
    // Add new medicine row
    $('#addRow').on('click', function() {
        addMedicineRow();
    });

    // Remove medicine row
    $(document).on('click', '.remove-row', function() {
        if ($('.medicine-row').length > 1) {
            if (confirm('Are you sure you want to remove this medicine?')) {
                $(this).closest('.medicine-row').remove();
                calculateTotals();
            }
        } else {
            alert('At least one medicine is required!');
        }
    });

    // Medicine category change handler
    $(document).on('change', '.medicine-category', function() {
        console.log('Category changed (jQuery)!', $(this).val());
        handleCategoryChange(this);
    });

    // Calculate row amount on quantity/price/tax change
    $(document).on('input', '.quantity, .purchase-price, .tax, .mrp, .batch-amount', function() {
        const row = $(this).closest('tr')[0];
        calculateRowAmount(row);
        calculateTotals();
    });

    // Discount percentage change
    $('#discount_percent').on('input', function() {
        const total = parseFloat($('#total').val()) || 0;
        const discountPercent = parseFloat($(this).val()) || 0;
        const discountAmount = (total * discountPercent) / 100;
        $('#discount').val(discountAmount.toFixed(2));
        calculateTotals();
    });

    // Discount amount change
    $('#discount').on('input', function() {
        calculateTotals();
    });

    // Form validation
    $('#purchaseForm').on('submit', function(e) {
        const supplierId = $('#supplier_id').val();
        if (!supplierId) {
            e.preventDefault();
            alert('Please select a supplier');
            return false;
        }

        // Update monthpicker values for submission
        $('.monthpicker').each(function() {
            const dbValue = $(this).attr('data-db-value');
            if (dbValue) {
                $(this).val(dbValue);
            }
        });

        // Add supplier_id to form data
        $('<input>').attr({
            type: 'hidden',
            name: 'supplier_id',
            value: supplierId
        }).appendTo(this);
    });
});

function addMedicineRow() {
    rowIndex++;
    const tbody = $('#medicineTableBody');
    
    const newRow = `
        <tr class="medicine-row">
            <td>
                <select class="form-select medicine-category" required>
                    <option value="">Select</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="medicines[${rowIndex}][pharmacy_id]" class="form-select medicine-name" required>
                    <option value="">Select</option>
                </select>
            </td>
            <td><input type="text" name="medicines[${rowIndex}][batch_no]" class="form-control" required></td>
            <td><input type="text" name="medicines[${rowIndex}][expiry]" class="form-control expiry-date monthpicker" required placeholder="Mar/2026" readonly></td>
            <td><input type="number" name="medicines[${rowIndex}][mrp]" class="form-control mrp" step="0.01" min="0" required></td>
            <td><input type="number" name="medicines[${rowIndex}][batch_amount]" class="form-control batch-amount" step="0.01" min="0"></td>
            <td><input type="number" name="medicines[${rowIndex}][sale_rate]" class="form-control sale-price" step="0.01" min="0" required></td>
            <td><input type="text" name="medicines[${rowIndex}][packing_qty]" class="form-control packing-qty"></td>
            <td><input type="number" name="medicines[${rowIndex}][quantity]" class="form-control quantity" min="1" required></td>
            <td><input type="number" name="medicines[${rowIndex}][purchase_price]" class="form-control purchase-price" step="0.01" min="0" required></td>
            <td>
                <div class="input-group">
                    <input type="number" name="medicines[${rowIndex}][tax]" class="form-control tax" step="0.01" min="0" value="0">
                    <span class="input-group-text">%</span>
                </div>
            </td>
            <td><input type="number" class="form-control amount" step="0.01" readonly></td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    tbody.append(newRow);
}

function calculateRowAmount(row) {
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const purchasePrice = parseFloat(row.querySelector('.purchase-price').value) || 0;
    const tax = parseFloat(row.querySelector('.tax').value) || 0;
    
    const amount = quantity * purchasePrice;
    const batchAmount = amount;
    
    row.querySelector('.amount').value = amount.toFixed(2);
    
    // Auto-fill batch amount if empty
    const batchAmountField = row.querySelector('.batch-amount');
    if (!batchAmountField.value) {
        batchAmountField.value = batchAmount.toFixed(2);
    }
}

function calculateTotals() {
    let total = 0;
    let totalTax = 0;
    
    document.querySelectorAll('.medicine-row').forEach(row => {
        const amount = parseFloat(row.querySelector('.amount').value) || 0;
        const tax = parseFloat(row.querySelector('.tax').value) || 0;
        
        total += amount;
        totalTax += (amount * tax) / 100;
    });
    
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const netAmount = total + totalTax - discount;
    
    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('tax_total').value = totalTax.toFixed(2);
    document.getElementById('net_amount').value = netAmount.toFixed(2);
}

// Handle category change
function handleCategoryChange(categorySelect) {
    const categoryId = categorySelect.value;
    const row = categorySelect.closest('tr');
    const medicineSelect = row.querySelector('.medicine-name');
    
    console.log('Category changed to:', categoryId);
    
    if (!categoryId) {
        medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
        return;
    }
    
    medicineSelect.innerHTML = '<option value="">Loading...</option>';
    
    const apiUrl = '{{ route("pharmacy.purchase.api.medicines-by-category") }}?category_id=' + categoryId;
    console.log('Fetching medicines from:', apiUrl);
    
    fetch(apiUrl)
        .then(response => {
            console.log('API Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(medicines => {
            console.log('Medicines received:', medicines);
            
            medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
            
            if (medicines.length === 0) {
                medicineSelect.innerHTML += '<option value="" disabled>No medicines found in this category</option>';
            } else {
                medicines.forEach(medicine => {
                    const option = document.createElement('option');
                    option.value = medicine.id;
                    option.textContent = medicine.medicine_name;
                    medicineSelect.appendChild(option);
                });
                console.log('Added', medicines.length, 'medicines to dropdown');
            }
        })
        .catch(error => {
            console.error('Error fetching medicines:', error);
            medicineSelect.innerHTML = '<option value="">Error: ' + error.message + '</option>';
        });
}

// Custom Month/Year Picker (same as create page)
$(document).on('click', '.monthpicker', function() {
    const input = $(this);
    const currentValue = input.val();
    
    let currentYear, currentMonth;
    if (currentValue && currentValue.includes('/')) {
        const parts = currentValue.split('/');
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        currentMonth = monthNames.indexOf(parts[0]) + 1;
        currentYear = parseInt(parts[1]);
    } else {
        const now = new Date();
        currentMonth = now.getMonth() + 1;
        currentYear = now.getFullYear();
    }
    
    showMonthPicker(input, currentYear, currentMonth);
});

function showMonthPicker(input, year, month) {
    $('.custom-monthpicker').remove();
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    let pickerHtml = `
        <div class="custom-monthpicker" style="position: absolute; z-index: 9999; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 280px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <button type="button" class="btn btn-sm btn-outline-secondary year-prev" style="width: 30px; height: 30px; padding: 0;">
                    <i class="ti ti-chevron-left"></i>
                </button>
                <strong style="font-size: 16px;">${year}</strong>
                <button type="button" class="btn btn-sm btn-outline-secondary year-next" style="width: 30px; height: 30px; padding: 0;">
                    <i class="ti ti-chevron-right"></i>
                </button>
            </div>
            <div class="month-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">`;
    
    months.forEach((monthName, idx) => {
        const monthNum = idx + 1;
        const isSelected = monthNum === month ? 'background: #007bff; color: white;' : 'background: #f8f9fa;';
        pickerHtml += `
            <button type="button" class="btn btn-sm month-btn" data-month="${monthNum}" data-month-name="${monthName}" 
                    style="${isSelected} border: 1px solid #dee2e6; padding: 8px; font-size: 13px; min-width: 70px;">
                ${monthName}
            </button>`;
    });
    
    pickerHtml += `
            </div>
        </div>`;
    
    const picker = $(pickerHtml);
    const offset = input.offset();
    picker.css({
        top: offset.top + input.outerHeight() + 5,
        left: offset.left
    });
    
    $('body').append(picker);
    
    picker.find('.year-prev').on('click', function() {
        year--;
        showMonthPicker(input, year, month);
    });
    
    picker.find('.year-next').on('click', function() {
        year++;
        showMonthPicker(input, year, month);
    });
    
    picker.find('.month-btn').on('click', function() {
        const selectedMonth = $(this).data('month');
        const selectedMonthName = $(this).data('month-name');
        const formattedValue = selectedMonthName + '/' + year;
        const dbValue = year + '-' + String(selectedMonth).padStart(2, '0');
        
        input.val(formattedValue);
        input.attr('data-db-value', dbValue);
        $('.custom-monthpicker').remove();
    });
    
    $(document).on('click.monthpicker', function(e) {
        if (!$(e.target).closest('.monthpicker, .custom-monthpicker').length) {
            $('.custom-monthpicker').remove();
            $(document).off('click.monthpicker');
        }
    });
}
</script>
@endsection
