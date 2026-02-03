@extends('layouts.adminLayout')
@section('content')
    <style>
        .gst-suggestions {
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .gst-suggestions .cursor-pointer:hover {
            background-color: #f8f9fa !important;
        }
        .gst-suggestions .cursor-pointer:active {
            background-color: #e9ecef !important;
        }
    </style>
    <div class="container">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Bed Groups</h5>
            </div>
            <div class="card-body">
                <x-table-actions.actions id="bed-groups" name="Bed Group" />
                {{-- Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered" id="bed-groups">
                        <thead>
                            <tr>
                                <th>Bed Group Name</th>
                                <th>Floor</th>
                                <th>Bed Cost</th>
                                <th>SAC Code</th>
                                <th>GST Rate (%)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bedGroups as $group)
                                <tr>
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->floorDetail->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($group->bed_cost, 2) }}</td>
                                    <td>{{ $group->sac_hsn_code ?? 'N/A' }}</td>
                                    <td>{{ $group->gst_rate ? number_format($group->gst_rate, 2) : 'N/A' }}</td>
                                    <td>
                                        <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $group->id }}"
                                            data-name="{{ $group->name }}" data-floor="{{ $group->floor }}"
                                            data-color="{{ $group->color }}" data-cost="{{ $group->bed_cost }}"
                                            data-description="{{ $group->description }}"
                                            data-sac-hsn-code="{{ $group->sac_hsn_code ?? '' }}"
                                            data-gst-rate="{{ $group->gst_rate ?? '' }}">
                                            <i class="ti ti-pencil"></i>
                                        </button>

                                        <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-id="{{ $group->id }}" data-name="{{ $group->name }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Links --}}
                <div class="mt-3" id="pagination-wrapper">
                    @php
                        $currentPage = $bedGroups->currentPage();
                        $lastPage = $bedGroups->lastPage();
                    @endphp

                    {{-- Previous --}}
                    @if ($bedGroups->onFirstPage())
                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                    @else
                        <a href="{{ $bedGroups->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                            class="btn btn-outline-secondary btn-sm me-1">
                            « Prev
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @for ($page = 1; $page <= $lastPage; $page++)
                        @if ($page == $currentPage)
                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                        @else
                            <a href="{{ $bedGroups->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                class="btn btn-outline-secondary btn-sm me-1">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    {{-- Next --}}
                    @if ($bedGroups->hasMorePages())
                        <a href="{{ $bedGroups->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                            class="btn btn-outline-secondary btn-sm">
                            Next »
                        </a>
                    @else
                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('bed-groups.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Bed Group</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Bed Group Name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Floor</label>
                        <select name="floor" id="" class="form-select" required>
                            <option value="">Select Floor</option>
                            @foreach ($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Color</label>
                        <input type="color" class="form-control" name="color">
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Bed Cost</label>
                        <input type="text" class="form-control" name="bed_cost">
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Description</label>
                        <input type="text" class="form-control" name="description" id="create-description">
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">SAC/HSN Code</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" name="sac_hsn_code" id="create-sac-hsn-code" 
                                placeholder="Search and select SAC/HSN Code" autocomplete="off">
                            <input type="hidden" id="create-gst-master-id">
                            <div id="create-gst-suggestions" class="position-absolute w-100 bg-white border shadow-lg gst-suggestions" 
                                style="z-index: 1050; max-height: 300px; overflow-y: auto; display: none; top: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">GST Rate (%)</label>
                        <input type="number" class="form-control" name="gst_rate" id="create-gst-rate" 
                            step="0.01" min="0" max="100" placeholder="GST Rate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('bed-groups.update') }}" method="POST" class="modal-content">
                @csrf @method('PUT')
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bed Group</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit-name"
                            placeholder="Bed Group Name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Floor</label>
                        <select name="floor" id="edit-floor" class="form-select" required>
                            <option value="">Select Floor</option>
                            @foreach ($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Color</label>
                        <input type="color" id="edit-color" class="form-control" name="color">
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Bed Cost</label>
                        <input type="text" id="edit-cost" class="form-control" name="bed_cost">
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Description</label>
                        <input type="text" id="edit-description" class="form-control" name="description">
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">SAC/HSN Code</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" name="sac_hsn_code" id="edit-sac-hsn-code" 
                                placeholder="Search and select SAC/HSN Code" autocomplete="off">
                            <input type="hidden" id="edit-gst-master-id">
                            <div id="edit-gst-suggestions" class="position-absolute w-100 bg-white border shadow-lg gst-suggestions" 
                                style="z-index: 1050; max-height: 300px; overflow-y: auto; display: none; top: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="" class="form-label">GST Rate (%)</label>
                        <input type="number" class="form-control" name="gst_rate" id="edit-gst-rate" 
                            step="0.01" min="0" max="100" placeholder="GST Rate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('bed-groups.destroy') }}" method="POST" class="modal-content">
                @csrf @method('DELETE')
                <input type="hidden" name="id" id="delete-id">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Bed Group</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="delete-name"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS to populate modals --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                document.getElementById('edit-id').value = button.getAttribute('data-id');
                document.getElementById('edit-name').value = button.getAttribute('data-name');
                document.getElementById('edit-floor').value = button.getAttribute('data-floor');
                document.getElementById('edit-color').value = button.getAttribute('data-color');
                document.getElementById('edit-cost').value = button.getAttribute('data-cost');
                document.getElementById('edit-description').value = button.getAttribute('data-description');
                document.getElementById('edit-sac-hsn-code').value = button.getAttribute('data-sac-hsn-code') || '';
                document.getElementById('edit-gst-rate').value = button.getAttribute('data-gst-rate') || '';
            });

            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                document.getElementById('delete-id').value = button.getAttribute('data-id');
                document.getElementById('delete-name').textContent = button.getAttribute('data-name');
            });
createAjaxTable({
    apiUrl: "{{ route('bed-groups.index') }}",
    tableSelector: "#bed-groups",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        // Handle both floor_detail (snake_case from JSON) and floorDetail (camelCase)
        const floorData = item.floor_detail || item.floorDetail || null;
        const floorName = floorData ? floorData.name : 'N/A';
        const bedCost = item.bed_cost ? parseFloat(item.bed_cost).toFixed(2) : '0.00';
        const sacCode = item.sac_hsn_code || 'N/A';
        const gstRate = item.gst_rate ? parseFloat(item.gst_rate).toFixed(2) : 'N/A';
        
        row.innerHTML = `
            <td class="name">${item.name}</td>
            <td>${floorName}</td>
            <td>${bedCost}</td>
            <td>${sacCode}</td>
            <td>${gstRate}</td>
            <td>
                <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#editModal"
                    data-id="${item.id}"
                    data-name="${item.name}"
                    data-floor="${item.floor}"
                    data-color="${item.color || '#f4f4f4'}" 
                    data-cost="${item.bed_cost || ''}"
                    data-description="${item.description || ''}"
                    data-sac-hsn-code="${item.sac_hsn_code || ''}"
                    data-gst-rate="${item.gst_rate || ''}">
                    <i class="ti ti-pencil"></i>
                </button>
                <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#deleteModal"
                    data-id="${item.id}"
                    data-name="${item.name}">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        return row;
    }
});

            // GST Master Autocomplete for Create Modal
            setupGstAutocomplete('create-sac-hsn-code', 'create-gst-suggestions', 'create-description', 'create-gst-rate');
            
            // GST Master Autocomplete for Edit Modal
            setupGstAutocomplete('edit-sac-hsn-code', 'edit-gst-suggestions', 'edit-description', 'edit-gst-rate');
        });

        // Function to setup GST Master autocomplete
        function setupGstAutocomplete(inputId, suggestionsId, descriptionId, gstRateId) {
            const input = document.getElementById(inputId);
            const suggestionsDiv = document.getElementById(suggestionsId);
            const descriptionInput = document.getElementById(descriptionId);
            const gstRateInput = document.getElementById(gstRateId);
            let searchTimeout;

            input.addEventListener('input', function() {
                const searchTerm = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (searchTerm.length < 1) {
                    suggestionsDiv.style.display = 'none';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('bed-groups.gst-masters') }}?search=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data.length > 0) {
                                suggestionsDiv.innerHTML = '';
                                data.data.forEach(gst => {
                                    const item = document.createElement('div');
                                    item.className = 'p-2 border-bottom cursor-pointer';
                                    item.style.cursor = 'pointer';
                                    item.innerHTML = `
                                        <div class="fw-bold">${gst.code}</div>
                                        <div class="text-muted small">${gst.description}</div>
                                        <div class="text-muted small">GST Rate: ${gst.gst_rate}%</div>
                                    `;
                                    item.addEventListener('click', function() {
                                        input.value = gst.code;
                                        descriptionInput.value = gst.description;
                                        gstRateInput.value = gst.gst_rate;
                                        suggestionsDiv.style.display = 'none';
                                    });
                                    item.addEventListener('mouseenter', function() {
                                        this.style.backgroundColor = '#f8f9fa';
                                    });
                                    item.addEventListener('mouseleave', function() {
                                        this.style.backgroundColor = 'white';
                                    });
                                    suggestionsDiv.appendChild(item);
                                });
                                suggestionsDiv.style.display = 'block';
                            } else {
                                suggestionsDiv.innerHTML = '<div class="p-2 text-muted">No results found</div>';
                                suggestionsDiv.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching GST Masters:', error);
                            suggestionsDiv.style.display = 'none';
                        });
                }, 300);
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(event) {
                if (!input.contains(event.target) && !suggestionsDiv.contains(event.target)) {
                    suggestionsDiv.style.display = 'none';
                }
            });

            // Handle Enter key
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const firstItem = suggestionsDiv.querySelector('div');
                    if (firstItem && firstItem.classList.contains('cursor-pointer')) {
                        firstItem.click();
                    }
                }
            });
        }
    </script>

@endsection
