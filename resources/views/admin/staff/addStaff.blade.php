@extends('layouts.adminLayout')

@section('content')
@php
    // Detect Add or Edit mode
    $isEdit = isset($staff);
@endphp

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            
            <!-- Header -->
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-cogs me-2"></i>
                    {{ $isEdit ? 'Edit Staff' : 'Add New Staff' }}
                </h5>
            </div>

            <div class="card-body">

                <!-- FORM -->
                <form id="form1"
                      action="{{ $isEdit ? route('staff.update', $staff->id) : route('staff.store') }}"
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

                                <!-- STAFF ID -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Staff ID</label><small class="req"> *</small>
                                        <input id="employee_id" name="employee_id" type="text"
                                               class="form-control"
                                               value="{{ old('employee_id', $staff->employee_id ?? '') }}">
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
                                                    {{ old('role', $staff->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
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
                                                    {{ old('designation', $staff->staff_designation_id  ?? '') == $des->id ? 'selected' : '' }}>
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
                                                    {{ old('department', $staff->department_id ?? '') == $dept->id ? 'selected' : '' }}>
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
                                                    {{ old('specialist', $staff->specialist ?? '') == $specialist->id ? 'selected' : '' }}>
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
                                               value="{{ old('name', $staff->name ?? '') }}">
                                    </div>
                                </div>

                                <!-- LAST NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input id="surname" name="surname" type="text" class="form-control"
                                               value="{{ old('surname', $staff->surname ?? '') }}">
                                    </div>
                                </div>

                                <!-- FATHER NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Father Name</label>
                                        <input id="father_name" name="father_name" type="text" class="form-control"
                                               value="{{ old('father_name', $staff->father_name ?? '') }}">
                                    </div>
                                </div>

                                <!-- MOTHER NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mother Name</label>
                                        <input id="mother_name" name="mother_name" type="text" class="form-control"
                                               value="{{ old('mother_name', $staff->mother_name ?? '') }}">
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
                                                    {{ old('gender', $staff->gender ?? '') == $g ? 'selected' : '' }}>
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
                                                    {{ old('marital_status', $staff->marital_status ?? '') == $ms ? 'selected' : '' }}>
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
                                                    {{ old('blood_group', $staff->blood_group ?? '') == $bg->id ? 'selected' : '' }}>
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
                                        <input id="dob" name="dob" type="text" class="form-control date"
                                               value="{{ old('dob', $staff->dob ?? '') }}">
                                    </div>
                                </div>

                                <!-- DO JOINING -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Of Joining</label>
                                        <input id="date_of_joining" name="date_of_joining" type="text" class="form-control date"
                                               value="{{ old('date_of_joining', $staff->date_of_joining ?? '') }}">
                                    </div>
                                </div>

                                <!-- CONTACT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input id="mobileno" name="contactno" type="text" class="form-control"
                                               value="{{ old('contactno', $staff->contact_no ?? '') }}">
                                    </div>
                                </div>

                                <!-- EMERGENCY CONTACT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Emergency Contact</label>
                                        <input id="emgmobileno" name="emgcontactno" type="text" class="form-control"
                                               value="{{ old('emgcontactno', $staff->emgcontactno ?? '') }}">
                                    </div>
                                </div>

                                <!-- EMAIL -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label><small class="req"> *</small>
                                        <input id="email" name="email" type="email" class="form-control"
                                               value="{{ old('email', $staff->email ?? '') }}">
                                    </div>
                                </div>

                                <!-- PHOTO -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <input type="file" class="form-control" name="file">
                                        @if($isEdit && $staff->photo)
                                            <small>Current: <img src="{{ asset('uploads/staff/'.$staff->photo) }}"
                                                                 width="40"></small>
                                        @endif
                                    </div>
                                </div>

                                <!-- CURRENT ADDRESS -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Current Address</label>
                                        <textarea name="address" class="form-control">{{ old('address', $staff->local_address ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- PERMANENT ADDRESS -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Permanent Address</label>
                                        <textarea name="permanent_address" class="form-control">{{ old('permanent_address', $staff->permanent_address ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- QUALIFICATION -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Qualification</label>
                                        <textarea name="qualification" class="form-control">{{ old('qualification', $staff->qualification ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- WORK EXP -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Work Experience</label>
                                        <textarea name="work_exp" class="form-control">{{ old('work_exp', $staff->work_exp ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- SPECIALIZATION -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Specialization</label>
                                        <textarea name="specialization" class="form-control">{{ old('specialization', $staff->specialization ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- NOTE -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea name="note" class="form-control">{{ old('note', $staff->note ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- PAN -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>PAN Number</label>
                                        <input name="pan_number" type="text" class="form-control"
                                               value="{{ old('pan_number', $staff->pan_number ?? '') }}">
                                    </div>
                                </div>

                                <!-- NATIONAL ID -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>National Identification Number</label>
                                        <input name="identification_number" type="text" class="form-control"
                                               value="{{ old('identification_number', $staff->identification_number ?? '') }}">
                                    </div>
                                </div>

                                <!-- LOCAL ID -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Local Identification Number</label>
                                        <input name="local_identification_number" type="text" class="form-control"
                                               value="{{ old('local_identification_number', $staff->local_identification_number ?? '') }}">
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
