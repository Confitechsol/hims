{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    {{-- Settings Form --}}
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Supplier List</h5>
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
                                    <div class="text-end d-flex">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary text-white ms-2 fs-13 btn-md" data-bs-toggle="modal"
                                            data-bs-target="#add_supplier"><i class="ti ti-plus me-1"></i>Add
                                            Supplier</a>
                                    </div>

                                </div>
                                @if (session('success'))
                                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Supplier Name</th>
                                                <th>Supplier Contact</th>
                                                <th>Contact Person Name</th>
                                                <th>Contact Person Phone</th>
                                                <th>Drug License Number</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($medicineSuppliers as $medicineSupplier)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{$medicineSupplier->supplier}}
                                                        </h6>
                                                    </td>
                                                    <td>{{$medicineSupplier->contact}}</td>
                                                    <td>{{$medicineSupplier->supplier_person}}</td>
                                                    <td>{{$medicineSupplier->supplier_person_contact}}</td>
                                                    <td>{{$medicineSupplier->supplier_drug_licence}}</td>
                                                    <td>{{$medicineSupplier->address}}</td>
                                                    <td>
                                                        <!-- <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a> -->
                                                            <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="{{ $medicineSupplier->id }}"
                                                            data-supplier_name="{{ $medicineSupplier->supplier }}"
                                                            data-supplier_contact="{{ $medicineSupplier->contact }}"
                                                            data-contact_person_name="{{ $medicineSupplier->supplier_person }}"
                                                            data-contact_person_phone="{{ $medicineSupplier->supplier_person_contact }}"
                                                            data-licence="{{ $medicineSupplier->supplier_drug_licence }}"
                                                            data-address="{{ $medicineSupplier->address }}"
                                                            >
                                                            <i class="ti ti-pencil"></i></button>
                                                        <form action="{{ route('supplier.destroy')}}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id"
                                                                value="{{$medicineSupplier->id}}">
                                                            <button onclick="return confirm('Are you sure?')"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                                                                    class="ti ti-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- Pagination Links --}}
                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $medicineSuppliers->currentPage();
                                        $lastPage = $medicineSuppliers->lastPage();
                                    @endphp

                                    {{-- Previous --}}
                                    @if ($medicineSuppliers->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $medicineSuppliers->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            « Prev
                                        </a>
                                    @endif

                                    {{-- Page numbers --}}
                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $medicineSuppliers->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    {{-- Next --}}
                                    @if ($medicineSuppliers->hasMorePages())
                                        <a href="{{ $medicineSuppliers->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            Next »
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                    @endif
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                </div>

            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<!-- <div class="modal fade" id="add_supplier" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Supplier
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="POST">
                                                            @csrf
                                                            <div class="row gy-3">

                                                                <div class="col-md-6">
                                                                    <label for="supplier_name" class="form-label">Supplier
                                                                        Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="supplier_name"
                                                                        id="supplier_name" class="form-control" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="supplier_contact"
                                                                        class="form-label">Supplier Contact </label>
                                                                    <input type="text" name="supplier_contact"
                                                                        id="supplier_contact" class="form-control">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="person_name" class="form-label">Contact
                                                                        Person Name </label>
                                                                    <input type="text" name="person_name" id="person_name"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="person_phone" class="form-label">Contact
                                                                        Person Phone </label>
                                                                    <input type="text" name="person_phone" id="person_phone"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label for="license_name" class="form-label">Drug
                                                                        License Number </label>
                                                                    <input type="text" name="license_name" id="license_name"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <label for="address" class="form-label">Address </label>
                                                                    <input type="text" name="address" id="address"
                                                                        class="form-control">
                                                                </div>
                                                            </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> -->
<!-- ['name' => 'specialization', 'label' => 'Specialization', 'type' => 'select', 'options' => $specializations, 'required' => true], -->
<x-modals.form-modal 
    id="edit_modal"
    title="Edit Supplier"
    action="{{route('supplier.update')}}"
    method="put"
    :fields="[
        ['name' => 'id', 'label' => '', 'type' => 'hidden', 'required' => true],
        ['name' => 'supplier_name', 'label' => 'Supplier Name', 'type' => 'text', 'required' => true],
        ['name' => 'supplier_contact', 'label' => 'Supplier Contact', 'type' => 'text'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text'],
        ['name' => 'licence', 'label' => 'Drug License Number', 'type' => 'text'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text'],
    ]"
    :columns="2"
/>

<x-modals.form-modal id="add_supplier" title="Add Supplier" action="{{route('supplier-store')}}" :fields="[
        ['name' => 'supplier_name', 'label' => 'Supplier Name', 'type' => 'text', 'required' => true],
        ['name' => 'supplier_contact', 'label' => 'Supplier Contact', 'type' => 'text'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text'],
        ['name' => 'licence', 'label' => 'Drug License Number', 'type' => 'text'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text'],
    ]" :columns="2" />

@endsection