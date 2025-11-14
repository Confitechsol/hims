{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Staff List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_Staff"><i
                                                        class="ti ti-plus me-1"></i>Add New
                                                    Staff</a>
                                            </div>
                                            <!-- Modal -->

                                           {{--@include('components.modals.add-Staffs-modal') --}} 


                                            <div class="text-end d-flex">
                                                <a href="{{ route('Staff-import') }}"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-download me-1"></i>Import Staff</a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-menu me-1"></i>Disable Staff List</a>
                                            </div>
                                        </div>

                                    </div>
                                    <form action="{{ route('Staffs.bulkDelete') }}" method="POST" id="bulk-delete-form">
                                        @csrf
                                        @method('DELETE') <!-- Laravel RESTful delete -->
                                        <div class="text-end mb-2">
                                            <button type="submit" class="btn btn-danger text-white ms-2 fs-13 btn-md"
                                                onclick="return confirm('Are you sure you want to delete the selected Staffs?')">
                                                <i class="ti ti-trash me-1"></i>Delete Selected
                                            </button>
                                        </div>
                                        @if (session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        @if (session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif

                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" name="checkbox" id="select_all">
                                                            #</th>
                                                        <th>Staff Name</th>
                                                        <th>Employee Id</th>
                                                        <th>Gender</th>
                                                        <th>Phone</th>
                                                        <th>Department</th>
                                                        <th>Is Active</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($staffs as $staff)
                                                        <tr>
                                                            <td><input type="checkbox" name="selected_Staffs[]"
                                                                    value="{{ $staff->id }}" class="select_item"></td>
                                                            <td>{{ $staff->name }} {{ $staff->surname }}</td>
                                                            <td>{{ $staff->employee_id }}</td>
                                                            <td>{{ $staff->gender }}</td>
                                                            <td>{{ $staff->contact_no }}</td>
                                                            <td>{{ $staff->department->department_name ?? 'No Department' }}</td>
                                                            <td>{{ $staff->is_active == '1' ? 'Yes' : 'No' }}</td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                        <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                            title="Assign Permission"></i></a>
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                        <i class="ti ti-dots-vertical"
                                                                            data-bs-toggle="tooltip"
                                                                            title="Assign Permission"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let myModal = new bootstrap.Modal(document.getElementById('add_Staff'));
                myModal.show();
            });
        </script>
    @endif
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.select_item');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
@endsection
