{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>
        .input-group .input-group-addon {
            border-radius: 0;
            border: 1px solid #d2d6de;
            background-color: #d3a2e03d;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
        }

        .input-group {
            position: relative;
            display: table;
            border-collapse: separate;
        }
    </style>

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Tax Category List</h5>
                </div>

                <div class="card-body">


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
                                    <x-table-actions.actions id="tax_category" name="Tax Category" />
                                    <!-- <div
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
                                                    data-bs-target="#add_tax_category"><i class="ti ti-plus me-1"></i>Add Tax
                                                    Category</a>
                                            </div>
                                        </div> -->
                                    <!-- Modal -->
                                    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Add Tax Category
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('tax_category.store')}}" method="POST">
                                                        @csrf
                                                        <div class="row gy-3">

                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" id="name"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Tax <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        name="tax_percentage" id="tax_percentage" required>
                                                                    <span class="input-group-addon"> %</span>
                                                                </div>
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
                                    <div class="modal fade" id="edit_tax_category" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Edit Tax Category
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('tax_category.update')}}" method="POST">
                                                        @csrf
                                                        @method('put')
                                                        <div class="row gy-3">
                                                            <input type="hidden" name="id">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" id="name"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Tax <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        name="tax_percentage" id="tax_percentage" required>
                                                                    <span class="input-group-addon"> %</span>
                                                                </div>
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
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="tax_category">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Percentage</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($taxcatogery as $taxcatogerys)


                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">{{$taxcatogerys->name}}</h6>
                                                        </td>
                                                        <td>{{$taxcatogerys->percentage}}</td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal" data-bs-target="#edit_tax_category"
                                                                onclick="handleEdit({{$taxcatogerys->id}},'{{$taxcatogerys->name}}',{{$taxcatogerys->percentage}})">
                                                                <i class="ti ti-pencil"></i></a>
                                                            {{-- <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i></a> --}}
                                                            <form action="{{route('tax_category.destroy')}}" method="POST"
                                                                style="display: inline">
                                                                @method('delete')
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{$taxcatogerys->id}}">
                                                                <button onclick='return confirm("Are you sure?")'
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                                                                        class="ti ti-trash"></i></button>
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

    <script>
        function handleEdit(taxId, taxName, taxPercentage) {
            let name = document.querySelector("#edit_tax_category input[name='name']");
            let tax = document.querySelector("#edit_tax_category input[name='tax_percentage']");
            let id = document.querySelector("#edit_tax_category input[name='id']");
            name.value = taxName;
            tax.value = taxPercentage;
            id.value = taxId;
        }
    </script>


@endsection