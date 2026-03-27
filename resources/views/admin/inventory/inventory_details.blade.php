@extends('layouts.adminLayout')

@section('content')
@php
    $itemStockPermCatId = permCatId('Item Stock');
    $canAddItemStock    = $itemStockPermCatId ? canAdd($itemStockPermCatId) : false;
    $canEditItemStock   = $itemStockPermCatId ? canEdit($itemStockPermCatId) : false;
    $canDeleteItemStock = $itemStockPermCatId ? canDelete($itemStockPermCatId) : false;

    $issueItemPermCatId = permCatId('Issue Item');
    $canViewIssueItem   = $issueItemPermCatId ? canView($issueItemPermCatId) : false;

    $itemPermCatId = permCatId('Item');
    $canViewItem   = $itemPermCatId ? canView($itemPermCatId) : false;
@endphp
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
                            @if ($canAddItemStock)
                                <div class="text-end d-flex">
                                    <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                       id="addNewStockBtn">
                                       <i class="ti ti-plus me-1"></i>Add Item Stock
                                    </a>
                                </div>
                            @endif

                            @if ($canViewIssueItem)
                                <a href="{{ route('issue-items')}}" class="btn btn-outline-primary d-inline-flex align-items-center">
                                    <i class="ti ti-menu me-1"></i>Issue Item
                                </a>
                            @endif

                            @if ($canViewItem)
                                <a href="{{ route('items')}}" class="btn btn-outline-primary d-inline-flex align-items-center">
                                    <i class="ti ti-menu me-1"></i>Item
                                </a>
                            @endif
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

                                        <td>
                                            @if ($canEditItemStock || $canDeleteItemStock)
                                                <div class="btn-group">
                                                    @if ($canEditItemStock)
                                                        <a href="javascript:void(0);" 
                                                           class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill editStockBtn"
                                                           data-id="{{ $stock->id }}" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                    @endif

                                                    @if ($canDeleteItemStock)
                                                        <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                           onclick="if(confirm('Are you sure you want to delete this stock?')) { document.getElementById('delete-stock-{{ $stock->id }}').submit(); }"
                                                           data-bs-toggle="tooltip" title="Delete">
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                        <form id="delete-stock-{{ $stock->id }}" action="{{ route('itemstock.destroy', $stock->id) }}" method="POST" style="display: none;">
                                                            @csrf @method('DELETE')
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
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
document.addEventListener('DOMContentLoaded', () => {

    /* ===============================
       DOM REFERENCES
    =============================== */
    const form                = document.getElementById('itemStockForm');
    const categorySelect      = document.getElementById('item_category');
    const itemSelect          = document.getElementById('item');
    const capitalDiv          = document.getElementById('capital_table_div');
    const consumableDiv       = document.getElementById('consumable_table_div');
    const modalEl             = document.getElementById('add_item_stock');
    const modalTitle          = document.getElementById('modalTitle');
    const saveBtn             = document.getElementById('saveBtn');

    const capitalTbody        = document.querySelector('#capitalTable tbody');
    const consumableTbody     = document.querySelector('#consumableTable tbody');

    /* ===============================
       HELPERS
    =============================== */

    const clearBatches = () => {
        capitalTbody.innerHTML = '';
        consumableTbody.innerHTML = '';
    };

    const hideBatchTables = () => {
        capitalDiv.classList.add('d-none');
        consumableDiv.classList.add('d-none');
    };

    const showBatchTable = (type) => {
        hideBatchTables();
        if (type === 'capital') capitalDiv.classList.remove('d-none');
        if (type === 'consumable') consumableDiv.classList.remove('d-none');
    };

    const escapeHtml = (str = '') =>
        String(str).replace(/[&<>"'`=\/]/g, s =>
            ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'})[s]
        );

    /* ===============================
       FETCH ITEMS BY CATEGORY
    =============================== */
    const loadItems = (categoryId, selectedItemId = null) => {
        itemSelect.innerHTML = '<option value="">Loading...</option>';

        return fetch(`{{ route('get.items', ':id') }}`.replace(':id', categoryId))
            .then(r => r.json())
            .then(items => {
                itemSelect.innerHTML = '<option value="">Select Item</option>';
                items.forEach(i => {
                    const opt = document.createElement('option');
                    opt.value = i.id;
                    opt.textContent = i.name;
                    if (selectedItemId && i.id == selectedItemId) {
                        opt.selected = true;
                    }
                    itemSelect.appendChild(opt);
                });
            });
    };

    /* ===============================
       CATEGORY CHANGE
    =============================== */
    $(document).on('change', '#item_category', function () {

    const selected = this.options[this.selectedIndex];
    const head = selected ? selected.getAttribute('data-item-head') : null;

    console.log('ITEM HEAD:', head); // ✅ WILL SHOW NOW

    clearBatches();
    hideBatchTables();

    if (head === 'Capital Equipment') {
        showBatchTable('capital');
    }

    if (head === 'Consumables') {
        showBatchTable('consumable');
    }

    if (this.value) {
        loadItems(this.value);
    } else {
        itemSelect.innerHTML = '<option value="">Select Item</option>';
    }
});


    /* ===============================
       ADD MODE
    =============================== */
    $(document).on('click', '#addNewStockBtn', () => {

        form.reset();
        clearBatches();
        hideBatchTables();

        modalTitle.textContent = 'Add Item Stock';
        saveBtn.textContent = 'Save Item Stock';

        form.action = "{{ route('itemstock.store') }}";

        const method = form.querySelector('input[name="_method"]');
        if (method) method.remove();

        new bootstrap.Modal(modalEl).show();
    });

    /* ===============================
       EDIT MODE
    =============================== */
    $(document).on('click', '.editStockBtn', function () {

        const id = $(this).data('id');

        $.get(`{{ route('itemstock.edit', ':id') }}`.replace(':id', id), data => {

            const stock = data.stock;
            if (!stock) return alert('Stock not found');

            modalTitle.textContent = 'Edit Item Stock';
            saveBtn.textContent = 'Update Item Stock';
            form.action = `{{ route('itemstock.update', ':id') }}`.replace(':id', id);

            if (!form.querySelector('input[name="_method"]')) {
                form.insertAdjacentHTML('beforeend',
                    `<input type="hidden" name="_method" value="PUT">`
                );
            }

            /* ---- SIMPLE FIELDS ---- */
            $('#supplier').val(stock.supplier_id);
            $('#store').val(stock.store_id);
            $('#quantity').val(stock.quantity);
            $('#date').val(stock.date ?? '');
            $('#message').val(stock.description ?? '');
            $('select[name="symbol"]').val(stock.symbol ?? '+');

            clearBatches();
            hideBatchTables();

            /* ---- CATEGORY & ITEMS ---- */
            $('#item_category').val(stock.item_category_id);

            loadItems(stock.item_category_id, stock.item_id).then(() => {

                const head = stock.item_category?.item_head;
                const batches = stock.batches || [];

                if (head === 'Capital Equipment') {
                    showBatchTable('capital');
                    populateCapital(batches);
                }

                if (head === 'Consumables') {
                    showBatchTable('consumable');
                    populateConsumable(batches);
                }
            });

            new bootstrap.Modal(modalEl).show();
        });
    });

    /* ===============================
       ROW ADD / REMOVE
    =============================== */
    document.addEventListener('click', e => {

        const addBtn = e.target.closest('.addRow');
        const removeBtn = e.target.closest('.removeRow');

        if (addBtn) {
            const type = addBtn.dataset.type;
            type === 'capital' ? addCapitalRow() : addConsumableRow();
        }

        if (removeBtn) {
            removeBtn.closest('tr').remove();
            reindex(typeFromRow(removeBtn));
        }
    });

    const typeFromRow = btn =>
        btn.closest('#capitalTable') ? 'capital' : 'consumable';

    /* ===============================
       CAPITAL
    =============================== */
    const addCapitalRow = (b = {}, i = capitalTbody.children.length) => {
        capitalTbody.insertAdjacentHTML('beforeend', `
            <tr>
                <td><input name="capital_batches[${i}][batch_no]" value="${escapeHtml(b.batch_no)}" class="form-control"></td>
                <td><input name="capital_batches[${i}][serial_no]" value="${escapeHtml(b.serial_no)}" class="form-control"></td>
                <td><input name="capital_batches[${i}][purchase_price]" value="${b.purchase_price ?? ''}" class="form-control cap-price"></td>
                <td><input name="capital_batches[${i}][salvage_value]" value="${b.salvage_value ?? ''}" class="form-control cap-salvage"></td>
                <td><input name="capital_batches[${i}][useful_life]" value="${b.useful_life ?? ''}" class="form-control cap-life"></td>
                <td><input name="capital_batches[${i}][annual_depreciation]" value="${b.annual_depreciation ?? ''}" class="form-control cap-dep" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger removeRow">×</button></td>
            </tr>
        `);
    };

    const populateCapital = batches => {
        capitalTbody.innerHTML = '';
        batches.forEach(addCapitalRow);
    };

    /* ===============================
       CONSUMABLE
    =============================== */
    const addConsumableRow = (b = {}, i = consumableTbody.children.length) => {
        consumableTbody.insertAdjacentHTML('beforeend', `
            <tr>
                <td><input name="consumable_batches[${i}][batch_no]" value="${escapeHtml(b.batch_no)}" class="form-control"></td>
                <td><input name="consumable_batches[${i}][serial_no]" value="${escapeHtml(b.serial_no)}" class="form-control"></td>
                <td><input name="consumable_batches[${i}][purchase_price]" value="${b.purchase_price ?? ''}" class="form-control"></td>
                <td><input type="date" name="consumable_batches[${i}][expiry_date]" value="${b.expiry_date ?? ''}" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-danger removeRow">×</button></td>
            </tr>
        `);
    };

    const populateConsumable = batches => {
        consumableTbody.innerHTML = '';
        batches.forEach(addConsumableRow);
    };

    /* ===============================
       REINDEX
    =============================== */
    const reindex = type => {
        document.querySelectorAll(`#${type}Table tbody tr`).forEach((tr, i) => {
            tr.querySelectorAll('input').forEach(inp => {
                inp.name = inp.name.replace(/\[\d+\]/, `[${i}]`);
            });
        });
    };

    /* ===============================
       DEPRECIATION
    =============================== */
    document.addEventListener('input', e => {
        if (!e.target.closest('.cap-price,.cap-salvage,.cap-life')) return;

        const row = e.target.closest('tr');
        const p = +row.querySelector('.cap-price')?.value || 0;
        const s = +row.querySelector('.cap-salvage')?.value || 0;
        const l = +row.querySelector('.cap-life')?.value || 0;

        row.querySelector('.cap-dep').value =
            (p && l) ? ((p - s) / l).toFixed(2) : '';
    });

});
</script>



@endsection
