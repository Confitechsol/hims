{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Doctor List</h5>
                </div>

                <div class="card-body">


                    {{-- Hospital Name & Code --}}
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
{{-- 
                                        <form method="GET" action="" class="input-icon-start position-relative me-2 d-flex align-items-center">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" id="search-input" name="search" class="form-control shadow-sm" placeholder="Search" value="{{ request('search') }}" style="max-width: 300px;">
                                            <button type="submit" class="btn btn-primary ms-2">Search</button>
                                        </form> --}}
                                        <div class="text-end d-flex">
                                            <a href="{{ route('createDoctor') }}"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                ><i
                                                    class="ti ti-plus me-1"></i>Add New
                                                Doctor</a>
                                        </div>
                                        <div class="page_btn d-flex">

                                            <!-- Modal -->

                                           


                                            <div class="text-end d-flex">
                                                <a href="{{ route('doctor-import') }}"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-download me-1"></i>Import Doctor</a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-menu me-1"></i>Disable Doctor List</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="mb-3">
                                        <input type="text" id="search-input" class="form-control" placeholder="Search for doctors..."
                                        >
                                    </div>
                                    <form action="{{ route('doctors.bulkDelete') }}" method="POST" id="bulk-delete-form">
                                        @csrf
                                        @method('DELETE') <!-- Laravel RESTful delete -->
                                        <div class="text-end mb-2">
                                            <button type="submit" class="btn btn-danger text-white ms-2 fs-13 btn-md"
                                                onclick="return confirm('Are you sure you want to delete the selected Doctors?')">
                                                <i class="ti ti-trash me-1"></i>Delete Selected
                                            </button>
                                        </div>
                                        @if (session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        @if (session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif

                                        <div class="table-responsive">
                                            <table class="table mb-0" id="doctor-table">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" name="checkbox" id="select_all">
                                                            #</th>
                                                        <th>Doctor Name</th>
                                                        <th>Doctor Id</th>
                                                        <th>Gender</th>
                                                        <th>Phone</th>
                                                        <th>Department</th>
                                                        <th>Is Active</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="doctor-table-body">
                                                    @foreach ($doctors as $doctor)
                                                        <tr>
                                                            <td><input type="checkbox" name="selected_Doctors[]"
                                                                    value="{{ $doctor->id }}" class="select_item"></td>
                                                            <td class="doctor-name">{{ $doctor->name }} {{ $doctor->surname }}</td>
                                                            <td class="doctor-id">{{ $doctor->doctor_id }}</td>
                                                            <td class="doctor-gender">{{ $doctor->gender }}</td>
                                                            <td class="doctor-phone">{{ $doctor->contact_no }}</td>
                                                            <td class="doctor-department">{{ $doctor->department->department_name ?? 'No Department' }}</td>
                                                            <td class="doctor-active">{{ $doctor->is_active == '1' ? 'Yes' : 'No' }}</td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="{{ route('doctor.edit', $doctor->id) }}" 
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                        >
                                                                        <i class="ti ti-pencil"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                         {{-- Pagination Links --}}
                                         
                                    <div class="mt-3" id="pagination-wrapper">
                                        @php
                                            $currentPage = $doctors->currentPage();
                                            $lastPage = $doctors->lastPage();
                                            $window = 2; // how many pages to show on each side
                                            $start = max(1, $currentPage - $window);
                                            $end = min($lastPage, $currentPage + $window);
                                        @endphp

                                        @if ($doctors->onFirstPage())
                                            <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                        @else
                                            <a href="{{ $doctors->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                        @endif

                                        @if ($start > 1)
                                            <a href="{{ $doctors->url(1) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">1</a>
                                            @if ($start > 2)
                                                <span class="btn btn-outline-secondary btn-sm me-1 disabled">...</span>
                                            @endif
                                        @endif

                                        @for ($page = $start; $page <= $end; $page++)
                                            @if ($page == $currentPage)
                                                <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                            @else
                                                <a href="{{ $doctors->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                            @endif
                                        @endfor

                                        @if ($end < $lastPage)
                                            @if ($end < $lastPage - 1)
                                                <span class="btn btn-outline-secondary btn-sm me-1 disabled">...</span>
                                            @endif
                                            <a href="{{ $doctors->url($lastPage) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">{{ $lastPage }}</a>
                                        @endif

                                        @if ($doctors->hasMorePages())
                                            <a href="{{ $doctors->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm">Next »</a>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                                    </form>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let myModal = new bootstrap.Modal(document.getElementById('add_Doctor'));
                myModal.show();
            });
        </script>
    @endif
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.select_item');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
@section('script')
<script>
    function createAjaxTable({
        apiUrl,
        tableSelector,
        paginationSelector,
        searchInputSelector,
        perPageSelector,
        rowRenderer
    }) {
        let debounceTimer;
        const searchInput = document.querySelector(searchInputSelector);
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    callApi(1);
                }, 500);
            });
        }
    
        // Public call function (can be used by pagination too)
        function callApi(page = 1) {
            const searchTerm = searchInput?.value || '';
            const perPage = document.querySelector(perPageSelector)?.value || 10;
    
            const url = new URL(apiUrl, window.location.origin);
            url.searchParams.set("search", searchTerm);
            url.searchParams.set("page", page);
            url.searchParams.set("perPage", perPage);
    
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    updateTable(data.result.data);        
                    updatePagination(data.result);
                })
                .catch(error => {
                    console.error("Error fetching table data:", error);
                    alert("Error fetching data. Please try again.");
                });
        }
    
        function updateTable(items) {
            const tableBody = document.querySelector(`${tableSelector} tbody`);
            if (!tableBody) return;
            tableBody.innerHTML = "";
    
            items.forEach(item => {
                const row = rowRenderer(item);
                tableBody.appendChild(row);
            });
        }
    
        function updatePagination(pagination) {
    const wrapper = document.querySelector(paginationSelector);
    if (!wrapper) return;

    wrapper.innerHTML = "";

    const currentPage = pagination.current_page;
    const lastPage = pagination.last_page;
    const windowSize = 2;

    const start = Math.max(1, currentPage - windowSize);
    const end = Math.min(lastPage, currentPage + windowSize);

    // Prev Button
    wrapper.appendChild(
        createButton("« Prev", currentPage > 1, () => callApi(currentPage - 1))
    );

    // First page + dots
    if (start > 1) {
        wrapper.appendChild(createButton(1, true, () => callApi(1)));

        if (start > 2) {
            wrapper.appendChild(createDots());
        }
    }

    // Middle pages
    for (let page = start; page <= end; page++) {
        wrapper.appendChild(
            createButton(page, true, () => callApi(page), page === currentPage)
        );
    }

    // Last page + dots
    if (end < lastPage) {
        if (end < lastPage - 1) {
            wrapper.appendChild(createDots());
        }

        wrapper.appendChild(
            createButton(lastPage, true, () => callApi(lastPage))
        );
    }

    // Next Button
    wrapper.appendChild(
        createButton("Next »", currentPage < lastPage, () => callApi(currentPage + 1))
    );
}
function createDots() {
    const span = document.createElement("span");
    span.textContent = "...";
    span.className = "btn btn-outline-secondary btn-sm me-1 disabled";
    return span;
}
        function createButton(label, enabled, onClick, isActive = false) {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.textContent = label;
            btn.className = `btn btn-sm me-1 ${isActive ? 'btn-primary' : 'btn-outline-secondary'}`;
            btn.disabled = !enabled;
            if (enabled) {
            btn.addEventListener("click", onClick);
            }
            return btn;
        }
    
        // Expose callApi if needed externally
        return {
            refresh: callApi
        };
    }
    createAjaxTable({
        apiUrl: "{{ route('doctors.index') }}",
        tableSelector: "#doctor-table",
        paginationSelector: "#pagination-wrapper",
        searchInputSelector: "#search-input",
        perPageSelector: "#perPage",
        rowRenderer: function (item) {
            const row = document.createElement("tr");
            row.innerHTML = `
            <td><input type="checkbox" name="selected_Doctors[]"
                value="${item.id}" class="select_item"></td>
                <td class="doctor-name">${item.name} ${item.surname}</td>
                <td class="doctor-id">${item.doctor_id ?? 'N/A'}</td>
                <td class="doctor-gender">${item.gender ?? 'N/A'}</td>
                <td class="doctor-phone">${item.contact_no}</td>
                <td class="doctor-department">${item.department?.department_name ?? 'No Department'}</td>
                <td class="doctor-active">${item.is_active  ? 'Yes' : 'No' }</td>
                <td>
                    <div class="d-flex">
                        <a href="${('{{ route('doctor.edit', ':id') }}').replace(':id', item.id)}" 
                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                            >
                            <i class="ti ti-pencil"></i>
                        </a>
                    </div>
                </td>
            `;
            return row;
        }
    });
</script>    
@endsection    
@endsection
