{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-ambulance me-2"></i>Ambulance Call List
                </h5>
            </div>

            <div class="card-body">

                <div class="card">
                    <div class="card-body">

                        {{-- Search + Actions --}}
                        <div
                            class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                            <form method="GET" action=""
                                class="input-icon-start position-relative me-2 d-flex align-items-center">
                                <span class="input-icon-addon">
                                    <i class="ti ti-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control shadow-sm"
                                    placeholder="Search by contact / vehicle / driver"
                                    value="{{ request('search') }}" style="max-width: 300px;">
                                <button type="submit" class="btn btn-primary ms-2">Search</button>
                            </form>

                            <div class="page_btn d-flex">
                                <button type="button"
                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addAmbulanceCallModal">
                                    <i class="ti ti-plus me-1"></i>Add New Ambulance Call
                                </button>
                            </div>
                            <div class="modal fade" id="addAmbulanceCallModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-scrollable">
                                    <div class="modal-content">

                                        <form action="{{ route('ambulanceCall.addCall') }}" method="POST">
                                            @csrf

                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-ambulance me-2"></i>Add Ambulance Call
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="row g-3">

                                                    <div class="col-md-6">
                                                        <label class="form-label">Patient name</label>
                                                        <select name="patient_id" class="form-select">
                                                            <option value="">Select Patient</option>
                                                            @foreach ($patients as $patient)
                                                                <option value="{{ $patient->id }}">
                                                                    {{ $patient->patient_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Contact No</label>
                                                        <input type="text" name="contact_no" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Call From</label>
                                                        <input type="text" name="call_from" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Call To</label>
                                                        <input type="text" name="call_to" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Vehicle</label>
                                                        <select name="vehicle_id" class="form-select">
                                                            <option value="">Select Vehicle</option>
                                                            @foreach ($vehicles as $vehicle)
                                                                <option value="{{ $vehicle->id }}">
                                                                    {{ $vehicle->vehicle_no ?? $vehicle->vehicle_model }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Driver</label>
                                                        <input type="text" name="driver" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Charge Category</label>
                                                        <select name="charge_category_id" id="charge_category_id"
                                                            class="form-select" required>
                                                            <option value="">Select Category</option>
                                                            @foreach ($chargeCategories as $category)
                                                                <option value="{{ $category->id }}">
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Charge Name</label>
                                                        <select name="charge_id" id="charge_id"
                                                            class="form-select" required disabled>
                                                            <option value="">Select Charge</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Standard Charge</label>
                                                        <input type="number" step="0.01" name="standard_charge"
                                                            id="standard_charge" class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Date</label>
                                                        <input type="datetime-local" name="date" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label class="form-label">Address</label>
                                                        <textarea name="address" class="form-control" rows="2"></textarea>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Amount</label>
                                                        <input type="number" step="0.01" name="amount" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Net Amount</label>
                                                        <input type="number" step="0.01" name="net_amount" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label class="form-label">Note</label>
                                                        <textarea name="note" class="form-control" rows="2"></textarea>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">
                                                    Save Ambulance Call
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>


                        </div>

                        {{-- Success / Error --}}
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        {{-- Table --}}
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select_all"> #
                                        </th>
                                        <th>Patient</th>
                                        <th>Contact</th>
                                        <th>Vehicle</th>
                                        <th>Driver</th>
                                        <th>Call From</th>
                                        <th>Call To</th>
                                        <th>Date</th>
                                        <th>Net Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($calls as $call)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="select_item"
                                                    value="{{ $call->id }}">
                                            </td>

                                            <td>
                                                {{ $call->patient->patient_name ?? 'Walk-in' }}
                                            </td>

                                            <td>{{ $call->contact_no }}</td>

                                            <td>
                                                {{ $call->vehicle->vehicle_no ?? $call->vehicle_model ?? '-' }}
                                            </td>

                                            <td>{{ $call->driver ?? '-' }}</td>

                                            <td>{{ $call->call_from }}</td>

                                            <td>{{ $call->call_to }}</td>

                                            <td>
                                                {{ $call->date?->format('d M Y, h:i A') }}
                                            </td>

                                            <td>
                                                ₹ {{ number_format($call->net_amount, 2) }}
                                            </td>

                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('ambulanceCall.editCall', $call->id) }}"
                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>

                                                    <form action="{{ route('ambulanceCall.deleteCall', $call->id) }}"
                                                        method="POST" class="ms-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                            onclick="return confirm('Delete this ambulance call?')">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
                                                No ambulance calls found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $calls->withQueryString()->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Select All --}}

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
    <script>
        document.getElementById('charge_category_id').addEventListener('change', function () {
            const categoryId = this.value;
            const chargeSelect = document.getElementById('charge_id');
            const standardCharge = document.getElementById('standard_charge');

            chargeSelect.innerHTML = '<option value="">Select Charge</option>';
            chargeSelect.disabled = true;
            standardCharge.value = '';

            if (!categoryId) return;

            const chargeByCategoryUrl = "{{ route('charges.byCategory', ':id') }}";

            fetch(chargeByCategoryUrl.replace(':id', categoryId))
                .then(res => res.json())
                .then(data => {
                    data.forEach(charge => {
                        chargeSelect.innerHTML +=
                            `<option value="${charge.id}">${charge.name}</option>`;
                    });
                    chargeSelect.disabled = false;
                });
            });

        document.getElementById('charge_id').addEventListener('change', function () {
            const chargeId = this.value;
            const standardCharge = document.getElementById('standard_charge');

            standardCharge.value = '';

            if (!chargeId) return;

            fetch(`/charges/${chargeId}`)
                .then(res => res.json())
                .then(data => {
                    standardCharge.value = data.standard_charge;
                });
        });
    </script>

@endsection
