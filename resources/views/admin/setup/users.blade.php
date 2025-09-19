@extends('layouts.adminLayout')
@section('content')
    <style>
        .nav-tabs .nav-link.active {
            background-color: #cb6ae6 !important;
            color: #f8f9fa !important;
            font-weight: 600 !important;
        }
    </style>
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Users</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                        <form action="{{ route('users') }}" method="GET">
                                            <div class="d-flex align-items-center">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input type="text" id="language-search" name="search"
                                                        value="{{ request('search') }}" class="form-control shadow-sm"
                                                        placeholder="Search">
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary" type="submit">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="text-end d-flex">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 {{ $isDoctorTab ? 'active' : '' }}"
                                                        href="{{ route('users', array_merge(request()->except('tab'), ['tab' => 'doctor'])) }}">
                                                        Doctors
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 {{ !$isDoctorTab ? 'active' : '' }}"
                                                        href="{{ route('users', array_merge(request()->except('tab'), ['tab' => 'staff'])) }}">
                                                        Staff
                                                    </a>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                    @if (!$isDoctorTab)
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Role</th>
                                                        <th>Designation</th>
                                                        <th>Department</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        <tr>
                                                            <th scope="row">{{ $loop->iteration }}</th>
                                                            <td>{{ $user->name }}&nbsp;{{ $user->surname }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->contact_no }}</td>
                                                            <td>{{ $user->role_name ?? '-' }}</td>
                                                            <td>{{ $user->designation_name ?? '-' }}</td>
                                                            <td>{{ $user->department_name ?? '-' }}</td>
                                                            <td>
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox" role="switch"
                                                                        id="switchCheckDefault" name="is_active"
                                                                        data-id="{{ $user->id }}"
                                                                        {{ $user->is_active == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Role</th>
                                                        <th>Designation</th>
                                                        <th>Department</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        <tr>
                                                            <th scope="row">{{ $loop->iteration }}</th>
                                                            <td>{{ $user->name }}&nbsp;{{ $user->surname }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->contact_no }}</td>
                                                            <td>{{ $user->role_name ?? '-' }}</td>
                                                            <td>{{ $user->designation_name ?? '-' }}</td>
                                                            <td>{{ $user->department_name ?? '-' }}</td>
                                                            <td>
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox" role="switch"
                                                                        id="switchCheckDefault" name="is_active"
                                                                        data-id="{{ $user->id }}"
                                                                        {{ $user->is_active == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
