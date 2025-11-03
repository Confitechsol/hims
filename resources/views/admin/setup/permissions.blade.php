@extends('layouts.adminLayout')
@section('content')


    <!-- Start Content -->
    <div class="content" id="profilePage">

        <!-- Page Header -->
        <div class="mb-3 border-bottom pb-3">
            <h4 class="fw-bold mb-0">Settings</h4>
        </div>
        <!-- End Page Header -->


        <div class="card">
    <div class="card-body p-0">
        <div class="settings-wrapper d-flex">

            <!-- Sidebar -->
            <div class="sidebars settings-sidebar" id="sidebar2">
                <div class="sidebar-inner" data-simplebar>
                    <div id="sidebar-menu5" class="sidebar-menu mt-0 p-0">
                        <ul class="nav flex-column" id="permissionTabs" role="tablist">
                            @foreach($permissionGroups as $index => $group)
                                <li class="nav-item">
                                    <a class="nav-link {{ $index == 0 ? 'active' : '' }}" 
                                       id="{{ $group->short_code }}-tab" 
                                       data-bs-toggle="tab"
                                       href="#{{ $group->short_code }}" 
                                       role="tab"
                                       aria-controls="{{ $group->short_code }}"
                                       aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                        <i class="ti ti-device-desktop-cog me-2"></i> {{ $group->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Sidebar -->

            <!-- Main Card -->
            <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none">
                <div class="card-header border-bottom px-0 mx-3">
                    <h6 class="fs-14 mb-3">
                        <a href="{{ route('roles') }}">
                            <i class="ti ti-chevron-left me-1"></i> Roles
                        </a>
                    </h6>
                    <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                        <div class="flex-grow-1">
                            <h4 class="fw-bold mb-0">Permissions</h4>
                        </div>
                        <div class="text-end d-flex">
                            <div class="dropdown">
                                <a href="javascript:void(0);"
                                   class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                                   data-bs-toggle="dropdown">
                                    <span class="text-body me-1">Role : </span> {{ $role->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-2">
                                    {{-- Add other roles dynamically if needed --}}
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Admin</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">User</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="card-body px-0 mx-3">
                    <form id="permissionsForm" method="POST" action="{{ route('roles.permissions.save') }}">
    @csrf
    <input type="hidden" name="role_id" value="{{ $roleId }}">

    <div class="tab-content" id="permissionTabsContent">
        @foreach($permissionGroups as $index => $group)
            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" 
                 id="{{ $group->short_code }}" 
                 role="tabpanel" 
                 aria-labelledby="{{ $group->short_code }}-tab">

                <div class="table-responsive border">
                    <table class="table table-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th>Feature</th>
                                <th>View</th>
                                <th>Add</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group->categories as $category)
                                @php $permissions = $category->rolePermissions->first(); @endphp
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td><input type="checkbox" name="permissions[{{ $category->id }}][]" value="can_view" {{ $permissions && $permissions->can_view ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="permissions[{{ $category->id }}][]" value="can_add" {{ $permissions && $permissions->can_add ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="permissions[{{ $category->id }}][]" value="can_edit" {{ $permissions && $permissions->can_edit ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="permissions[{{ $category->id }}][]" value="can_delete" {{ $permissions && $permissions->can_delete ? 'checked' : '' }}></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        @endforeach
    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fa fa-save me-1"></i> Save
        </button>
    </div>
</form>


                </div>
                <!-- End Tab Content -->

            </div>
        </div>
    </div>
</div>



        <!-- end card -->

    </div>
    <!-- End Content -->

{{-- Optional JS for "Select All" functionality --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.select-all').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                let groupId = this.dataset.group;
                let checkboxes = document.querySelectorAll(`.permission-checkbox[data-category][data-role]`);
                checkboxes.forEach(function(cb) {
                    if(cb.closest('.tab-pane').id == groupId) {
                        cb.checked = event.target.checked;
                    }
                });
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var triggerTabList = [].slice.call(document.querySelectorAll('#permissionTabs a'))
        triggerTabList.forEach(function(triggerEl) {
            var tab = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function(e) {
                e.preventDefault()
                tab.show()
            })
        })
    });
</script>
<script>
document.getElementById("permissionsForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong while saving.'
            });
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: 'Unable to save permissions. Please try again later.'
        });
    });
});

</script>

@endsection