{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">
                    <h5>Change Password</h5>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Current Password</label>
                            <input type="password"
                                   name="current_password"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password"
                                   name="new_password"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Confirm New Password</label>
                            <input type="password"
                                   name="new_password_confirmation"
                                   class="form-control"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Update Password
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection