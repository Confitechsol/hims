@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-edit me-2"></i>Edit Radiology Test Details
                    </h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('radiology.test.update', $test->id) }}" method="POST" id="radiologyTestForm">
                        @csrf
                        @method('PUT')

                        <!-- Row 1 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Test Name <span class="text-danger">*</span></label>
                                <input type="text" name="test_name" class="form-control" value="{{ old('test_name', $test->test_name) }}" required maxlength="50" placeholder="Test Name">
                                @error('test_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Short Name <span class="text-danger">*</span></label>
                                <input type="text" name="short_name" class="form-control" value="{{ old('short_name', $test->short_name) }}" required maxlength="20" placeholder="Short Name">
                                @error('short_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Test Type</label>
                                <input type="text" name="test_type" class="form-control" value="{{ old('test_type', $test->test_type) }}" maxlength="15" placeholder="Test Type">
                                @error('test_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <select name="radiology_category_id" id="radiology_category_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('radiology_category_id', $test->radiology_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('radiology_category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Sub Category</label>
                                <input type="text" name="sub_category" class="form-control" value="{{ old('sub_category', $test->sub_category) }}" maxlength="25" placeholder="Sub Category">
                                @error('sub_category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Report Days <span class="text-danger">*</span></label>
                                <input type="number" name="report_days" class="form-control" value="{{ old('report_days', $test->report_days) }}" min="0" required placeholder="0">
                                @error('report_days')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Standard Charge IPD (INR) <span class="text-danger">*</span></label>
                                <input type="number" name="standard_charge_ipd" id="standard_charge_ipd" class="form-control" value="{{ old('standard_charge_ipd', $test->standard_charge_ipd) }}" step="0.01" min="0" required>
                                @error('standard_charge_ipd')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Standard Charge OPD (INR) <span class="text-danger">*</span></label>
                                <input type="number" name="standard_charge_opd" id="standard_charge_opd" class="form-control" value="{{ old('standard_charge_opd', $test->standard_charge_opd) }}" step="0.01" min="0" required>
                                @error('standard_charge_opd')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @include('admin.insurance.partials.test_panel_rates', [
                            'panelRates' => $panelRates,
                            'testType' => 'radiology',
                            'editable' => true,
                        ])

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('radiology.test.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    jQuery('#radiology_category_id').select2({
                        width: '100%',
                        placeholder: 'Select Category',
                        allowClear: false
                    });
                }
            }, 500);
        });
    </script>
@endsection
