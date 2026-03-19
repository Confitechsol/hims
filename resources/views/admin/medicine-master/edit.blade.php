@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-edit me-2"></i>Edit Medicine Details
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

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('medicine-master.update', $medicine->id) }}" method="POST" id="medicineForm">
                        @csrf
                        @method('PUT')

                        <!-- Row 1 -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                                <input type="text" name="medicine_name" class="form-control"
                                    value="{{ old('medicine_name', $medicine->name) }}" required maxlength="50"
                                    placeholder="Medicine Name">
                                @error('medicine_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Medicine Type</label>
                                <select class="form-select med-types" name="medicine_type" id="med-types">
                                    <option value="">Select Medicine Type</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Injection">Injection</option>
                                    <option value="Ointment">Ointment</option>
                                    <option value="Powder">Powder</option>
                                    <option value="Drop">Drop</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" name="medicine_price" class="form-control"
                                    value="{{ old('medicine_price', $medicine->price) }}" min="0"
                                    placeholder="Price">
                                @error('medicine_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Manufacturer Name</label>
                                <input type="text" name="manufacturer_name" class="form-control"
                                    value="{{ old('manufacturer_name', $medicine->manufacturer_name) }}"
                                    placeholder="Manufacturer Name">
                                @error('manufacturer_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pack Size Label</label>
                                <input type="text" name="pack_size_label" class="form-control"
                                    value="{{ old('pack_size_label', $medicine->pack_size_label) }}"
                                    placeholder="Pack Size Label">
                                @error('pack_size_label')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Short Composition 1</label>
                                <input type="text" name="short_composition1" class="form-control"
                                    value="{{ old('short_composition1', $medicine->short_composition1) }}" maxlength="25"
                                    placeholder="Short Composition 1">
                                @error('short_composition1')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Short Composition 2</label>
                                <input type="text" name="short_composition2" class="form-control"
                                    value="{{ old('short_composition2', $medicine->short_composition2) }}" maxlength="25"
                                    placeholder="Short Composition 2">
                                @error('short_composition2')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('medicine-master') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check"></i>&nbsp;Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let medicineType = "{{ $medicine->medicine_type ?? '' }}";

        document.addEventListener("DOMContentLoaded", function() {
            if (medicineType) {
                document.getElementById("med-types").value = medicineType;
            }
        });
    </script>
@endsection
