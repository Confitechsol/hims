<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->supplier); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6 text-end">
                        <span style="color: #750096"><strong>Purchase Date:</strong> <?php echo e(now()->format('m/d/Y g:i A')); ?></span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                
                <?php if(isset($categories)): ?>
                    <div class="alert alert-info">
                        <strong>Debug:</strong> Categories loaded: <?php echo e($categories->count()); ?> categories found
                        <br>Categories: 
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($cat->medicine_category); ?><?php echo e(!$loop->last ? ', ' : ''); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <strong>Debug:</strong> Categories variable not set!
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('pharmacy.purchase.store')); ?>" method="POST" id="purchaseForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <input type="hidden" name="date" value="<?php echo e(now()->format('Y-m-d H:i:s')); ?>">
                    
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Bill No</label>
                            <input type="text" name="invoice_no" class="form-control" value="<?php echo e(old('invoice_no')); ?>">
                        </div>
                    </div>

                    
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
                                        <tr class="medicine-row">
                                            <td>
                                                <select class="form-select medicine-category" required>
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->medicine_category); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="medicines[0][pharmacy_id]" class="form-select medicine-name" required>
                                                    <option value="">Select</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="medicines[0][batch_no]" class="form-control" required></td>
                                            <td>
                                                <input type="text" name="medicines[0][expiry]" class="form-control expiry-date monthpicker" required placeholder="Mar/2026" readonly>
                                            </td>
                                            <td><input type="number" name="medicines[0][mrp]" class="form-control mrp" step="0.01" min="0" required></td>
                                            <td><input type="number" name="medicines[0][batch_amount]" class="form-control batch-amount" step="0.01" min="0"></td>
                                            <td><input type="number" name="medicines[0][sale_rate]" class="form-control sale-price" step="0.01" min="0" required></td>
                                            <td><input type="text" class="form-control packing-qty"></td>
                                            <td><input type="number" name="medicines[0][quantity]" class="form-control quantity" min="1" required></td>
                                            <td><input type="number" name="medicines[0][purchase_price]" class="form-control purchase-price" step="0.01" min="0" required></td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="medicines[0][tax]" class="form-control tax" step="0.01" min="0" value="0">
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3"><?php echo e(old('note')); ?></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Attach Document</label>
                                <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Allowed: PDF, JPG, JPEG, PNG (Max: 2MB)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Total (INR)</th>
                                        <td class="text-end">
                                            <input type="number" name="total" id="total" class="form-control text-end" value="0" readonly>
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
                                            <input type="number" name="discount" id="discount" class="form-control text-end" step="0.01" min="0" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tax (INR)</th>
                                        <td class="text-end">
                                            <input type="number" id="tax_total" class="form-control text-end" value="0" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold">Net Amount (INR)</th>
                                        <td class="text-end">
                                            <input type="number" id="net_amount" class="form-control text-end fw-bold" value="0" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Card">Card</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <div id="cheque_fields" style="display: none;">
                                <div class="form-group mb-3">
                                    <label class="form-label">Cheque No</label>
                                    <input type="text" name="cheque_no" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Cheque Date</label>
                                    <input type="date" name="cheque_date" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Payment Amount (INR)</label>
                                <input type="number" class="form-control" step="0.01" min="0">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Payment Note</label>
                                <textarea name="payment_note" class="form-control" rows="2"><?php echo e(old('payment_note')); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?php echo e(route('pharmacy.purchase.index')); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>Generate Bill
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let rowIndex = 1;
const allMedicines = <?php echo json_encode($medicines, 15, 512) ?>;

$(document).ready(function() {
    console.log('Page loaded with jQuery!');
    
    // Add new medicine row
    $('#addRow').on('click', function() {
        addMedicineRow();
    });

    // Remove medicine row
    $(document).on('click', '.remove-row', function() {
        if ($('.medicine-row').length > 1) {
            $(this).closest('.medicine-row').remove();
            calculateTotals();
        }
    });

    // Medicine category change handler using jQuery event delegation
    $(document).on('change', '.medicine-category', function() {
        console.log('Category changed (jQuery)!', $(this).val());
        handleCategoryChange(this);
    });

    // Calculate row amount on quantity/price/tax change
    $(document).on('input', '.quantity, .purchase-price, .tax', function() {
        const row = $(this).closest('tr')[0];
        calculateRowAmount(row);
        calculateTotals();
    });

    // Discount percentage change
    document.getElementById('discount_percent').addEventListener('input', function() {
        const total = parseFloat(document.getElementById('total').value) || 0;
        const discountPercent = parseFloat(this.value) || 0;
        const discountAmount = (total * discountPercent) / 100;
        document.getElementById('discount').value = discountAmount.toFixed(2);
        calculateTotals();
    });

    // Discount amount change
    document.getElementById('discount').addEventListener('input', function() {
        calculateTotals();
    });

    // Payment mode change
    document.getElementById('payment_mode').addEventListener('change', function() {
        const chequeFields = document.getElementById('cheque_fields');
        if (this.value === 'Cheque') {
            chequeFields.style.display = 'block';
        } else {
            chequeFields.style.display = 'none';
        }
    });

    // Form validation
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        const supplierId = document.getElementById('supplier_id').value;
        if (!supplierId) {
            e.preventDefault();
            alert('Please select a supplier');
            return false;
        }

        // Add supplier_id to form data
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'supplier_id';
        hiddenInput.value = supplierId;
        this.appendChild(hiddenInput);
    });
});

function addMedicineRow() {
    const tbody = document.getElementById('medicineTableBody');
    const newRow = document.createElement('tr');
    newRow.classList.add('medicine-row');
    
    newRow.innerHTML = `
        <td>
            <select class="form-select medicine-category" required>
                <option value="">Select</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->medicine_category); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        <td><input type="text" class="form-control packing-qty"></td>
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
    `;
    
    tbody.appendChild(newRow);
    rowIndex++;
    // jQuery event delegation handles category change and monthpicker automatically
}

function calculateRowAmount(row) {
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const purchasePrice = parseFloat(row.querySelector('.purchase-price').value) || 0;
    const tax = parseFloat(row.querySelector('.tax').value) || 0;
    
    const amount = quantity * purchasePrice;
    const batchAmount = amount;
    
    row.querySelector('.amount').value = amount.toFixed(2);
    row.querySelector('.batch-amount').value = batchAmount.toFixed(2);
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
    
    const apiUrl = '<?php echo e(route("pharmacy.purchase.api.medicines-by-category")); ?>?category_id=' + categoryId;
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

// Custom Month/Year Picker
$(document).on('click', '.monthpicker', function() {
    const input = $(this);
    const currentValue = input.val();
    
    // Parse current value or use current date
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
    // Remove any existing picker
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
    
    // Year navigation
    picker.find('.year-prev').on('click', function() {
        year--;
        showMonthPicker(input, year, month);
    });
    
    picker.find('.year-next').on('click', function() {
        year++;
        showMonthPicker(input, year, month);
    });
    
    // Month selection
    picker.find('.month-btn').on('click', function() {
        const selectedMonth = $(this).data('month');
        const selectedMonthName = $(this).data('month-name');
        const formattedValue = selectedMonthName + '/' + year;
        const dbValue = year + '-' + String(selectedMonth).padStart(2, '0');
        
        input.val(formattedValue);
        input.attr('data-db-value', dbValue);
        $('.custom-monthpicker').remove();
    });
    
    // Close picker when clicking outside
    $(document).on('click.monthpicker', function(e) {
        if (!$(e.target).closest('.monthpicker, .custom-monthpicker').length) {
            $('.custom-monthpicker').remove();
            $(document).off('click.monthpicker');
        }
    });
}

// Update form submission to use data-db-value
$(document).on('submit', '#purchaseForm', function() {
    $('.monthpicker').each(function() {
        const dbValue = $(this).attr('data-db-value');
        if (dbValue) {
            $(this).val(dbValue); // Submit as YYYY-MM for backend
        }
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pharmacy\purchase\create.blade.php ENDPATH**/ ?>