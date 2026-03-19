{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

        <div class="row justify-content-center">
            {{-- Settings Form --}}
            <div class="col-md-11">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Role List</h5>
                    </div>

                    <div class="card-body">
                      

                            {{-- Hospital Name & Code --}}
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="card">

                                        <div class="card-body">
                                            <div
                                                class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                                 <form method="GET" action="" class="input-icon-start position-relative me-2 d-flex align-items-center">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" name="search" class="form-control shadow-sm" placeholder="Search" value="{{ request('search') }}" style="max-width: 300px;">
                                            <button type="submit" class="btn btn-primary ms-2">Search</button>
                                        </form>
                                                <div class="text-end d-flex">
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                        data-bs-toggle="modal" data-bs-target="#roleModal"><i
                                                            class="ti ti-plus me-1"></i>Create Role</a>
                                                </div>
                                                <!-- Modal -->
                                                <!-- <div class="modal fade" id="add_specialization" tabindex="-1"
                                                    aria-labelledby="addSpecializationLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header rounded-0"
                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                <h5 class="modal-title" id="addSpecializationLabel">Create
                                                                    Role</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('roles.store')  }}" method="POST">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label for="roleName" class="form-label">Role
                                                                            Name</label>
                                                                        <input id="name" name= "name" class="form-control" />
                                                                    </div>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Save Role</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <!-- Role Modal -->
                                                <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header rounded-0"
                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                <h5 class="modal-title" id="roleModalLabel">Create Role</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="roleForm" method="POST" action="{{ route('roles.store') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="_method" id="roleFormMethod" value="POST">
                                                                    <div class="mb-3">
                                                                        <label for="roleName" class="form-label">Role Name</label>
                                                                        <input id="roleName" name="name" class="form-control" required />
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Save Role</button>
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
                                                            <th>Role</th>
                                                            <th>Type</th>
                                                            <th>Action</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                         @foreach($roles as $index => $role)
                                                            <tr>
                                                                <th scope="row">{{ $index + 1 }}</th>
                                                                <td>
                                                                    <h6 class="mb-0 fs-14 fw-semibold">{{ $role->name }}</h6>
                                                                </td>
                                                                <td>{{ $role->type ?? 'N/A' }}</td>
                                                                <td>
                                                                    <a href="{{ route('permissions', $role->id) }}" 
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Assign Permission">
                                                                        <i class="ti ti-user-circle"></i>
                                                                    </a>
                                                                    <!-- <a href="javascript:void(0);" onclick="openEditRoleModal({{ $role->id }}, '{{ $role->name }}')" 
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Edit">
                                                                        <i class="ti ti-pencil"></i>
                                                                    </a> -->
                                                                    <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                        data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}" onclick="openRoleModal(this)">
                                                                        <i class="ti ti-pencil"></i>
                                                                    </a>
                                                                    <!-- <a href="javascript:void(0);" 
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Delete"
                                                                    onclick="confirmDelete('delete-role-{{ $role->id }}', 'Delete Role?', 'Are you sure you want to delete this role?')">
                                                                        <i class="ti ti-trash"></i>
                                                                    </a> -->

                                                                </td>
                                                                <td>
                                                                <form
                                                                    action="{{ route('roles.status', [$role->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="form-check form-switch mb-0">
                                                                        <input class="form-check-input status-toggle"
                                                                            type="checkbox" role="switch"
                                                                            id="switchCheckDefault_{{ $role->id }}"
                                                                            name="is_active" data-id="{{ $role->id }}"
                                                                            {{ $role->is_active == 1 ? 'checked' : '' }}>
                                                                    </div>

                                                                </form>
                                                            </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                                         {{-- Pagination Links --}}
                                         
                                    <div class="mt-3" id="pagination-wrapper">
                                        @php
                                            $currentPage = $roles->currentPage();
                                            $lastPage = $roles->lastPage();
                                            $window = 2; // how many pages to show on each side
                                            $start = max(1, $currentPage - $window);
                                            $end = min($lastPage, $currentPage + $window);
                                        @endphp

                                        @if ($roles->onFirstPage())
                                            <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                        @else
                                            <a href="{{ $roles->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                        @endif

                                        @if ($start > 1)
                                            <a href="{{ $roles->url(1) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">1</a>
                                            @if ($start > 2)
                                                <span class="btn btn-outline-secondary btn-sm me-1 disabled">...</span>
                                            @endif
                                        @endif

                                        @for ($page = $start; $page <= $end; $page++)
                                            @if ($page == $currentPage)
                                                <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                            @else
                                                <a href="{{ $roles->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                            @endif
                                        @endfor

                                        @if ($end < $lastPage)
                                            @if ($end < $lastPage - 1)
                                                <span class="btn btn-outline-secondary btn-sm me-1 disabled">...</span>
                                            @endif
                                            <a href="{{ $roles->url($lastPage) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">{{ $lastPage }}</a>
                                        @endif

                                        @if ($roles->hasMorePages())
                                            <a href="{{ $roles->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm">Next »</a>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                        @endif
                                    </div>


                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                </div> <!-- end col -->

                            </div>
                            <!-- <hr> -->
                            <!-- <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> Save Settings
                                </button>
                            </div> -->
                        
                    </div>
                </div>
            </div>
        </div>


    <!-- Bootstrap 5 JS bundle (includes Popper) -->
     <script>
        document.querySelectorAll('.status-toggle').forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
<script>
function openRoleModal(element) {
    const roleId = element.getAttribute('data-role-id');
    const roleName = element.getAttribute('data-role-name');
    
    const modalTitle = document.getElementById('roleModalLabel');
    const form = document.getElementById('roleForm');
    const nameInput = document.getElementById('roleName');

    if (roleId) {
        // Edit mode
        modalTitle.innerText = "Edit Role";
        // form.action = `hims/public/roles/${roleId}`; // Update route
        form.action = "{{ url('roles') }}/update/" + roleId;
        nameInput.value = roleName;

        // Add PUT method spoof
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    } else {
        // Create mode
        modalTitle.innerText = "Create Role";
        form.action = "{{ route('roles.store') }}";
        nameInput.value = "";
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
    }

    // Show modal
    const roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
    roleModal.show();
}

</script>

@endsection
