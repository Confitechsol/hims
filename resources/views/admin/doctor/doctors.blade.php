{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Doctor List</h5>
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
                                                <a href="{{ route('createDoctor') }}"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    ><i
                                                        class="ti ti-plus me-1"></i>Add New
                                                    Doctor</a>
                                            </div>
                                            <!-- Modal -->

                                           


                                            <div class="text-end d-flex">
                                                <a href="{{ route('doctor-import') }}"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-download me-1"></i>Import Doctor</a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-menu me-1"></i>Disable Doctor List</a>
                                            </div>
                                        </div>

                                    </div>
                                    <form action="{{ route('doctors.bulkDelete') }}" method="POST" id="bulk-delete-form">
                                        @csrf
                                        @method('DELETE') <!-- Laravel RESTful delete -->
                                        <div class="text-end mb-2">
                                            <button type="submit" class="btn btn-danger text-white ms-2 fs-13 btn-md"
                                                onclick="return confirm('Are you sure you want to delete the selected Doctors?')">
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
                                                        <th>Doctor Name</th>
                                                        <th>Doctor Id</th>
                                                        <th>Gender</th>
                                                        <th>Phone</th>
                                                        <th>Department</th>
                                                        <th>Is Active</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($doctors as $doctor)
                                                        <tr>
                                                            <td><input type="checkbox" name="selected_Doctors[]"
                                                                    value="{{ $doctor->id }}" class="select_item"></td>
                                                            <td>{{ $doctor->name }} {{ $doctor->surname }}</td>
                                                            <td>{{ $doctor->doctor_id }}</td>
                                                            <td>{{ $doctor->gender }}</td>
                                                            <td>{{ $doctor->contact_no }}</td>
                                                            <td>{{ $doctor->department->department_name ?? 'No Department' }}</td>
                                                            <td>{{ $doctor->is_active == '1' ? 'Yes' : 'No' }}</td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="{{ route('doctor.edit', $doctor->id) }}" 
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                        >
                                                                        <i class="ti ti-pencil"></i>
                                                                    </a>

                                                                    
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
                let myModal = new bootstrap.Modal(document.getElementById('add_Doctor'));
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
