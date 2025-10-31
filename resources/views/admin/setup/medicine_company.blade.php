@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #750096">Company List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                    <i class="ti ti-plus"></i> Add Company
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
                            <th>Database ID</th>
                            <th>Company Name</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td>{{ $company->id }}</td>
                            <td>{{ $company->company_name }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editCompany({{ $company->id }}, '{{ $company->company_name }}')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <form action="{{ route('medicine-company.destroy', $company->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
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
                            <td colspan="3" class="text-center">No companies found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Company Modal -->
<div class="modal fade" id="addCompanyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Add Company</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('medicine-company.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" required>
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

<!-- Edit Company Modal -->
<div class="modal fade" id="editCompanyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Edit Company</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCompanyForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" id="edit_company_name" class="form-control" required>
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
function editCompany(id, name) {
    document.getElementById('edit_company_name').value = name;
    document.getElementById('editCompanyForm').action = "{{ url('setup/medicine-company/update') }}/" + id;
    new bootstrap.Modal(document.getElementById('editCompanyModal')).show();
}
</script>
@endsection

