{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>

        .form-select {
            padding: 0.5rem 0.75rem !important;
        }
    </style>

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charge Category List</h5>
                </div>
                <div class="card-body">
                <x-table-actions.actions id="charges_category" name="Charge Category" />
                    {{-- Hospital Name & Code --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(session('error'))
                                        <div class="alert alert-danger">
                                            {{session('error')}}
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{session('success')}}
                                        </div>
                                    @endif
                                        <!-- Modal -->
                                        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Charge
                                                            Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('charge_categories.store')}}" method="POST">
                                                            @csrf
                                                            <div class="row gy-3">
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Charge
                                                                        Type <span class="text-danger">*</span></label>
                                                                    <select name="charge_type" id="charge_type"
                                                                        class="form-select" required>
                                                                        <option value="">Select</option>
                                                                        @foreach ($charge_types as $charge_type)
                                                                            <option value="{{$charge_type->id}}">{{$charge_type->charge_type}}</option>
                                                                        @endforeach

                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Name <span
                                                                            class="text-danger">*</span></label>
                                                                            <input type="text" name="name" id="name" class="form-control" required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Description <span
                                                                            class="text-danger">*</span></label>
                                                                            <textarea name="description" id="description" class="form-control" required></textarea>
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

                                    <div class="table-responsive">
                                        <table class="table mb-0" id="charges_category">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Charge Type</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($chargesCatogery as $chargesCatogerys)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{$chargesCatogerys->name}}</h6>
                                                    </td>
                                                    <td>{{$chargesCatogerys->chargeType['charge_type']}}</td>
                                                    <td>{{$chargesCatogerys->description}}</td>
                                                    <td>

                                                        <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" data-bs-toggle="modal" data-bs-target="#edit_charges_category_{{$chargesCatogerys->id}}">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="edit_charges_category_{{$chargesCatogerys->id}}" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header rounded-0" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                        <h5 class="modal-title" id="addSpecializationLabel">Edit Charge Category</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{route('charge_categories.update')}}" method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden" name="id" value="{{$chargesCatogerys->id}}">
                                                                            <div class="row gy-3">
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">Charge Type <span class="text-danger">*</span></label>
                                                                                    <select name="charge_type" id="charge_type" class="form-select" required>
                                                                                        <option value="">Select</option>
                                                                                        @foreach ($charge_types as $charge_type)
                                                                                            <option value="{{$charge_type->id}}" {{$chargesCatogerys->charge_type_id == $charge_type->id ? 'selected' : ''}}>{{$charge_type->charge_type}}</option>
                                                                                        @endforeach

                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">Name <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="name" id="name" class="form-control" value="{{$chargesCatogerys->name}}" required>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">Description <span class="text-danger">*</span></label>
                                                                                    <textarea name="description" id="description" class="form-control" required>{{$chargesCatogerys->description}}</textarea>
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
                                                        <!-- Modal End -->
                                                        <form class="d-inline" action="{{route('charge_categories.destroy')}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{$chargesCatogerys->id}}">
                                                            <button type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i class="ti ti-trash"></i></button>
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




@endsection