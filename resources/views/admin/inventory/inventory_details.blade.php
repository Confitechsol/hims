@extends('layouts.adminLayout')

@section('content')
<style>
    .modal-backdrop.show:nth-of-type(2) { z-index: 1060; }
    #new_patient { z-index: 1070; }
</style>

<div class="content pb-0">
    <div class="row">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                        <div><h4 class="fw-bold mb-0">Inventory Details</h4></div>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="text-end d-flex">
                                <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                   id="addNewStockBtn">
                                   <i class="ti ti-plus me-1"></i>Add Item Stock
                                </a>
                            </div>

                            <a href="#" class="btn btn-outline-primary d-inline-flex align-items-center">
                                <i class="ti ti-menu me-1"></i>Issue Item
                            </a>
                            <a href="#" class="btn btn-outline-primary d-inline-flex align-items-center">
                                <i class="ti ti-menu me-1"></i>Item
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Modal (Add/Edit) --}}
                <div class="modal fade" id="add_item_stock" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <form method="POST" id="itemStockForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="stock_id" name="stock_id" value="">

                                <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                    <h5 class="modal-title" id="modalTitle">Add Item Stock</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row align-items-center gy-3">
                                        {{-- Item Category --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Item Category <span class="text-danger">*</span></label>
                                            <select class="form-select" id="item_category" name="item_category" required>
                                                <option value="">Select Item Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" data-item-head="{{ $category->item_head }}">
                                                        {{ $category->item_category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Item --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Item <span class="text-danger">*</span></label>
                                            <select class="form-select" id="item" name="item" required>
                                                <option value="">Select Item</option>
                                            </select>
                                        </div>

                                        {{-- Supplier --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Supplier <span class="text-danger">*</span></label>
                                            <select class="form-select" id="supplier" name="supplier" required>
                                                <option value="">Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->item_supplier }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Store --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Store</label>
                                            <select id="store" name="store" class="form-select">
                                                <option value="">Select Store</option>
                                                @foreach ($stores as $store)
                                                    <option value="{{ $store->id }}">{{ $store->item_store }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Quantity --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Quantity</label>
                                            <div class="d-flex align-items-center">
                                                <select class="form-select me-2" name="symbol" style="max-width: 70px;">
                                                    <option value="+">+</option>
                                                    <option value="-">-</option>
                                                </select>
                                                <input id="quantity" name="quantity" type="number" class="form-control">
                                            </div>
                                        </div>

                                        {{-- Date --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>

                                        {{-- Attachment --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Attachment</label>
                                            <input type="file" name="attachment" id="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <small class="text-muted">Upload (PDF, JPG, PNG)</small>
                                        </div>

                                        {{-- Message --}}
                                        <div class="col-md-12">
                                            <label class="form-label">Message / Notes</label>
                                            <textarea name="message" id="message" class="form-control" rows="2"></textarea>
                                        </div>

                                        {{-- Capital Equipment Table --}}
                                        <div class="col-12 mt-3 d-none" id="capital_table_div">
                                            <label class="form-label fw-bold">Capital Equipment Details</label>
                                            <table class="table table-bordered align-middle" id="capitalTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Batch No.</th>
                                                        <th>Serial No.</th>
                                                        <th>Purchase Price</th>
                                                        <th>Salvage Value</th>
                                                        <th>Useful Life (Years)</th>
                                                        <th>Annual Depreciation</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                            <button type="button" class="btn btn-sm btn-success addRow" data-type="capital">+ Add Capital Batch</button>
                                        </div>

                                        {{-- Consumables Table --}}
                                        <div class="col-12 mt-3 d-none" id="consumable_table_div">
                                            <label class="form-label fw-bold">Consumable Batch Details</label>
                                            <table class="table table-bordered align-middle" id="consumableTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Batch No.</th>
                                                        <th>Serial No.</th>
                                                        <th>Purchase Price</th>
                                                        <th>Expiry Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                            <button type="button" class="btn btn-sm btn-success addRow" data-type="consumable">+ Add Consumable Batch</button>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Item Stock</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Inventory table below (unchanged) --}}
                <div class="card-body">
                    <div class="table-responsive table-nowrap">
                        <table class="table border">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Supplier</th>
                                    <th>Store</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Purchase Price</th>
                                    <th>Expiry Date</th>
                                    <th>Salvage Value</th>
                                    <th>Useful Life</th>
                                    <th>Depreciation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stocks as $stock)
                                    <tr>
                                        <td>{{ $stock->item->name ?? 'N/A' }}</td>
                                        <td>{{ $stock->itemCategory->item_category ?? 'N/A' }}</td>
                                        <td>{{ $stock->supplier->item_supplier ?? 'N/A' }}</td>
                                        <td>{{ $stock->store->item_store ?? 'N/A' }}</td>
                                        <td>{{ $stock->date ? \Carbon\Carbon::parse($stock->date)->format('d-M-Y') : 'N/A' }}</td>
                                        <td>{{ $stock->message ?? '-' }}</td>
                                        <td>{{ $stock->quantity }}</td>
                                        <td>{{ number_format($stock->purchase_price, 2) }}</td>
                                        <td>{{ $stock->expiry_date ? \Carbon\Carbon::parse($stock->expiry_date)->format('d-M-Y') : '-' }}</td>
                                        <td>{{ $stock->salvage_value ?? '-' }}</td>
                                        <td>{{ $stock->useful_life ?? '-' }}</td>
                                        <td>{{ $stock->annual_depreciation ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="javascript:void(0);" 
                                                   class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill editStockBtn"
                                                   data-id="{{ $stock->id }}" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>

                                                <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                   onclick="if(confirm('Are you sure you want to delete this stock?')) { document.getElementById('delete-stock-{{ $stock->id }}').submit(); }"
                                                   data-bs-toggle="tooltip" title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                                <form id="delete-stock-{{ $stock->id }}" action="{{ route('itemstock.destroy', $stock->id) }}" method="POST" style="display: none;">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="14" class="text-center text-muted">No purchase records found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('itemStockForm');
    const itemCategory = document.getElementById('item_category');
    const itemSelect = document.getElementById('item');
    const capitalTableDiv = document.getElementById('capital_table_div');
    const consumableTableDiv = document.getElementById('consumable_table_div');
    const modalTitle = document.getElementById('modalTitle');
    const saveBtn = document.getElementById('saveBtn');

    // Helper: empty both batch table bodies
    function clearBatchTables() {
        document.querySelector('#capitalTable tbody').innerHTML = '';
        document.querySelector('#consumableTable tbody').innerHTML = '';
    }

    // Category change -> toggle tables and load items for that category
    itemCategory.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const itemHead = selected?.getAttribute('data-item-head') || '';
        const categoryId = this.value;

        capitalTableDiv.classList.add('d-none');
        consumableTableDiv.classList.add('d-none');
        clearBatchTables();

        if (itemHead === 'Capital Equipment') capitalTableDiv.classList.remove('d-none');
        else if (itemHead === 'Consumables') consumableTableDiv.classList.remove('d-none');

        if (categoryId) {
            fetch(`{{ route('get.items', ':id') }}`.replace(':id', categoryId))
                .then(res => res.json())
                .then(data => {
                    itemSelect.innerHTML = '<option value="">Select Item</option>';
                    data.forEach(i => itemSelect.innerHTML += `<option value="${i.id}">${i.name}</option>`);
                })
                .catch(() => {
                    itemSelect.innerHTML = '<option value="">Error loading items</option>';
                });
        } else {
            itemSelect.innerHTML = '<option value="">Select Item</option>';
        }
    });

    // Add new stock -> reset and open modal (create mode)
    $(document).on('click', '#addNewStockBtn', function () {
        // reset form
        form.reset();
        clearBatchTables();
        modalTitle.textContent = 'Add Item Stock';
        saveBtn.textContent = 'Save Item Stock';
        // remove _method if any
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
        form.action = "{{ route('itemstock.store') }}";

        // hide batch tables
        capitalTableDiv.classList.add('d-none');
        consumableTableDiv.classList.add('d-none');

        // show modal
        const modal = new bootstrap.Modal(document.getElementById('add_item_stock'));
        modal.show();
    });

    // Dynamic add/remove rows
    document.addEventListener('click', function (e) {
        const addBtn = e.target.closest('button.addRow');
        const removeBtn = e.target.closest('button.removeRow');

        if (addBtn) {
            const type = addBtn.getAttribute('data-type');
            const tableBody = (type === 'capital') ? document.querySelector('#capitalTable tbody') : document.querySelector('#consumableTable tbody');
            if (!tableBody) return;
            const rowCount = tableBody.querySelectorAll('tr').length;

            if (type === 'capital') {
                tableBody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td><input type="text" name="capital_batches[${rowCount}][batch_no]" class="form-control"></td>
                        <td><input type="text" name="capital_batches[${rowCount}][serial_no]" class="form-control"></td>
                        <td><input type="number" name="capital_batches[${rowCount}][purchase_price]" class="form-control cap-price"></td>
                        <td><input type="number" name="capital_batches[${rowCount}][salvage_value]" class="form-control cap-salvage"></td>
                        <td><input type="number" name="capital_batches[${rowCount}][useful_life]" class="form-control cap-life"></td>
                        <td><input type="number" name="capital_batches[${rowCount}][annual_depreciation]" class="form-control cap-depreciation" readonly></td>
                        <td><button type="button" class="btn btn-sm btn-danger removeRow">×</button></td>
                    </tr>
                `);
            } else {
                tableBody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td><input type="text" name="consumable_batches[${rowCount}][batch_no]" class="form-control"></td>
                        <td><input type="text" name="consumable_batches[${rowCount}][serial_no]" class="form-control"></td>
                        <td><input type="number" name="consumable_batches[${rowCount}][purchase_price]" class="form-control"></td>
                        <td><input type="date" name="consumable_batches[${rowCount}][expiry_date]" class="form-control"></td>
                        <td><button type="button" class="btn btn-sm btn-danger removeRow">×</button></td>
                    </tr>
                `);
            }
            reindexRows(type);
        }

        if (removeBtn) {
            const row = removeBtn.closest('tr');
            if (!row) return;
            const tbody = row.closest('tbody');
            row.remove();
            const parentTable = tbody.closest('table');
            if (!parentTable) return;
            const id = parentTable.id;
            if (id === 'capitalTable') reindexRows('capital');
            else if (id === 'consumableTable') reindexRows('consumable');
        }
    });

    function reindexRows(type) {
        if (type === 'capital') {
            document.querySelectorAll('#capitalTable tbody tr').forEach((tr, idx) => {
                tr.querySelectorAll('input').forEach((input) => {
                    const name = input.getAttribute('name') || '';
                    input.setAttribute('name', name.replace(/capital_batches\[\d+\]/, `capital_batches[${idx}]`));
                });
            });
        } else {
            document.querySelectorAll('#consumableTable tbody tr').forEach((tr, idx) => {
                tr.querySelectorAll('input').forEach((input) => {
                    const name = input.getAttribute('name') || '';
                    input.setAttribute('name', name.replace(/consumable_batches\[\d+\]/, `consumable_batches[${idx}]`));
                });
            });
        }
    }

    // Auto-calc depreciation on capital rows
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('cap-price') || e.target.classList.contains('cap-salvage') || e.target.classList.contains('cap-life')) {
            const row = e.target.closest('tr');
            const purchase = parseFloat(row.querySelector('.cap-price').value) || 0;
            const salvage = parseFloat(row.querySelector('.cap-salvage').value) || 0;
            const life = parseFloat(row.querySelector('.cap-life').value) || 0;
            const depField = row.querySelector('.cap-depreciation');
            depField.value = (purchase > 0 && life > 0) ? ((purchase - salvage) / life).toFixed(2) : '';
        }
    });

    // EDIT handler: fetch stock & populate modal
    $(document).on('click', '.editStockBtn', function () {
        const id = $(this).data('id');

        $.ajax({
            url: "{{ route('itemstock.edit', ':id') }}".replace(':id', id),
            method: 'GET',
            success: function (data) {
                // `data` contains: stock, categories, items, suppliers, stores (per your earlier JSON)
                if (!data || !data.stock) {
                    return alert('Stock not found.');
                }

                const stock = data.stock;

                // show modal
                const modal = new bootstrap.Modal(document.getElementById('add_item_stock'));
                modal.show();

                modalTitle.textContent = 'Edit Item Stock';
                saveBtn.textContent = 'Update Item Stock';

                // set form action to update route
                form.action = "{{ route('itemstock.update', ':id') }}".replace(':id', id);

                // add PUT method input if not present
                if (!form.querySelector('input[name="_method"]')) {
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                }

                // populate categories select (use data.categories if present)
                if (Array.isArray(data.categories)) {
                    const catSel = document.getElementById('item_category');
                    catSel.innerHTML = '<option value="">Select Item Category</option>';
                    data.categories.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.text = c.item_category;
                        // if server returned item_head with categories then keep data-item-head attribute - but fallback keep existing
                        // We can't get item_head from this array here, but the selected option already exists in your view.
                        catSel.appendChild(opt);
                    });
                }

                // populate items select (data.items) and select the stock.item
                if (Array.isArray(data.items)) {
                    itemSelect.innerHTML = '<option value="">Select Item</option>';
                    data.items.forEach(i => {
                        const selected = (i.id == stock.item_id) ? 'selected' : '';
                        itemSelect.innerHTML += `<option value="${i.id}" ${selected}>${i.name}</option>`;
                    });
                } else {
                    // fallback: set the single item
                    itemSelect.innerHTML = `<option value="${stock.item.id}" selected>${stock.item.name}</option>`;
                }

                // populate suppliers & stores selects using server arrays (if exist)
                if (Array.isArray(data.suppliers)) {
                    const sSel = document.getElementById('supplier');
                    sSel.innerHTML = '<option value="">Select Supplier</option>';
                    data.suppliers.forEach(s => sSel.innerHTML += `<option value="${s.id}" ${s.id == stock.supplier_id ? 'selected' : ''}>${s.item_supplier}</option>`);
                } else {
                    $('#supplier').val(stock.supplier_id);
                }

                if (Array.isArray(data.stores)) {
                    const stSel = document.getElementById('store');
                    stSel.innerHTML = '<option value="">Select Store</option>';
                    data.stores.forEach(s => stSel.innerHTML += `<option value="${s.id}" ${s.id == stock.store_id ? 'selected' : ''}>${s.item_store}</option>`);
                } else {
                    $('#store').val(stock.store_id);
                }

                // set simple fields
                $('#item_category').val(stock.item_category_id).trigger('change');
                $('#supplier').val(stock.supplier_id);
                $('#store').val(stock.store_id);
                $('#quantity').val(stock.quantity);
                $('#date').val(stock.date ?? '');
                $('#message').val(stock.description ?? '');

                $('#stock_id').val(stock.id);

                // clear previous batch rows
                clearBatchTables();

                // determine which batch table to show:
                // Use stock.item_category.item_head (note server returns item_category not itemCategory)
                const itemCategoryObj = stock.item_category || stock.itemCategory || null;
                const head = itemCategoryObj ? itemCategoryObj.item_head : null;

                // If categories array or stock.batches can indicate per-batch fields:
                // decide by head OR by presence of salvage_value / expiry_date on batches
                const batches = Array.isArray(stock.batches) ? stock.batches : [];

                let decided = null;
                if (head === 'Capital Equipment') decided = 'capital';
                else if (head === 'Consumables') decided = 'consumable';
                else if (batches.length > 0) {
                    // fallback: inspect first batch row fields
                    const first = batches[0];
                    if (first && (first.salvage_value !== null || first.useful_life !== null)) decided = 'capital';
                    else if (first && first.expiry_date) decided = 'consumable';
                }

                if (decided === 'capital') {
                    populateCapitalBatches(batches);
                    capitalTableDiv.classList.remove('d-none');
                    consumableTableDiv.classList.add('d-none');
                } else if (decided === 'consumable') {
                    populateConsumableBatches(batches);
                    consumableTableDiv.classList.remove('d-none');
                    capitalTableDiv.classList.add('d-none');
                } else {
                    // if no batches, show nothing (user can add)
                    capitalTableDiv.classList.add('d-none');
                    consumableTableDiv.classList.add('d-none');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Error fetching stock data.');
            }
        });
    });

    // Populate helpers
    function populateCapitalBatches(batches) {
        const tbody = document.querySelector('#capitalTable tbody');
        tbody.innerHTML = '';
        batches.forEach((b, i) => {
            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td><input type="text" name="capital_batches[${i}][batch_no]" value="${escapeHtml(b.batch_no ?? '')}" class="form-control"></td>
                    <td><input type="text" name="capital_batches[${i}][serial_no]" value="${escapeHtml(b.serial_no ?? '')}" class="form-control"></td>
                    <td><input type="number" name="capital_batches[${i}][purchase_price]" value="${b.purchase_price ?? ''}" class="form-control cap-price"></td>
                    <td><input type="number" name="capital_batches[${i}][salvage_value]" value="${b.salvage_value ?? ''}" class="form-control cap-salvage"></td>
                    <td><input type="number" name="capital_batches[${i}][useful_life]" value="${b.useful_life ?? ''}" class="form-control cap-life"></td>
                    <td><input type="number" name="capital_batches[${i}][annual_depreciation]" value="${b.annual_depreciation ?? ''}" class="form-control cap-depreciation" readonly></td>
                    <td><button type="button" class="btn btn-sm btn-danger removeRow">×</button></td>
                </tr>
            `);
        });
        reindexRows('capital');
    }

    function populateConsumableBatches(batches) {
        const tbody = document.querySelector('#consumableTable tbody');
        tbody.innerHTML = '';
        batches.forEach((b, i) => {
            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td><input type="text" name="consumable_batches[${i}][batch_no]" value="${escapeHtml(b.batch_no ?? '')}" class="form-control"></td>
                    <td><input type="text" name="consumable_batches[${i}][serial_no]" value="${escapeHtml(b.serial_no ?? '')}" class="form-control"></td>
                    <td><input type="number" name="consumable_batches[${i}][purchase_price]" value="${b.purchase_price ?? ''}" class="form-control"></td>
                    <td><input type="date" name="consumable_batches[${i}][expiry_date]" value="${b.expiry_date ?? ''}" class="form-control"></td>
                    <td><button type="button" class="btn btn-sm btn-danger removeRow">×</button></td>
                </tr>
            `);
        });
        reindexRows('consumable');
    }

    // small helper to avoid breaking HTML injection
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"'`=\/]/g, function (s) {
            return ({
                '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
            })[s];
        });
    }
});
</script>

@endsection
