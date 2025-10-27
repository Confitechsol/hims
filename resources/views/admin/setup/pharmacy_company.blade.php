{{-- resources/views/admin/setup/pharmacy_company.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-building me-2"></i>Pharmacy Companies</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                                            <i class="ti ti-plus me-1"></i>Add Company
                                        </button>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Company Name</th>
                                                    <th>Created At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($companies as $company)
                                                    <tr>
                                                        <td>{{ $company->id }}</td>
                                                        <td>{{ $company->company_name }}</td>
                                                        <td>{{ $company->created_at->format('d M Y') }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning" 
                                                                    onclick="editCompany({{ $company->id }}, '{{ $company->company_name }}')">
                                                                <i class="ti ti-edit"></i> Edit
                                                            </button>
                                                            <form action="{{ route('pharmacy.company.destroy') }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('Are you sure?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="id" value="{{ $company->id }}">
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="ti ti-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No companies found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        {{ $companies->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Company Modal --}}
    <div class="modal fade" id="addCompanyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Pharmacy Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pharmacy.company.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Company Modal --}}
    <div class="modal fade" id="editCompanyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pharmacy Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pharmacy.company.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_company_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="edit_company_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function editCompany(id, name) {
            $('#edit_company_id').val(id);
            $('#edit_company_name').val(name);
            $('#editCompanyModal').modal('show');
        }
    </script>
    @endpush
@endsection

