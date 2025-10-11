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
                                                <form method="POST" action="{{ route('items.store') }}" id="itemStockForm">
                                                    @csrf

                                                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title">Add Item Stock</h5>
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

                                                            {{-- Item (Dynamic based on category) --}}
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
                                                                    <input id="quantity" name="quantity" type="number" class="form-control" placeholder="Quantity">
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
                                                                <input type="file" name="attachment" id="attachment" class="form-control" multiple>
                                                                <small class="text-muted">You can upload (PDF, JPG, PNG)</small>
                                                            </div>

                                                            {{-- Expiry Date (hidden initially) --}}
                                                            <div class="col-md-3 d-none" id="expiry_date_div">
                                                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                                                <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                                                            </div>

                                                            {{-- Salvage Value (hidden initially) --}}
                                                            <div class="col-md-3 d-none" id="salvage_value_div">
                                                                <label for="salvage_value" class="form-label">Salvage Value</label>
                                                                <input type="number" name="salvage_value" id="salvage_value" class="form-control">
                                                            </div>

                                                            {{-- Useful Life (hidden initially) --}}
                                                            <div class="col-md-3 d-none" id="useful_life_div">
                                                                <label for="useful_life" class="form-label">Useful Life (in years)</label>
                                                                <input type="number" name="useful_life" id="useful_life" class="form-control">
                                                            </div>

                                                            {{-- Annual Depreciation (hidden initially) --}}
                                                            <div class="col-md-3 d-none" id="annual_depreciation_div">
                                                                <label for="annual_depreciation" class="form-label">Annual Depreciation (%)</label>
                                                                <input type="number" name="annual_depreciation" id="annual_depreciation" class="form-control">
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

                                                    
                            
                                                
                                           
                                    <a href="{{ route('appointments.doctor-wise') }}"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Doctor Wise</a>
                                    <a href="{{ route('appointments.queue') }}"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Queue</a>
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
                    <td>{{ $stock->itemCategory->name ?? 'N/A' }}</td>
                    <td>{{ $stock->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $stock->store->name ?? 'N/A' }}</td>
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
                            <a href="{{ route('itemstock.edit', $stock->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('itemstock.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
        <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form id="rescheduleForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                            <h5 class="mb-0 text-dark fw-bold">Reschedule Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row gy-3">
                                {{-- Patient Name --}}
                                <div class="col-md-3">
                                    <label>Patient</label>
                                    <input type="text" id="reschedule_patient" class="form-control" readonly>
                                </div>

                                {{-- Doctor --}}
                                <div class="col-md-3">
                                    <label>Doctor</label>
                                    <input type="text" id="reschedule_doctor" class="form-control" readonly>
                                </div>

                                {{-- Fees --}}
                                <div class="col-md-3">
                                    <label>Doctor Fees (INR)</label>
                                    <input type="text" id="reschedule_fees" class="form-control" readonly>
                                </div>

                                {{-- Shift --}}
                                <div class="col-md-3">
                                    <label>Shift</label>
                                    <select id="reschedule_shift" name="shift" class="form-select" required></select>
                                </div>

                                {{-- Date --}}
                                <div class="col-md-3">
                                    <label>Date</label>
                                    <input type="date" id="reschedule_date" name="appointment_date" class="form-control" required>
                                </div>

                                {{-- Slot --}}
                                <div class="col-md-3">
                                    <label>Slot</label>
                                    <select id="reschedule_slot" name="slot" class="form-select" required></select>
                                </div>
                                {{-- Status --}}
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select id="reschedule_status" name="status" class="form-select">
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="rescheduled">Rescheduled</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Appointment</button>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemCategory = document.getElementById('item_category');
    const itemSelect = document.getElementById('item');

    // Hidden fields
    const expiryDiv = document.getElementById('expiry_date_div');
    const salvageDiv = document.getElementById('salvage_value_div');
    const usefulDiv = document.getElementById('useful_life_div');
    const depreciationDiv = document.getElementById('annual_depreciation_div');

    // Handle Item Category Change
    itemCategory.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const itemHead = selectedOption.getAttribute('data-item-head');
        const categoryId = this.value;

        // Hide all conditional fields initially
        [expiryDiv, salvageDiv, usefulDiv, depreciationDiv].forEach(div => div.classList.add('d-none'));

        // Show fields based on item_head type
        if (itemHead === 'Consumables') {
            expiryDiv.classList.remove('d-none');
        } else if (itemHead === 'Capital Equipment') {
            salvageDiv.classList.remove('d-none');
            usefulDiv.classList.remove('d-none');
            depreciationDiv.classList.remove('d-none');
        }

        // Fetch related items via route
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

    // Save only button
    document.getElementById('saveOnly').addEventListener('click', function () {
        document.getElementById('itemStockForm').submit();
    });
});
</script>





@endsection