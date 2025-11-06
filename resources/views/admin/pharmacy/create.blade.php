{{-- resources/views/admin/pharmacy/create.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-pills me-2"></i>Add New Medicine
                    </h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pharmacy.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            {{-- Medicine Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                                <input type="text" name="medicine_name" class="form-control" 
                                       value="{{ old('medicine_name') }}" required>
                            </div>

                            {{-- Medicine Category --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="medicine_category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('medicine_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->medicine_category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Medicine Company --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <select name="medicine_company" class="form-select">
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('medicine_company') == $company->id ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Medicine Composition --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Composition</label>
                                <input type="text" name="medicine_composition" class="form-control" 
                                       value="{{ old('medicine_composition') }}">
                            </div>

                            {{-- Medicine Group --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Medicine Group</label>
                                <select name="medicine_group" class="form-select">
                                    <option value="">Select Group</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ old('medicine_group') == $group->id ? 'selected' : '' }}>
                                            {{ $group->group_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Unit --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-select">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->unit_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Min Level --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Minimum Level</label>
                                <input type="text" name="min_level" class="form-control" 
                                       value="{{ old('min_level') }}">
                            </div>

                            {{-- Reorder Level --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Reorder Level</label>
                                <input type="text" name="reorder_level" class="form-control" 
                                       value="{{ old('reorder_level') }}">
                            </div>

                            {{-- GST --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">GST Rate (%)</label>
                                <select name="gst_percentage" class="form-select">
                                    <option value="">Select GST Rate</option>
                                    <option value="0" {{ old('gst_percentage') == '0' ? 'selected' : '' }}>0% (Exempt)</option>
                                    <option value="5" {{ old('gst_percentage') == '5' ? 'selected' : '' }}>5%</option>
                                    <option value="12" {{ old('gst_percentage') == '12' ? 'selected' : '' }}>12%</option>
                                    <option value="18" {{ old('gst_percentage') == '18' ? 'selected' : '' }}>18%</option>
                                    <option value="28" {{ old('gst_percentage') == '28' ? 'selected' : '' }}>28%</option>
                                </select>
                                <small class="text-muted">Standard Indian GST Rates</small>
                            </div>

                            {{-- Unit Packing --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Packing</label>
                                <input type="text" name="unit_packing" class="form-control" 
                                       value="{{ old('unit_packing') }}" 
                                       placeholder="e.g., 10 Tablets per strip">
                            </div>

                            {{-- Rack Number --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rack Number</label>
                                <input type="text" name="rack_number" class="form-control" 
                                       value="{{ old('rack_number') }}">
                            </div>

                            {{-- Medicine Image --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Medicine Image</label>
                                <input type="file" name="medicine_image" class="form-control" accept="image/*">
                                <small class="text-muted">Max size: 2MB. Formats: jpg, jpeg, png</small>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="yes" {{ old('is_active', 'yes') == 'yes' ? 'selected' : '' }}>Active</option>
                                    <option value="no" {{ old('is_active') == 'no' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            {{-- Note --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('pharmacy.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i>Save Medicine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

