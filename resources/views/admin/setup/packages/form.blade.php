@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096">
                    <i class="ti ti-package me-2"></i>
                    {{ (isset($package) && $package->id) ? 'Edit Package' : 'Create Package' }}
                </h5>
                <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> Back to List
                </a>
            </div>
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

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ (isset($package) && $package->id) ? route('packages.update', $package->id) : route('packages.store') }}" method="POST">
                @csrf
                @if(isset($package) && $package->id)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Package Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $package->name ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="account_head" class="form-label">Account Head</label>
                            <input type="text" class="form-control" id="account_head" name="account_head" 
                                   value="{{ old('account_head', $package->account_head ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="gst_amount" class="form-label">GST Amount</label>
                            <input type="number" step="0.01" class="form-control" id="gst_amount" name="gst_amount" 
                                   value="{{ old('gst_amount', $package->gst_amount ?? 0) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="package_rate" class="form-label">Package Rate <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="package_rate" name="package_rate" 
                                   value="{{ old('package_rate', $package->package_rate ?? 0) }}" required>
                            <small class="text-muted">Enter the final package rate manually.</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $package->description ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" {{ old('status', $package->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $package->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Service Includes Section -->
                <div class="mb-4">
                    <h6 class="mb-3">Service Includes</h6>
                    <div id="charges-container">
                        @if(isset($package) && $package->id && $package->charges->count() > 0)
                            @foreach($package->charges as $index => $charge)
                                <div class="row charge-row mb-2">
                                    <div class="col-md-4">
                                        <select class="form-select charge-type" name="charges[{{ $index }}][charge_type]">
                                            <option value="">Select Charge Type</option>
                                            <option value="Other Charges" {{ $charge->charge_type == 'Other Charges' ? 'selected' : '' }}>Other Charges</option>
                                            <option value="Bed Charges" {{ $charge->charge_type == 'Bed Charges' ? 'selected' : '' }}>Bed Charges</option>
                                            <option value="O.T. Charges" {{ $charge->charge_type == 'O.T. Charges' ? 'selected' : '' }}>O.T. Charges</option>
                                            <option value="Doctor Charges" {{ $charge->charge_type == 'Doctor Charges' ? 'selected' : '' }}>Doctor Charges</option>
                                            <option value="Diagnostic Charges" {{ $charge->charge_type == 'Diagnostic Charges' ? 'selected' : '' }}>Diagnostic Charges</option>
                                            <option value="Medicine Charges" {{ $charge->charge_type == 'Medicine Charges' ? 'selected' : '' }}>Medicine Charges</option>
                                            <option value="Service Charges" {{ $charge->charge_type == 'Service Charges' ? 'selected' : '' }}>Service Charges</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                    <input type="number" step="0.01" class="form-control charge-amount" 
                                               name="charges[{{ $index }}][amount]" 
                                               value="{{ $charge->amount }}" 
                                               placeholder="Amount">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-charge">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row charge-row mb-2">
                                <div class="col-md-4">
                                    <select class="form-select charge-type" name="charges[0][charge_type]">
                                        <option value="">Select Charge Type</option>
                                        <option value="Other Charges">Other Charges</option>
                                        <option value="Bed Charges">Bed Charges</option>
                                        <option value="O.T. Charges">O.T. Charges</option>
                                        <option value="Doctor Charges">Doctor Charges</option>
                                        <option value="Diagnostic Charges">Diagnostic Charges</option>
                                        <option value="Medicine Charges">Medicine Charges</option>
                                        <option value="Service Charges">Service Charges</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" step="0.01" class="form-control charge-amount" 
                                           name="charges[0][amount]" placeholder="Amount">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-charge">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="add-charge">
                        <i class="ti ti-plus"></i> Add Charge
                    </button>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('packages.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check"></i> {{ (isset($package) && $package->id) ? 'Update' : 'Create' }} Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let chargeIndex = {{ (isset($package) && $package->id && $package->charges->count() > 0) ? $package->charges->count() : 1 }};

// Add charge row (no auto package-rate calculation; rate is fully manual)
document.getElementById('add-charge').addEventListener('click', function() {
    const container = document.getElementById('charges-container');
    const row = document.createElement('div');
    row.className = 'row charge-row mb-2';
    row.innerHTML = `
        <div class="col-md-4">
            <select class="form-select charge-type" name="charges[${chargeIndex}][charge_type]">
                <option value="">Select Charge Type</option>
                <option value="Other Charges">Other Charges</option>
                <option value="Bed Charges">Bed Charges</option>
                <option value="O.T. Charges">O.T. Charges</option>
                <option value="Doctor Charges">Doctor Charges</option>
                <option value="Diagnostic Charges">Diagnostic Charges</option>
                <option value="Medicine Charges">Medicine Charges</option>
                <option value="Service Charges">Service Charges</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="number" step="0.01" class="form-control charge-amount" 
                   name="charges[${chargeIndex}][amount]" placeholder="Amount">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-charge">
                <i class="ti ti-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);

    chargeIndex++;
});

// Remove charge row
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-charge')) {
        e.target.closest('.charge-row').remove();
    }
});
</script>
@endsection

