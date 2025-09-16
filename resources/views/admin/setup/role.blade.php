{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

        <div class="row justify-content-center">
            {{-- Settings Form --}}
            <div class="col-md-11">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Role List</h5>
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
                                                        class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                        data-bs-toggle="modal" data-bs-target="#add_specialization"><i
                                                            class="ti ti-plus me-1"></i>Create Role</a>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="add_specialization" tabindex="-1"
                                                    aria-labelledby="addSpecializationLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header rounded-0"
                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                <h5 class="modal-title" id="addSpecializationLabel">Create
                                                                    Role</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('roles.store')  }}" method="POST">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label for="roleName" class="form-label">Role
                                                                            Name</label>
                                                                        <input id="name" name= "name" class="form-control" />
                                                                    </div>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Save Role</button>
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
                                                            <th>#</th>
                                                            <th>Role</th>
                                                            <th>Type</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>
                                                                <h6 class="mb-0 fs-14 fw-semibold"> Admin</h6>
                                                            </td>

                                                            <td>System</td>

                                                            <td>
                                                                <a href="javascript: void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                   <i class="ti ti-user-circle" data-bs-toggle="tooltip" title="Assign Permission"></i></a>
                                                                <a href="javascript: void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                    <i class="ti ti-pencil"  data-bs-toggle="tooltip" title="Edit"></i></a>
                                                                <a href="javascript: void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                    <i class="ti ti-trash"  data-bs-toggle="tooltip" title="Delete"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                </div> <!-- end col -->

                            </div>
                            <!-- <hr> -->
                            <!-- <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> Save Settings
                                </button>
                            </div> -->
                        
                    </div>
                </div>
            </div>
        </div>


    <!-- Bootstrap 5 JS bundle (includes Popper) -->


@endsection
