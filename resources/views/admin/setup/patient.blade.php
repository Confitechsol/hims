{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message (Single Error) --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>There were some problems with your input:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Patient List</h5>
                </div>

                <div class="card-body">


                    {{-- Hospital Name & Code --}}
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                        <form method="GET" action=""
                                            class="input-icon-start position-relative me-2 d-flex align-items-center">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" id="search-input"" name="search" class="form-control shadow-sm"
                                                placeholder="Search" value="{{ request('search') }}"
                                                style="max-width: 300px;">
                                            <button type="submit" class="btn btn-primary ms-2">Search</button>
                                        </form>
                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_patient"><i
                                                        class="ti ti-plus me-1"></i>Add New
                                                    Patient</a>
                                            </div>
                                            <!-- Modal -->

                                            @include('components.modals.add-patients-modal')


                                            <div class="text-end d-flex">
                                                <a href="{{ route('patient-import') }}"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-download me-1"></i>Import Patient</a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-menu me-1"></i>Disable Patient List</a>
                                            </div>
                                        </div>

                                    </div>
                                    <form action="{{ route('patients.bulkDelete') }}" method="POST" id="bulk-delete-form">
                                        @csrf
                                        @method('DELETE') <!-- Laravel RESTful delete -->
                                        <div class="text-end mb-2">
                                            <button type="submit" class="btn btn-danger text-white ms-2 fs-13 btn-md"
                                                onclick="return confirm('Are you sure you want to delete the selected patients?')">
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
                                            <table class="table mb-0" id="patient-table">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" name="checkbox" id="select_all">
                                                            #</th>
                                                        <th>Patient Name</th>
                                                        <th>Age</th>
                                                        <th>Gender</th>
                                                        <th>Phone</th>
                                                        <th>Guardian Name</th>
                                                        <th>Address</th>
                                                        <th>Dead</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($patients as $patient)
                                                        <tr>
                                                            <td><input type="checkbox" name="selected_patients[]"
                                                                    value="{{ $patient->id }}" class="select_item"></td>
                                                            <td>{{ $patient->patient_name }}</td>
                                                            <td>{{ $patient->age }}</td>
                                                            <td>{{ $patient->gender }}</td>
                                                            <td>{{ $patient->mobileno }}</td>
                                                            <td>{{ $patient->guardian_name }}</td>
                                                            <td>{{ $patient->address }}</td>
                                                            <td>{{ $patient->is_dead == 'yes' ? 'Yes' : 'No' }}</td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="{{ route('patient.edit', $patient->id) }}"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                        <i class="ti ti-pencil"></i>
                                                                    </a>

                                                                    <!-- @include('components.modals.edit-patient-modal') -->

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <!-- <tr>
                                                                <th scope="row">
                                                                    <input type="checkbox" name="checkbox" id="checkbox">
                                                                </th>
                                                                <td>
                                                                    <h6 class="mb-0 fs-14 fw-semibold"> Bimal</h6>
                                                                </td>

                                                                <td>15 Year 4 Month 8 Days</td>
                                                                <td>Male</td>
                                                                <td>7044094367</td>
                                                                <td>Das </td>
                                                                <td>xXzXzXzX</td>
                                                                <td>No</td>

                                                                <td>
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                        <i class="ti ti-dots-vertical" data-bs-toggle="tooltip"
                                                                            title="Assign Permission"></i></a>
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                        <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                            title="Assign Permission"></i></a>
                                                                </td>
                                                            </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- Pagination Links --}}

                                        <div class="mt-3" id="pagination-wrapper">
                                            @php
                                                $currentPage = $patients->currentPage();
                                                $lastPage = $patients->lastPage();
                                                $window = 2; // how many pages to show on each side
                                                $start = max(1, $currentPage - $window);
                                                $end = min($lastPage, $currentPage + $window);
                                            @endphp

                                            @if ($patients->onFirstPage())
                                                <button class="btn btn-outline-secondary btn-sm me-1" disabled>«
                                                    Prev</button>
                                            @else
                                                <a href="{{ $patients->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                    class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                            @endif

                                            @if ($start > 1)
                                                <a href="{{ $patients->url(1) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                    class="btn btn-outline-secondary btn-sm me-1">1</a>
                                                @if ($start > 2)
                                                    <span class="btn btn-outline-secondary btn-sm me-1 disabled">...</span>
                                                @endif
                                            @endif

                                            @for ($page = $start; $page <= $end; $page++)
                                                @if ($page == $currentPage)
                                                    <button
                                                        class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                                @else
                                                    <a href="{{ $patients->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                        class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                                @endif
                                            @endfor

                                            @if ($end < $lastPage)
                                                @if ($end < $lastPage - 1)
                                                    <span class="btn btn-outline-secondary btn-sm me-1 disabled">...</span>
                                                @endif
                                                <a href="{{ $patients->url($lastPage) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                    class="btn btn-outline-secondary btn-sm me-1">{{ $lastPage }}</a>
                                            @endif

                                            @if ($patients->hasMorePages())
                                                <a href="{{ $patients->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                    class="btn btn-outline-secondary btn-sm">Next »</a>
                                            @else
                                                <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                            @endif
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
                let myModal = new bootstrap.Modal(document.getElementById('add_patient'));
                myModal.show();
            });
        </script>
    @endif
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.select_item');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

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
            apiUrl: "{{ route('patients') }}",
            tableSelector: "#patient-table",
            paginationSelector: "#pagination-wrapper",
            searchInputSelector: "#search-input",
            perPageSelector: "#perPage",
            rowRenderer: function(item) {
                const row = document.createElement("tr");
                row.innerHTML = `
            <td><input type="checkbox" name="selected_Doctors[]"
                value="${item.id}" class="select_item"></td>
                <td>${item.patient_name}</td>
                <td>${item.age}</td>
                <td>${item.gender ?? 'N/A'}</td>
                <td>${item.mobileno}</td>
                <td>${item.guardian_name}</td>
                <td>${item.address ?? ''}</td>
                <td>${item.is_dead == 'yes' ? 'Yes' : 'No'}</td>
                <td>
                    <div class="d-flex">
                        <a href="${('{{ route('patient.edit', ':id') }}').replace(':id', item.id)}" 
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
