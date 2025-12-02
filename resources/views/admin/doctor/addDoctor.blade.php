@extends('layouts.adminLayout')

@section('content')
@php
    // Detect Add or Edit mode
    $isEdit = isset($doctor);
@endphp

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            
            <!-- Header -->
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-cogs me-2"></i>
                    {{ $isEdit ? 'Edit Doctor' : 'Add New Doctor' }}
                </h5>
            </div>

            <div class="card-body">

                <!-- FORM -->
                <form id="form1"
                      action="{{ $isEdit ? route('doctor.update', $doctor->id) : route('doctor.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="row">
                        <h4>Basic Information</h4>

                        <div class="around10">
                            <div class="row">

                                <!-- Doctor ID -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Doctor ID</label><small class="req"> *</small>
                                        <input id="doctor_id" name="doctor_id" type="text"
                                               class="form-control"
                                               value="{{ old('doctor_id', $doctor->doctor_id ?? '') }}">
                                    </div>
                                </div>

                                <!-- ROLE -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Role</label><small class="req"> *</small>
                                        <select id="role" name="role" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('role', $doctor->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Doctor Registration No.</label><small class="req"> *</small>
                                        <input id="registration_no" name="registration_no" type="text"
                                               class="form-control"
                                               value="{{ old('registration_no', $doctor->registration_no ?? '') }}">
                                    </div>
                                </div>

                                <!-- DESIGNATION -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <select id="designation" name="designation" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($designations as $des)
                                                <option value="{{ $des->id }}"
                                                    {{ old('designation', $doctor->staff_designation_id  ?? '') == $des->id ? 'selected' : '' }}>
                                                    {{ $des->designation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- DEPARTMENT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select id="department" name="department" class="form-control"
                                                onchange="loadSpecialists(this.value)">
                                            <option value="">Select</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}"
                                                    {{ old('department', $doctor->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- SPECIALIST -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Specialist</label>
                                        <select name="specialist" class="form-control" id="specialist">
                                            <option value="">Select</option>

                                            @foreach ($specialists as $specialist)
                                                <option value="{{ $specialist->id }}"
                                                    {{ old('specialist', $doctor->specialist ?? '') == $specialist->id ? 'selected' : '' }}>
                                                    {{ $specialist->specialist_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- FIRST NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label><small class="req"> *</small>
                                        <input id="name" name="name" type="text" class="form-control"
                                               value="{{ old('name', $doctor->name ?? '') }}">
                                    </div>
                                </div>

                                <!-- LAST NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input id="surname" name="surname" type="text" class="form-control"
                                               value="{{ old('surname', $doctor->surname ?? '') }}">
                                    </div>
                                </div>

                                <!-- FATHER NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Father Name</label>
                                        <input id="father_name" name="father_name" type="text" class="form-control"
                                               value="{{ old('father_name', $doctor->father_name ?? '') }}">
                                    </div>
                                </div>

                                <!-- MOTHER NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mother Name</label>
                                        <input id="mother_name" name="mother_name" type="text" class="form-control"
                                               value="{{ old('mother_name', $doctor->mother_name ?? '') }}">
                                    </div>
                                </div>

                                <!-- GENDER -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender</label><small class="req"> *</small>
                                        <select class="form-control" name="gender">
                                            <option value="">Select</option>
                                            @foreach (['Male','Female','others'] as $g)
                                                <option value="{{ $g }}"
                                                    {{ old('gender', $doctor->gender ?? '') == $g ? 'selected' : '' }}>
                                                    {{ $g }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- MARITAL STATUS -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Marital Status</label>
                                        <select class="form-control" name="marital_status">
                                            <option value="">Select</option>
                                            @foreach (['Single','Married','Widowed','Separated','Not Specified'] as $ms)
                                                <option value="{{ $ms }}"
                                                    {{ old('marital_status', $doctor->marital_status ?? '') == $ms ? 'selected' : '' }}>
                                                    {{ $ms }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- BLOOD GROUP -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Blood Group</label>
                                        <select class="form-control" name="blood_group">
                                            <option value="">Select</option>
                                            @foreach ($bloodgroups as $bg)
                                                <option value="{{ $bg }}"
                                                    {{ old('blood_group', $doctor->blood_group ?? '') == $bg->id ? 'selected' : '' }}>
                                                    {{ $bg->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- DOB -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Of Birth</label><small class="req"> *</small>
                                        <input id="dob" name="dob" type="date" class="form-control date"
                                               value="{{ old('dob', $doctor->dob ?? '') }}">
                                    </div>
                                </div>

                                <!-- DO JOINING -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Of Joining</label>
                                        <input id="date_of_joining" name="date_of_joining" type="date" class="form-control date"
                                               value="{{ old('date_of_joining', $doctor->date_of_joining ?? '') }}">
                                    </div>
                                </div>

                                <!-- DO JOINING -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Of Leaving</label>
                                        <input id="date_of_leaving" name="date_of_leaving" type="date" class="form-control date"
                                               value="{{ old('date_of_leaving', $doctor->date_of_leaving ?? '') }}">
                                    </div>
                                </div>

                                <!-- CONTACT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input id="mobileno" name="contactno" type="text" class="form-control"
                                               value="{{ old('contactno', $doctor->contact_no ?? '') }}">
                                    </div>
                                </div>

                                <!-- EMERGENCY CONTACT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Emergency Contact</label>
                                        <input id="emgmobileno" name="emgcontactno" type="text" class="form-control"
                                               value="{{ old('emgcontactno', $doctor->emergency_contact_no ?? '') }}">
                                    </div>
                                </div>

                                <!-- EMAIL -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label><small class="req"> *</small>
                                        <input id="email" name="email" type="email" class="form-control"
                                               value="{{ old('email', $doctor->email ?? '') }}">
                                    </div>
                                </div>

                                <!-- PHOTO -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <input type="file" class="form-control" name="file">
                                        @if($isEdit && $doctor->photo)
                                            <small>Current: <img src="{{ asset('uploads/doctor/'.$doctor->photo) }}"
                                                                 width="40"></small>
                                        @endif
                                    </div>
                                </div>

                                <!-- CURRENT ADDRESS -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Current Address</label>
                                        <textarea name="address" class="form-control">{{ old('address', $doctor->local_address ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- PERMANENT ADDRESS -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Permanent Address</label>
                                        <textarea name="permanent_address" class="form-control">{{ old('permanent_address', $doctor->permanent_address ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- QUALIFICATION -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Qualification</label>
                                        <textarea name="qualification" class="form-control">{{ old('qualification', $doctor->qualification ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- WORK EXP -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Work Experience</label>
                                        <textarea name="work_exp" class="form-control">{{ old('work_exp', $doctor->work_exp ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- SPECIALIZATION -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Specialization</label>
                                        <textarea name="specialization" class="form-control">{{ old('specialization', $doctor->specialization ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- NOTE -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea name="note" class="form-control">{{ old('note', $doctor->note ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- PAN -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>PAN Number</label>
                                        <input name="pan_number" type="text" class="form-control"
                                               value="{{ old('pan_number', $doctor->pan_number ?? '') }}">
                                    </div>
                                </div>

                                <!-- NATIONAL ID -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Adhaar Card Number</label>
                                        <input name="identification_number" type="text" class="form-control"
                                               value="{{ old('identification_number', $doctor->identification_number ?? '') }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-info pull-right">
                        <i class="fa fa-check-circle"></i>
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
