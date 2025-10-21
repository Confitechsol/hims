@extends('layouts.adminLayout')

@section('content')
    <!-- ========================
        Start Page Content
    ========================= -->

    {{-- <div class="page-wrapper"> --}}

        <style>
            .modal-backdrop.show:nth-of-type(2) {
                z-index: 1060;
                /* higher backdrop for nested modal */
            }

            #new_patient {
                z-index: 1070;
                /* ensure new modal is above the first */
            }
        </style>

        <!-- Start Content -->
        <div class="content pb-0">


            <!-- row start -->
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                                <div>
                                    <h4 class="fw-bold mb-0">Inventory Details</h4>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <div class="text-end d-flex">
                                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                            data-bs-toggle="modal" data-bs-target="#add_item_stock"><i
                                                class="ti ti-plus me-1"></i>Add Item Stock</a>
                                               
                                    </div>
                                    <!-- First Modal -->
                                    <div class="modal fade" id="add_item_stock" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('items.store') }}" id="itemStockForm" enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title">Add Item Stock</h5>
                                                         <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row align-items-center gy-3">

                                                            {{-- Item Category --}}
                                                            <div class="col-md-3">
                                                                <label for="item_category" class="form-label">Item Category <span class="text-danger">*</span></label>
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
                                                                <label for="item" class="form-label">Item</label>
                                                                <select class="form-select" id="item" name="item" required>
                                                                    <option value="">Select Item</option>
                                                                </select>
                                                            </div>

                                                            {{-- Supplier --}}
                                                            <div class="col-md-3">
                                                                <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="supplier" name="supplier" required>
                                                                    <option value="">Select Supplier</option>
                                                                    @foreach ($suppliers as $supplier)
                                                                        <option value="{{ $supplier->id }}">{{ $supplier->item_supplier }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{-- Store --}}
                                                            <div class="col-md-3">
                                                                <label for="store" class="form-label fw-bold">Store</label>
                                                                <select id="store" name="store" class="form-select">
                                                                    <option value="">Select Store</option>
                                                                    @foreach ($stores as $store)
                                                                        <option value="{{ $store->id }}">{{ $store->item_store }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{-- Quantity --}}
                                                            <div class="col-md-3">
                                                                <label for="quantity" class="form-label">Quantity</label>
                                                                <div class="d-flex align-items-center">
                                                                    <select class="form-select me-2" name="symbol" style="max-width: 70px;">
                                                                        <option value="+">+</option>
                                                                        <option value="-">-</option>
                                                                    </select>
                                                                    <input id="quantity" name="quantity" type="number" class="form-control" placeholder="">
                                                                </div>
                                                            </div>

                                                            {{-- Purchase Price --}}
                                                            <div class="col-md-3">
                                                                <label for="purchase_price" class="form-label">Purchase Price</label>
                                                                <input type="number" id="purchase_price" name="purchase_price" class="form-control" required>
                                                            </div>

                                                            {{-- Date --}}
                                                            <div class="col-md-3">
                                                                <label for="date" class="form-label">Date</label>
                                                                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                                                            </div>

                                                            {{-- Message --}}
                                                            <div class="col-md-9">
                                                                <label for="message" class="form-label">Message</label>
                                                                <textarea name="message" id="message" class="form-control" rows="2"></textarea>
                                                            </div>

                                                            {{-- Attachment --}}
                                                            <div class="col-md-3">
                                                                <label for="attachment" class="form-label">Attachment</label>
                                                                <input type="file" name="attachment" id="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                                                <small class="text-muted">Upload (PDF, JPG, PNG)</small>
                                                            </div>

                                                            {{-- Expiry Date --}}
                                                            <div class="col-md-3 d-none" id="expiry_date_div">
                                                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                                                <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                                                            </div>

                                                            {{-- Salvage Value --}}
                                                            <div class="col-md-3 d-none" id="salvage_value_div">
                                                                <label for="salvage_value" class="form-label">Salvage Value</label>
                                                                <input type="number" name="salvage_value" id="salvage_value" class="form-control">
                                                            </div>

                                                            {{-- Useful Life --}}
                                                            <div class="col-md-3 d-none" id="useful_life_div">
                                                                <label for="useful_life" class="form-label">Useful Life (in years)</label>
                                                                <input type="number" name="useful_life" id="useful_life" class="form-control">
                                                            </div>

                                                            {{-- Annual Depreciation --}}
                                                            <div class="col-md-3 d-none" id="annual_depreciation_div">
                                                                <label for="annual_depreciation" class="form-label">Annual Depreciation</label>
                                                                <input type="number" name="annual_depreciation" id="annual_depreciation" class="form-control" readonly>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save & Print</button>
                                                        <button type="button" id="saveOnly" class="btn btn-secondary">Save</button>
                                                    </div>
                                                </form>



                                            </div>
                                        </div>
                                    </div>

                                                    
                            
                                                
                                           
                                    <a href="#"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Issue Item</a>
                                    <a href="#"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Item</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Table start -->
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
                            <!-- <a href="{{ route('itemstock.edit', $stock->id) }}" class="btn btn-sm btn-warning">Edit</a> -->
                            <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill editStockBtn" 
                                data-id="{{ $stock->id }}" data-bs-toggle="tooltip" title="Edit">
                                    <i class="ti ti-pencil"></i>
                            </a>
                            <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill " 
                                onclick="if(confirm('Are you sure you want to delete this stock ?')) { document.getElementById('delete-source-{{ $stock->id }}').submit(); }"
                                  data-bs-toggle="tooltip" title="delete">
                                    <i class="ti ti-trash"></i>
                            </a>
                                <form id="delete-source-{{ $stock->id }}"  action="{{ route('itemstock.destroy', $stock->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center text-muted">No purchase records found</td>
                </tr>
            @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table end -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
         <!-- Edit Modal (nested) -->
        <div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="editStockForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                            <h5 class="modal-title" id="editStockModalLabel">Edit Item Stock</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_id">

                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label">Item Category</label>
                                    <select class="form-select" id="edit_item_category" name="item_category" required></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Item</label>
                                    <select class="form-select" id="edit_item" name="item" required></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Supplier</label>
                                    <select class="form-select" id="edit_supplier" name="supplier" required></select>
                                </div>
                                <div class="col-md-3">
                                    <label for="store" class="form-label fw-bold">Store</label>
                                    <select class="form-select" id="edit_store" name="store" ></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="edit_quantity" name="quantity" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Purchase Price</label>
                                    <input type="number" class="form-control" id="edit_purchase_price" name="purchase_price" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" id="edit_date" name="date">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-control" id="edit_message" name="message"></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Attachment (optional)</label>
                                    <input type="file" class="form-control" name="attachment">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>


<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const itemCategory = document.getElementById('item_category');
    const itemSelect = document.getElementById('item');

    itemCategory.addEventListener('change', function () {
        const categoryId = this.value;
        alert(categoryId);

        itemSelect.innerHTML = '<option value="">Loading...</option>';

        if (categoryId) {
            // Use Laravel route helper
            fetch(`{{ route('get.items', ':id') }}`.replace(':id', categoryId))
                .then(res => res.json())
                .then(data => {
                    itemSelect.innerHTML = '<option value="">Select Item</option>';
                    data.forEach(item => {
                        itemSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(() => {
                    itemSelect.innerHTML = '<option value="">Error loading items</option>';
                });
        } else {
            itemSelect.innerHTML = '<option value="">Select Item</option>';
        }
    });
});
</script> -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const itemCategory = document.getElementById('item_category');
    const itemSelect = document.getElementById('item');
    const quantity = document.getElementById('quantity');
    const purchasePrice = document.getElementById('purchase_price');
    const salvageValue = document.getElementById('salvage_value');
    const usefulLifeInput = document.getElementById('useful_life');
    const annualDepreciation = document.getElementById('annual_depreciation');

    const expiryDiv = document.getElementById('expiry_date_div');
    const salvageDiv = document.getElementById('salvage_value_div');
    const usefulDiv = document.getElementById('useful_life_div');
    const depreciationDiv = document.getElementById('annual_depreciation_div');

    // Handle Category Change
    itemCategory.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const itemHead = selectedOption.getAttribute('data-item-head');
        const categoryId = this.value;

        [expiryDiv, salvageDiv, usefulDiv, depreciationDiv].forEach(div => div.classList.add('d-none'));

        if (itemHead === 'Consumables') {
            expiryDiv.classList.remove('d-none');
        } else if (itemHead === 'Capital Equipment') {
            salvageDiv.classList.remove('d-none');
            usefulDiv.classList.remove('d-none');
            depreciationDiv.classList.remove('d-none');
        }

        // Fetch dynamic items
        if (categoryId) {
            fetch(`{{ route('get.items', ':id') }}`.replace(':id', categoryId))
                .then(res => res.json())
                .then(data => {
                    itemSelect.innerHTML = '<option value="">Select Item</option>';
                    data.forEach(item => {
                        itemSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(() => {
                    itemSelect.innerHTML = '<option value="">Error loading items</option>';
                });
        } else {
            itemSelect.innerHTML = '<option value="">Select Item</option>';
        }
    });

    // Auto-calculate Depreciation
    function calculateDepreciation() {
        const purchase = parseFloat(purchasePrice.value) || 0;
        const salvage = parseFloat(salvageValue.value) || 0;
        const quant = parseFloat(quantity.value) || 0;
        const usefulLife = parseFloat(usefulLifeInput.value) || 0;

        if (purchase > 0 && salvage >= 0 && usefulLife > 0) {
            const depreciation = (((quant*purchase) - salvage) / usefulLife).toFixed(2);
            annualDepreciation.value = depreciation;
        } else {
            annualDepreciation.value = '';
        }
    }

    [purchasePrice, salvageValue, usefulLifeInput].forEach(input => {
        input.addEventListener('input', calculateDepreciation);
    });

    // Save only button
    document.getElementById('saveOnly').addEventListener('click', function () {
        document.getElementById('itemStockForm').submit();
    });
});
</script> -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemCategory = document.getElementById('item_category');
    const itemSelect = document.getElementById('item');
    const quantity = document.getElementById('quantity');
    const purchasePrice = document.getElementById('purchase_price');
    const salvageValue = document.getElementById('salvage_value');
    const usefulLifeInput = document.getElementById('useful_life');
    const annualDepreciation = document.getElementById('annual_depreciation');

    const expiryDiv = document.getElementById('expiry_date_div');
    const salvageDiv = document.getElementById('salvage_value_div');
    const usefulDiv = document.getElementById('useful_life_div');
    const depreciationDiv = document.getElementById('annual_depreciation_div');

    // Handle Category Change
    itemCategory.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const itemHead = selectedOption.getAttribute('data-item-head');
        const categoryId = this.value;

        [expiryDiv, salvageDiv, usefulDiv, depreciationDiv].forEach(div => div.classList.add('d-none'));

        if (itemHead === 'Consumables') {
            expiryDiv.classList.remove('d-none');
        } else if (itemHead === 'Capital Equipment') {
            salvageDiv.classList.remove('d-none');
            usefulDiv.classList.remove('d-none');
            depreciationDiv.classList.remove('d-none');
        }

        // Fetch dynamic items
        if (categoryId) {
            fetch(`{{ route('get.items', ':id') }}`.replace(':id', categoryId))
                .then(res => res.json())
                .then(data => {
                    itemSelect.innerHTML = '<option value="">Select Item</option>';
                    data.forEach(item => {
                        itemSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(() => {
                    itemSelect.innerHTML = '<option value="">Error loading items</option>';
                });
        } else {
            itemSelect.innerHTML = '<option value="">Select Item</option>';
        }
    });

    // Auto-calculate Depreciation
    function calculateDepreciation() {
        const purchase = parseFloat(purchasePrice.value) || 0;
        const salvage = parseFloat(salvageValue.value) || 0;
        const quant = parseFloat(quantity.value) || 0;
        const usefulLife = parseFloat(usefulLifeInput.value) || 0;

        if (purchase > 0 && salvage >= 0 && usefulLife > 0) {
            const depreciation = (((quant*purchase) - salvage) / usefulLife).toFixed(2);
            annualDepreciation.value = depreciation;
        } else {
            annualDepreciation.value = '';
        }
    }

    [purchasePrice, salvageValue, usefulLifeInput].forEach(input => {
        input.addEventListener('input', calculateDepreciation);
    });

    // Save only button
    document.getElementById('saveOnly').addEventListener('click', function () {
        document.getElementById('itemStockForm').submit();
    });
});
</script>

<script>
    document.querySelectorAll('.editStockBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;

        fetch(`{{ url('inventory') }}/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                const stock = data.stock;

                // Set form action dynamically
                const form = document.getElementById('editStockForm');
                form.action = `{{ url('inventory/update') }}/${stock.id}`;

                // Fill modal fields
                document.getElementById('edit_id').value = stock.id;
                document.getElementById('edit_quantity').value = stock.quantity;
                document.getElementById('edit_purchase_price').value = stock.purchase_price;
                document.getElementById('edit_date').value = stock.date ? stock.date.split('T')[0] : '';
                document.getElementById('edit_message').value = stock.message || '';

                // Populate item category dropdown
                const categorySelect = document.getElementById('edit_item_category');
                categorySelect.innerHTML = '';
                data.categories.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.id;
                    option.text = cat.item_category;
                    if (stock.item_category_id == cat.id) option.selected = true;
                    categorySelect.appendChild(option);
                });

                // Populate item dropdown
                const itemSelect = document.getElementById('edit_item');
                itemSelect.innerHTML = '';
                data.items.forEach(it => {
                    const option = document.createElement('option');
                    option.value = it.id;
                    option.text = it.name;
                    if (stock.item_id == it.id) option.selected = true;
                    itemSelect.appendChild(option);
                });

                // Populate supplier dropdown
                const supplierSelect = document.getElementById('edit_supplier');
                supplierSelect.innerHTML = '';
                data.suppliers.forEach(supp => {
                    const option = document.createElement('option');
                    option.value = supp.id;
                    option.text = supp.item_supplier;
                    if (stock.supplier_id == supp.id) option.selected = true;
                    supplierSelect.appendChild(option);
                });

                // Populate store dropdown
                const storeSelect = document.getElementById('edit_store');
                storeSelect.innerHTML = '';
                data.stores.forEach(store => {
                    const option = document.createElement('option');
                    option.value = store.id;
                    option.text = store.item_store;
                    if (stock.store_id == store.id) option.selected = true;
                    storeSelect.appendChild(option);
                });


                // Show modal
                const editModal = new bootstrap.Modal(document.getElementById('editStockModal'));
                editModal.show();
            })
            .catch(error => {
                console.error('Error fetching stock:', error);
            });
    });
    });


</script>






@endsection