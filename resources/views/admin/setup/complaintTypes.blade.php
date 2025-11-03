@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    {{-- Settings Form --}}
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Complaint Type List</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                    <div class="input-icon-start position-relative me-2">
                                        <span class="input-icon-addon">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" class="form-control shadow-sm" placeholder="Search">
                                    </div>
                                    <div class="text-end d-flex">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                            data-bs-toggle="modal" data-bs-target="#complaintTypeModal"><i
                                                class="ti ti-plus me-1"></i>Create Complaint Type</a>
                                    </div>

                                    <!-- Complaint Type Modal -->
                                    <div class="modal fade" id="complaintTypeModal" tabindex="-1" aria-labelledby="complaintTypeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="complaintTypeModalLabel">Create Complaint Type</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="complaintTypeForm" method="POST" action="{{ route('complaint-types.store') }}">
                                                        @csrf
                                                        <input type="hidden" name="_method" id="complaintTypeFormMethod" value="POST">

                                                        <div id="complaintTypeFieldsWrapper">
                                                            <div class="complaint-type-item mb-3 d-flex gap-2">
                                                                <div class="flex-grow-1">
                                                                    <label class="form-label">Complaint Type</label>
                                                                    <input name="complaint_types[0][name]" class="form-control" required />
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <label class="form-label">Description</label>
                                                                    <input name="complaint_types[0][description]" class="form-control" required />
                                                                </div>
                                                                <button type="button" class="btn btn-danger btn-remove-complaint-type align-self-end">Remove</button>
                                                            </div>
                                                        </div>

                                                        <button type="button" class="btn btn-secondary mb-3" id="addComplaintTypeBtn">Add Another Complaint Type</button>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Complaint Types</button>
                                                </div>
                                                    </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Complaint Type</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($complaintTypes as $index => $complaintType)
                                                <tr>
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{ $complaintType->complaint_type }}</h6>
                                                    </td>
                                                    <td>{{ $complaintType->description }}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" 
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                            data-complaint-type-id="{{ $complaintType->id }}" 
                                                            data-complaint-type-name="{{ $complaintType->complaint_type }}" 
                                                            data-complaint-type-description="{{ $complaintType->description }}"
                                                            onclick="openComplaintTypeModal(this)">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" 
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                            data-bs-toggle="tooltip" 
                                                            title="Delete"
                                                            onclick="if(confirm('Are you sure you want to delete this complaint type ?')) { document.getElementById('delete-complaint-type-{{ $complaintType->id }}').submit(); }">
                                                            <i class="ti ti-trash"></i>
                                                        </a>

                                                        <form id="delete-complaint-type-{{ $complaintType->id }}" 
                                                            action="{{ route('complaint-types.destroy', $complaintType->id) }}" 
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                </div>
            
            </div>
        </div>
    </div>
</div>

<!-- Edit Complaint Type Modal -->
<div class="modal fade" id="editComplaintTypeModal" tabindex="-1" aria-labelledby="editComplaintTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title" id="editComplaintTypeModalLabel">Edit Complaint Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editComplaintTypeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Complaint Type</label>
                        <input type="text" name="name" id="editComplaintTypeName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" id="editComplaintTypeDescription" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Complaint Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS bundle (includes Popper) -->
<script>
    let complaintTypeIndex = 1;

    document.getElementById('addComplaintTypeBtn').addEventListener('click', function () {
        const wrapper = document.getElementById('complaintTypeFieldsWrapper');

        const newItem = document.createElement('div');
        newItem.classList.add('complaint-type-item', 'mb-3', 'd-flex', 'gap-2');
        newItem.innerHTML = `
            <div class="flex-grow-1">
                <label class="form-label">Complaint Type</label>
                <input name="complaint_types[${complaintTypeIndex}][name]" class="form-control" required />
            </div>
            <div class="flex-grow-1">
                <label class="form-label">Description</label>
                <input name="complaint_types[${complaintTypeIndex}][description]" class="form-control" required />
            </div>
            <button type="button" class="btn btn-danger btn-remove-complaint-type align-self-end">Remove</button>
        `;
        wrapper.appendChild(newItem);
        complaintTypeIndex++;
    });

    document.getElementById('complaintTypeFieldsWrapper').addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-complaint-type')) {
            e.target.closest('.complaint-type-item').remove();
        }
    });
</script>
<script>
    function openComplaintTypeModal(el) {
        let id = el.getAttribute("data-complaint-type-id");
        let name = el.getAttribute("data-complaint-type-name");
        let description = el.getAttribute("data-complaint-type-description");

        // Fill modal inputs
        document.getElementById("editComplaintTypeName").value = name;
        document.getElementById("editComplaintTypeDescription").value = description;

        // Update form action dynamically
        let form = document.getElementById("editComplaintTypeForm");
        form.action = "{{ url('complaintType') }}/update/" + id; // route to update

        // Show modal
        let modal = new bootstrap.Modal(document.getElementById("editComplaintTypeModal"));
        modal.show();
    }
</script>


@endsection