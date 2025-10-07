{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Item Supplier List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_item_supplier"><i
                                                        class="ti ti-plus me-1"></i>Add Item Supplier</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_item_supplier" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content modal-lg">
                                                        <div class="modal-header rounded-0 modal-lg"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Item
                                                                Supplier

                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('item-supplier.store') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-6">
                                                                        <label for="name" class="form-label">Name
                                                                            <span class="text-danger">*</span></label>
                                                                        <input type="text" name="name" id="name"
                                                                            class="form-control" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="phone" class="form-label">Phone
                                                                        </label>
                                                                        <input type="tel" name="phone" id="phone"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="mail" class="form-label">Email
                                                                        </label>
                                                                        <input type="email" name="mail" id="mail"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="contact_person_name"
                                                                            class="form-label">Contact Person Name
                                                                        </label>
                                                                        <input type="text" name="contact_person_name"
                                                                            id="contact_person_name" class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="address" class="form-label">Address
                                                                        </label>
                                                                        <input type="address" name="address" id="address"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="contact_person_phone"
                                                                            class="form-label">Contact Person Phone
                                                                        </label>
                                                                        <input type="tel" name="contact_person_phone"
                                                                            id="contact_person_phone"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="contact_person_email"
                                                                            class="form-label">Contact Person Email
                                                                        </label>
                                                                        <input type="email" name="contact_person_email"
                                                                            id="contact_person_email"
                                                                            class="form-control" />
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <label for="description"
                                                                            class="form-label">Description</label>
                                                                        <textarea name="description" id="description" class="form-control"></textarea>
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
                                                    <th>Item Supplier</th>
                                                    <th>Contact Person</th>
                                                    <th>Address</th>
                                                    <th>Status</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($itemSuppliers as $supplier)
                                                    <tr>
                                                        <td>
                                                            <span data-toggle="popover" class="detail_popover"
                                                                data-original-title="" style="color:#750096">

                                                                {{ $supplier->item_supplier }}<br>
                                                            </span>
                                                            <i class="fa fa-phone-square" style="color: #a941c6;"></i>
                                                            {{ $supplier->phone }}<br>
                                                            <i class="fa fa-envelope" style="color: #a941c6;"></i>
                                                            {{ $supplier->email }}
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-user" style="color: #a941c6;"></i>
                                                            {{ $supplier->contact_person_name }}
                                                            <br>
                                                            <i class="fa fa-phone-square" style="color: #a941c6;"></i>
                                                            {{ $supplier->contact_person_phone }} <br>
                                                            <i class="fa fa-envelope" style="color: #a941c6;"></i>
                                                            {{ $supplier->contact_person_email }}
                                                        </td>
                                                        <td class="text-wrap">
                                                            <i class="fa fa-building" style="color: #a941c6;"></i>
                                                            {{ $supplier->address }}
                                                        </td>
                                                        <td>
                                                            <form
                                                                action="{{ route('item-supplier.updateStatus', [$supplier->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox" role="switch"
                                                                        id="switchCheckDefault" name="is_active"
                                                                        data-id="{{ $supplier->id }}"
                                                                        {{ $supplier->is_active == 'yes' ? 'checked' : '' }}>
                                                                </div>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal" data-bs-target="#edit_itemSupplier"
                                                                data-id="{{ $supplier->id }}"
                                                                data-name="{{ $supplier->item_supplier }}"
                                                                data-phone="{{ $supplier->phone }}"
                                                                data-email="{{ $supplier->email }}"
                                                                data-cp-name="{{ $supplier->contact_person_name }}"
                                                                data-cp-phone="{{ $supplier->contact_person_phone }}"
                                                                data-cp-email="{{ $supplier->contact_person_email }}"
                                                                data-address="{{ $supplier->address }}"
                                                                data-description="{{ $supplier->description }}">
                                                                <i class="ti ti-pencil"></i></a>
                                                            <form
                                                                action="{{ route('item-supplier.delete', [$supplier->id]) }}"
                                                                class="d-inline" id="delete-form-{{ $supplier->id }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill delete-button"
                                                                    data-item-supplier-id="{{ $supplier->id }}"
                                                                    data-item-supplier-name="{{ $supplier->item_supplier }}"
                                                                    data-form-id="delete-form-{{ $supplier->id }}">
                                                                    <i class="ti ti-trash"></i></a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="edit_itemSupplier" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content modal-lg">
                                                <div class="modal-header rounded-0 modal-lg"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Update Item
                                                        Supplier

                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('item-supplier.update') }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row gy-3 mb-2">

                                                            <!-- Operation Name -->
                                                            <input type="hidden" name="item_supplier_id"
                                                                id="item_supplier_id">
                                                            <div class="col-md-6">
                                                                <label for="update_name" class="form-label">Name
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="text" name="name" id="update_name"
                                                                    class="form-control" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="update_phone" class="form-label">Phone
                                                                </label>
                                                                <input type="tel" name="phone" id="update_phone"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="update_mail" class="form-label">Email
                                                                </label>
                                                                <input type="email" name="mail" id="update_mail"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="update_contact_person_name"
                                                                    class="form-label">Contact Person Name
                                                                </label>
                                                                <input type="text" name="contact_person_name"
                                                                    id="update_contact_person_name"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="update_address" class="form-label">Address
                                                                </label>
                                                                <input type="address" name="address" id="update_address"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="update_contact_person_phone"
                                                                    class="form-label">Contact Person Phone
                                                                </label>
                                                                <input type="tel" name="contact_person_phone"
                                                                    id="update_contact_person_phone"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="update_contact_person_email"
                                                                    class="form-label">Contact Person Email
                                                                </label>
                                                                <input type="email" name="contact_person_email"
                                                                    id="update_contact_person_email"
                                                                    class="form-control" />
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label for="update_description"
                                                                    class="form-label">Description</label>
                                                                <textarea name="description" id="update_description" class="form-control"></textarea>
                                                            </div>

                                                        </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('edit_itemSupplier');

            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var phone = button.getAttribute('data-phone');
                var email = button.getAttribute('data-email');
                var cpName = button.getAttribute('data-cp-name');
                var cpPhone = button.getAttribute('data-cp-phone');
                var cpEmail = button.getAttribute('data-cp-email');
                var address = button.getAttribute('data-address');
                var description = button.getAttribute('data-description');

                // Populate modal inputs
                document.getElementById('item_supplier_id').value = id;
                document.getElementById('update_name').value = name;
                document.getElementById('update_phone').value = phone;
                document.getElementById('update_mail').value = email;
                document.getElementById('update_contact_person_name').value = cpName;
                document.getElementById('update_contact_person_phone').value = cpPhone;
                document.getElementById('update_contact_person_email').value = cpEmail;
                document.getElementById('update_address').value = address;
                document.getElementById('update_description').value = description;

            });
        });
    </script>
    <script>
        document.querySelectorAll('.status-toggle').forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
    <script>
        document.querySelectorAll('.delete-button').forEach(input => {
            input.addEventListener('click', function() {
                const itemSupplierId = this.dataset.itemSupplierId;
                const itemSupplierName = this.dataset.itemSupplierName;
                const formId = this.dataset.formId;

                Swal.fire({
                    title: `Please Confirm`,
                    text: `Delete Item Supplier ${itemSupplierName}(${itemSupplierId})`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete!',
                    cancelButtonText: 'Cancel',
                }).then(result => {
                    console.log(result);

                    if (result.isConfirmed) {
                        document.getElementById(formId).submit(); // Submit your form
                    }
                });
            });
        });
    </script>
@endsection
