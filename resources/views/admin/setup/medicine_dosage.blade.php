@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #750096">Medicine Dosage List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDosageModal">
                    <i class="ti ti-plus"></i> Add Medicine Dosage
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Category Name</th>
                            <th>Dosage</th>
                            <th>Unit</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosages as $dosage)
                        <tr>
                            <td>{{ $dosage->category->category_name ?? 'N/A' }}</td>
                            <td>{{ $dosage->dosage }}</td>
                            <td>{{ $dosage->unit->unit_name ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editDosage({{ json_encode($dosage) }})">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <form action="{{ route('setup.medicine-dosage.destroy', $dosage->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No dosages found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Dosage Modal -->
<div class="modal fade" id="addDosageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Add Medicine Dosage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('setup.medicine-dosage.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Medicine Category <span class="text-danger">*</span></label>
                        <select name="medicine_category_id" class="form-select" required>
                            <option value="">Select</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dose <span class="text-danger">*</span></label>
                        <input type="text" name="dosage" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit <span class="text-danger">*</span></label>
                        <select name="units_id" class="form-select" required>
                            <option value="">Select</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Dosage Modal -->
<div class="modal fade" id="editDosageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Edit Medicine Dosage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDosageForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Medicine Category <span class="text-danger">*</span></label>
                        <select name="medicine_category_id" id="edit_category_id" class="form-select" required>
                            <option value="">Select</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dose <span class="text-danger">*</span></label>
                        <input type="text" name="dosage" id="edit_dosage" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit <span class="text-danger">*</span></label>
                        <select name="units_id" id="edit_unit_id" class="form-select" required>
                            <option value="">Select</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editDosage(dosage) {
    document.getElementById('edit_category_id').value = dosage.medicine_category_id;
    document.getElementById('edit_dosage').value = dosage.dosage;
    document.getElementById('edit_unit_id').value = dosage.units_id;
    document.getElementById('editDosageForm').action = "{{ url('setup/medicine-dosage/update') }}/" + dosage.id;
    new bootstrap.Modal(document.getElementById('editDosageModal')).show();
}
</script>
@endsection
