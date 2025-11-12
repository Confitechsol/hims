@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #750096">Dosage Duration List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDurationModal">
                    <i class="ti ti-plus"></i> Add Dosage Duration
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
                            <th>Name</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($durations as $duration)
                        <tr>
                            <td>{{ $duration->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editDuration({{ $duration->id }}, '{{ $duration->name }}')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <form action="{{ route('setup.dose-duration.destroy', $duration->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
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
                            <td colspan="2" class="text-center">No durations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Duration Modal -->
<div class="modal fade" id="addDurationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Add Dosage Duration</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('setup.dose-duration.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Duration <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
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

<!-- Edit Duration Modal -->
<div class="modal fade" id="editDurationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Edit Dosage Duration</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDurationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Duration <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_duration" class="form-control" required>
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
function editDuration(id, duration) {
    document.getElementById('edit_duration').value = duration;
    document.getElementById('editDurationForm').action = "{{ url('setup/dose-duration/update') }}/" + id;
    new bootstrap.Modal(document.getElementById('editDurationModal')).show();
}
</script>
@endsection

