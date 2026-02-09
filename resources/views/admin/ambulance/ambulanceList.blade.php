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
                                    data-bs-target="#addAmbulanceModal">
                                    <i class="ti ti-plus me-1"></i>Add New Ambulance List
                                </button>
                                <a type="button"
                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                    href="{{ route('ambulanceList.index') }}">
                                    Ambulance Call
                                </a>
                            </div>
                            <div class="modal fade" id="addAmbulanceModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-scrollable">
                                    <div class="modal-content">

                                        <form action="{{ route('ambulanceList.addList') }}" method="POST">
                                            @csrf

                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-ambulance me-2"></i>Add Ambulance List
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="row g-3">

                                                    <div class="col-md-6">
                                                        <label class="form-label">Vehicle Number</label>
                                                        <input type="text" name="vehicle_no" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Vehicle Model</label>
                                                        <input type="text" name="vehicle_model" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Year Made</label>
                                                        <input type="text" name="year_made" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Driver Name</label>
                                                        <input type="text" name="driver_name" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Driver License</label>
                                                        <input type="text" name="driver_license" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Driver Contact No</label>
                                                        <input type="text" name="driver_contact_no" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Vehicle Type</label>
                                                        <select name="vehicle_type" id="vehicle_type"
                                                            class="form-select" required>
                                                                <option value="">Select Vehicle Type</option>
                                                            
                                                                <option value="Owned">Owned
                                                                </option>
                                                                <option value="Contractual">Contractual
                                                                </option>
                                                        </select>
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
                                                    Save Ambulance
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
                                        
                                        
                                        <th>Vehicle Number</th>
                                        <th>Vehicle Name</th>
                                        <th>Year Made</th>
                                        <th>Driver Name</th>
                                        <th>Driver License</th>
                                        <th>Driver Contact</th>
                                        <th>Vehicle Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($vehicles as $call)
                                        <tr>
                                            

                                            <td>
                                                {{ $call->vehicle_no ?? '-' }}
                                            </td>

                                            <td>{{ $call->vehicle_model ?? '-' }}</td>

                                            <td>
                                                {{ $call->manufacture_year ?? '-' }}
                                            </td>

                                            <td>{{ $call->driver_name ?? '-' }}</td>

                                            <td>{{ $call->driver_licence }}</td>

                                            <td>{{ $call->driver_contact }}</td>

                                            <td>
                                                {{ $call->vehicle_type }}
                                            </td>

                                            

                                            <td>
                                                <div class="d-flex">
                                                    <a href="javascript:void(0)"
                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-ambulance"
                                                        data-id="{{ $call->id }}">
                                                            <i class="ti ti-pencil"></i>
                                                    </a>
                                                    <div class="modal fade" id="editAmbulanceModal" tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <form id="editAmbulanceForm" method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">
                                                                            <i class="fas fa-ambulance me-2"></i>Edit Ambulance
                                                                        </h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="row g-3">

                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Vehicle Number</label>
                                                                                <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" required>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Vehicle Model</label>
                                                                                <input type="text" name="vehicle_model" id="vehicle_model" class="form-control" required>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Year Made</label>
                                                                                <input type="text" name="year_made" id="year_made" class="form-control" required>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Driver Name</label>
                                                                                <input type="text" name="driver_name" id="driver_name" class="form-control" required>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Driver License</label>
                                                                                <input type="text" name="driver_license" id="driver_license" class="form-control" required>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Driver Contact</label>
                                                                                <input type="text" name="driver_contact_no" id="driver_contact_no" class="form-control" required>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Vehicle Type</label>
                                                                                <select name="vehicle_type" id="vehicle_type" class="form-select">
                                                                                    <option value="Owned">Owned</option>
                                                                                    <option value="Contractual">Contractual</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-12">
                                                                                <label class="form-label">Note</label>
                                                                                <textarea name="note" id="note" class="form-control"></textarea>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>


                                                    <form action="{{ route('ambulanceList.deleteList', $call->id) }}"
                                                        method="POST" class="ms-1">
                                                        @csrf
                                                        @method('DELETE')

                                                        <a href="javascript:void(0)"
                                                        class="btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                        onclick="if(confirm('Delete this ambulance call?')) this.closest('form').submit();">
                                                            <i class="ti ti-trash"></i>
                                                        </a>
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
                            {{ $vehicles->withQueryString()->links() }}
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

<script>
    const editAmbulanceRoute   = "{{ route('ambulanceList.editList', ':id') }}";
    const updateAmbulanceRoute = "{{ route('ambulanceList.updateList', ':id') }}";
    document.querySelectorAll('.edit-ambulance').forEach(btn => {
        btn.addEventListener('click', function () {
            let id = this.dataset.id;

            fetch(editAmbulanceRoute.replace(':id', id))
                .then(res => res.json())
                .then(data => {

                    document.getElementById('vehicle_no').value = data.vehicle_no;
                    document.getElementById('vehicle_model').value = data.vehicle_model;
                    document.getElementById('year_made').value = data.manufacture_year;
                    document.getElementById('driver_name').value = data.driver_name;
                    document.getElementById('driver_license').value = data.	driver_licence;
                    document.getElementById('driver_contact_no').value = data.driver_contact;
                    document.getElementById('vehicle_type').value = data.vehicle_type;
                    document.getElementById('note').value = data.note ?? '';

                    document.getElementById('editAmbulanceForm').action =
                        updateAmbulanceRoute.replace(':id', id);

                    new bootstrap.Modal(
                        document.getElementById('editAmbulanceModal')
                    ).show();
                });
        });
    });
</script>



@endsection
