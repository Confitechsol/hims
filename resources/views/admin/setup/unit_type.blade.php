{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Unit Type List</h5>
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
                                                data-bs-target="#add_unit"><i class="ti ti-plus me-1"></i>Add Unit Type</a>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="add_unit" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Unit Type
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('charge_units.store') }}" method="POST">
                                                            @csrf
                                                            <div class="row gy-3">

                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Unit <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="unit" id="unit"
                                                                        class="form-control" required>
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
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>

                                                    <th>Unit Type </th>
                                                    <th style="width: 200px;">Action</th>

                                                </tr>

                                            </thead>
                                            <tbody>
                                                @foreach ($unittype as $unittypes)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $unittypes->unit }}</h6>
                                                        </td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal" data-bs-target="#edit_unit"
                                                                onclick="handleEditUnit({{ $unittypes->id }},'{{ $unittypes->unit }}')">
                                                                <i class="ti ti-pencil"></i></a>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal fade" id="edit_unit" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Edit Unit Type
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('charge_units.update') }}" method="POST">
                                                        @csrf
                                                        @method('put')
                                                        <div class="row gy-3">
                                                            <input type="hidden" name="id">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Unit <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="update_unit" id="update_unit"
                                                                    class="form-control" required>
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

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function handleEditUnit(id, unit) {
            let unitInput = document.querySelector("#edit_unit input[name='update_unit']");
            let unitId = document.querySelector("#edit_unit input[name='id']");

            unitInput.value = unit;
            unitId.value = id;
        }
    </script>
@endsection
