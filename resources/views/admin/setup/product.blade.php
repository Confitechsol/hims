{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Product List</h5>
                </div>

                <div class="card-body">


                    {{-- Hospital Name & Code --}}
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                        <div class="input-icon-start position-relative me-2">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm" placeholder="Search">

                                        </div>
                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_radiology_parameter"><i
                                                        class="ti ti-plus me-1"></i>Add Products</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_radiology_parameter" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Products
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('blood-bank-products.store') }}" method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="type" class="form-label">Type <span
                                                                                class="text-danger">*</span></label>
                                                                        <select class="form-select" id="type" name="type" required>
                                                                            <option value="">Select</option>
                                                                            <option value="1"> Blood Group</option>
                                                                            <option value="2"> Component</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="name" class="form-label">Name <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="name" id="name" class="form-control" required>
                                                                    </div>
                                                                    

                                                                </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                                </div>
                                                            </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($products as $product)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{ $product->name }} 
                                                        </h6>
                                                    </td>
                                                    <td>{{ $product->is_blood_group == 1 ? 'Blood Group' : 'Component' }}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" 
                                                        onclick="openProductModal(this)" 
                                                        data-product-id="{{ $product->id }}" 
                                                        data-product-name="{{ $product->name }}" 
                                                        data-product-type="{{ $product->is_blood_group }}"
                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                        <i class="ti ti-pencil"></i>
                                                        </a>

                                                        <a href="javascript:void(0);" 
                                                        onclick="deleteProduct({{ $product->id }})"
                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                        <i class="ti ti-trash"></i>
                                                        </a>
                                                        <!-- Delete Product Hidden Form -->
                                                        <form id="deleteProductForm" method="POST" style="display:none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>
<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProductForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <!-- Type -->
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type" id="editProductType" required>
                            <option value="">Select</option>
                            <option value="1">Blood Group</option>
                            <option value="2">Component</option>
                        </select>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="editProductName" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function openProductModal(el) {
    let id   = el.getAttribute("data-product-id");
    let name = el.getAttribute("data-product-name");
    let type = el.getAttribute("data-product-type");

    // Fill inputs
    document.getElementById("editProductName").value = name;
    document.getElementById("editProductType").value = type;

    // Update form action
    let form = document.getElementById("editProductForm");
    form.action = "{{ url('blood-bank-products/update') }}/" + id; // update route

    // Show modal
    let modal = new bootstrap.Modal(document.getElementById("editProductModal"));
    modal.show();
}

function deleteProduct(id) {
    if (confirm("Are you sure you want to delete this product?")) {
        let form = document.getElementById("deleteProductForm");
        form.action = "{{ url('blood-bank-products/destroy') }}/" + id; // delete route
        form.submit();
    }
}
</script>
@endsection