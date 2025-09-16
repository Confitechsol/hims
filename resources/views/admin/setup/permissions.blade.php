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
                    <div class="tab-content" id="permissionTabsContent">
                        @foreach($permissionGroups as $index => $group)
                            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" 
                                 id="{{ $group->short_code }}" 
                                 role="tabpanel" 
                                 aria-labelledby="{{ $group->short_code }}-tab">
                                
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold mb-0">{{ $group->name }} Permissions</h6>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input select-all" type="checkbox" data-group="{{ $group->id }}">
                                        <label>Allow All</label>
                                    </div>
                                </div>

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
                                                @php
                                                    $permissions = $category->rolePermissions->first();
                                                @endphp
                                                <tr>
                                                    <td>{{ $category->name }}</td>

                                                    {{-- View --}}
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                                data-role="{{ $roleId }}"
                                                                data-category="{{ $category->id }}"
                                                                data-type="view"
                                                                {{ $permissions && $permissions->view ? 'checked' : '' }}>
                                                        </div>
                                                    </td>

                                                    {{-- Add --}}
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                                data-role="{{ $roleId }}"
                                                                data-category="{{ $category->id }}"
                                                                data-type="add"
                                                                {{ $permissions && $permissions->add ? 'checked' : '' }}>
                                                        </div>
                                                    </td>

                                                    {{-- Edit --}}
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                                data-role="{{ $roleId }}"
                                                                data-category="{{ $category->id }}"
                                                                data-type="edit"
                                                                {{ $permissions && $permissions->edit ? 'checked' : '' }}>
                                                        </div>
                                                    </td>

                                                    {{-- Delete --}}
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                                data-role="{{ $roleId }}"
                                                                data-category="{{ $category->id }}"
                                                                data-type="delete"
                                                                {{ $permissions && $permissions->delete ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fa fa-save me-1"></i> Save
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    </div>
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

@endsection