@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-shield-alt me-2"></i>Insurance Management</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
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
                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <div class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="input-icon-start position-relative me-2">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input onkeyup="dataSearch()" type="text" id="insurance-search" name="search"
                                                class="form-control shadow-sm" placeholder="Search">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <a href="{{ route('insurance.rate-panels') }}" class="btn btn-outline-primary btn-md">
                                            <i class="ti ti-file-invoice me-1"></i>Test Rates</a>
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary text-white btn-md"
                                            data-bs-toggle="modal" data-bs-target="#add_insurance">
                                            <i class="ti ti-plus me-1"></i>Add Insurance
                                        </a>
                                    </div>
                                </div>

                                <div class="table-responsive table-nowrap">
                                    <table class="table" id="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Rate Panels</th>
                                                <th>Phone</th>
                                                <th>Contact Person</th>
                                                <th>TPAs</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($insuranceCompanies as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->code }}</td>
                                                <td>
                                                    @forelse($item->ratePanels as $panel)
                                                        <span class="badge bg-info text-dark me-1 mb-1">{{ $panel->name }}</span>
                                                    @empty
                                                        <span class="text-muted small">None</span>
                                                    @endforelse
                                                </td>
                                                <td>{{ $item->contact_no ?? 'N/A' }}</td>
                                                <td>{{ $item->contact_person_name ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('tpamanagement', ['insurance_company_id' => $item->id]) }}"
                                                        class="badge bg-primary text-decoration-none">
                                                        {{ $item->tpas_count }} TPA(s)
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-insurance-btn"
                                                            data-id="{{ $item->id }}"
                                                            data-name="{{ $item->name }}"
                                                            data-code="{{ $item->code }}"
                                                            data-contact_no="{{ $item->contact_no }}"
                                                            data-address="{{ $item->address }}"
                                                            data-contact_person_name="{{ $item->contact_person_name }}"
                                                            data-contact_person_phone="{{ $item->contact_person_phone }}"
                                                            data-rate_panel_ids="{{ $item->ratePanels->pluck('id')->join(',') }}">
                                                            <i class="ti ti-pencil"></i>
                                                        </button>
                                                        <form method="POST" action="{{ route('insurance.management.destroy') }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                            <button type="submit"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                                onclick="return confirm('Are you sure you want to delete this insurance company?');">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $insuranceCompanies->currentPage();
                                        $lastPage = $insuranceCompanies->lastPage();
                                    @endphp
                                    @if ($insuranceCompanies->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $insuranceCompanies->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif
                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $insuranceCompanies->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                        @endif
                                    @endfor
                                    @if ($insuranceCompanies->hasMorePages())
                                        <a href="{{ $insuranceCompanies->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm">Next »</a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Insurance --}}
<div class="modal fade" id="add_insurance" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('insurance.management.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Insurance Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Insurance Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person Phone</label>
                            <input type="text" name="contact_person_phone" class="form-control" value="{{ old('contact_person_phone') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person Name</label>
                            <input type="text" name="contact_person_name" class="form-control" value="{{ old('contact_person_name') }}">
                        </div>
                        @include('admin.insurance.partials.rate_panel_checkboxes', [
                            'inputPrefix' => 'add-',
                            'selectedIds' => old('rate_panel_ids', []),
                        ])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Insurance --}}
<div class="modal fade" id="edit_insurance" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('insurance.management.update') }}" method="POST" id="edit_insurance_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_insurance_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Insurance Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Insurance Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="edit_code" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="contact_no" id="edit_contact_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person Phone</label>
                            <input type="text" name="contact_person_phone" id="edit_contact_person_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" id="edit_address" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person Name</label>
                            <input type="text" name="contact_person_name" id="edit_contact_person_name" class="form-control">
                        </div>
                        <div class="col-12" id="edit_rate_panels_wrap">
                            @include('admin.insurance.partials.rate_panel_checkboxes', [
                                'inputPrefix' => 'edit-',
                                'selectedIds' => [],
                            ])
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setRatePanelCheckboxes(container, idsCsv) {
    const ids = (idsCsv || '').split(',').filter(Boolean).map(String);
    container.querySelectorAll('.rate-panel-checkbox').forEach(cb => {
        cb.checked = ids.includes(String(cb.value));
    });
}

document.querySelectorAll('.edit-insurance-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('edit_insurance_id').value = this.dataset.id || '';
        document.getElementById('edit_name').value = this.dataset.name || '';
        document.getElementById('edit_code').value = this.dataset.code || '';
        document.getElementById('edit_contact_no').value = this.dataset.contact_no || '';
        document.getElementById('edit_address').value = this.dataset.address || '';
        document.getElementById('edit_contact_person_name').value = this.dataset.contact_person_name || '';
        document.getElementById('edit_contact_person_phone').value = this.dataset.contact_person_phone || '';
        setRatePanelCheckboxes(document.getElementById('edit_insurance'), this.dataset.rate_panel_ids || '');
        new bootstrap.Modal(document.getElementById('edit_insurance')).show();
    });
});

function dataSearch() {
    const data = document.querySelector('#insurance-search');
    const table = document.querySelector('#table tbody');

    fetch("{{ route('insurance.management') }}?search=" + encodeURIComponent(data.value))
        .then(res => res.json())
        .then(data => {
            if (data.status == 200) {
                table.innerHTML = '';
                data.result.forEach((item) => {
                    const panels = (item.rate_panels_label && item.rate_panels_label !== '—')
                        ? item.rate_panels_label.split(', ').map(p => `<span class="badge bg-info text-dark me-1 mb-1">${p}</span>`).join('')
                        : '<span class="text-muted small">None</span>';
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.code}</td>
                        <td>${panels}</td>
                        <td>${item.contact_no ?? 'N/A'}</td>
                        <td>${item.contact_person_name ?? 'N/A'}</td>
                        <td><span class="badge bg-primary">${item.tpas_count ?? 0} TPA(s)</span></td>
                        <td>
                            <div class="d-flex">
                                <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-insurance-btn"
                                    data-id="${item.id}"
                                    data-name="${item.name}"
                                    data-code="${item.code}"
                                    data-contact_no="${item.contact_no ?? ''}"
                                    data-address="${item.address ?? ''}"
                                    data-contact_person_name="${item.contact_person_name ?? ''}"
                                    data-contact_person_phone="${item.contact_person_phone ?? ''}"
                                    data-rate_panel_ids="${item.rate_panel_ids ?? ''}">
                                    <i class="ti ti-pencil"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    table.appendChild(row);
                });
                document.querySelectorAll('.edit-insurance-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.getElementById('edit_insurance_id').value = this.dataset.id || '';
                        document.getElementById('edit_name').value = this.dataset.name || '';
                        document.getElementById('edit_code').value = this.dataset.code || '';
                        document.getElementById('edit_contact_no').value = this.dataset.contact_no || '';
                        document.getElementById('edit_address').value = this.dataset.address || '';
                        document.getElementById('edit_contact_person_name').value = this.dataset.contact_person_name || '';
                        document.getElementById('edit_contact_person_phone').value = this.dataset.contact_person_phone || '';
                        setRatePanelCheckboxes(document.getElementById('edit_insurance'), this.dataset.rate_panel_ids || '');
                        new bootstrap.Modal(document.getElementById('edit_insurance')).show();
                    });
                });
            }
        })
        .catch(err => console.error(err));
}
</script>

@endsection
