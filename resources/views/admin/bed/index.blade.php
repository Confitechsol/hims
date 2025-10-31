@extends('layouts.adminLayout')
@section('content')
<style>
    table th button {
        border: none;
        background-color: transparent;
    }

    button.sort.asc .arrow::after {
        content: "▲";
        margin-left: 5px;
    }

    button.sort.desc .arrow::after {
        content: "▼";
        margin-left: 5px;
    }
</style>
<!-- Start Content -->
<div class="content">
    <div class="row justify-content-center">

        {{-- Settings Form --}}
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Bed List</h5>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add
                            Bed</button>
                        <!-- Action Buttons -->
                        <div class="mb-3">
                            <button class="btn btn-primary" id="copy-btn"
                                data-clipboard-target="#bed-table">Copy</button>
                            <button class="btn btn-success" onclick="exportToExcel()">Export to Excel</button>
                            <button class="btn btn-info" onclick="exportToCSV()">Export to CSV</button>
                            <button class="btn btn-danger" onclick="exportToPDF()">Export to PDF</button>
                            <button class="btn btn-warning" onclick="printTable()">Print</button>
                        </div>
                    </div>
                    <!-- Search Input Box -->
                    <div class="mb-3">
                        <input type="text" id="search-input" class="form-control" placeholder="Search for beds..."
                        >
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!--  Start Table -->
                    <div class="table-responsive" id="bed-table-wrapper">
                        <table class="table datatable table-nowrap" id="bed-table">
                            <thead class="">
                                <tr>
                                    <th><button class="sort" data-sort="name">Name <span class="arrow"></span></button>
                                    </th>
                                    <th><button class="sort" data-sort="type">Bed Type <span
                                                class="arrow"></span></button></th>
                                    <th><button class="sort" data-sort="group">Bed Group <span
                                                class="arrow"></span></button></th>
                                    <th><button class="sort" data-sort="status">Useable<span
                                                class="arrow"></span></button></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($beds as $bed)
                                    <tr>
                                        <td class="name">{{ $bed->name }}</td>
                                        <td class="type">{{ $bed->bedType->name ?? 'N/A' }}</td>
                                        <td class="group">{{ $bed->bedGroup->name ?? 'N/A' }}</td>
                                        <td class="status">
                                            @if ($bed->is_active != "noused")
                                                <i class="fa-solid fa-square-check ms-2"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editModal" data-id="{{ $bed->id }}"
                                                data-name="{{ $bed->name }}" data-bed_type_id="{{ $bed->bed_type_id }}"
                                                data-bed_group_id="{{ $bed->bed_group_id }}"
                                                data-is_available="{{ $bed->is_active }}">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $bed->id }}"
                                                data-name="{{ $bed->name }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination Links --}}
                    <div class="mt-3" id="pagination-wrapper">
                        @php
                            $currentPage = $beds->currentPage();
                            $lastPage = $beds->lastPage();
                        @endphp
                    
                        {{-- Previous --}}
                        @if ($beds->onFirstPage())
                            <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                        @else
                            <a href="{{ $beds->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">
                                « Prev
                            </a>
                        @endif
                    
                        {{-- Page numbers --}}
                        @for ($page = 1; $page <= $lastPage; $page++)
                            @if ($page == $currentPage)
                                <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                            @else
                                <a href="{{ $beds->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor
                    
                        {{-- Next --}}
                        @if ($beds->hasMorePages())
                            <a href="{{ $beds->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm">
                                Next »
                            </a>
                        @else
                            <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                        @endif
                    </div>                                        
                    <!--  End Table -->
                    <div class="mt-3">
                        <strong>Total Beds: <span id="bed-count">{{ count($beds) }}</span></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('beds.store') }}">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Add Bed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Bed Name</label>
                        <input type="text" class="form-control"  name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-bed-type" class="form-label">Bed Type</label>
                        <select class="form-select" name="bed_type_id" required>
                            <option value="">Select Type</option>
                            @foreach($bedTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit-bed-group" class="form-label">Bed Group</label>
                        <select class="form-select" name="bed_group_id" required>
                            <option value="">Select Group</option>
                            @foreach($bedGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active"
                            value="0">
                        <label class="form-check-label" for="edit-is-available">
                            Not available for use
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('beds.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Bed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Bed Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-bed-type" class="form-label">Bed Type</label>
                        <select class="form-select" id="edit-bed-type" name="bed_type_id" required>
                            <option value="">Select Type</option>
                            @foreach($bedTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit-bed-group" class="form-label">Bed Group</label>
                        <select class="form-select" id="edit-bed-group" name="bed_group_id" required>
                            <option value="">Select Group</option>
                            @foreach($bedGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="edit-is-available" name="is_active"
                            value="0">
                        <label class="form-check-label" for="edit-is-available">
                            Not available for use
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('beds.destroy') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" id="delete-id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="delete-name"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- End Content -->
<!-- JavaScript for Search Functionality -->
<!-- Clipboard.js for Copy to clipboard functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

<!-- SheetJS for Excel/CSV export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

<!-- jsPDF for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- jsPDF AutoTable plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/3.0.2/jspdf.umd.min.js"></script> -->
<!-- Load html2canvas (required by jsPDF's html method) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Print.js for printing functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>

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
        const perPage = document.querySelector(perPageSelector)?.value || 5;

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

        const prevBtn = createButton("« Prev", currentPage > 1, () => callApi(currentPage - 1));
        wrapper.appendChild(prevBtn);

        for (let page = 1; page <= lastPage; page++) {
            const btn = createButton(page, true, () => callApi(page), page === currentPage);
            wrapper.appendChild(btn);
        }
        const nextBtn = createButton("Next »", currentPage < lastPage, () => callApi(currentPage + 1));
        wrapper.appendChild(nextBtn);
    }

    function createButton(label, enabled, onClick, isActive = false) {
        const btn = document.createElement("button");
        btn.textContent = label;
        btn.className = `btn btn-sm me-1 ${isActive ? 'btn-primary' : 'btn-outline-secondary'}`;
        btn.disabled = !enabled;
        if (enabled) btn.onclick = onClick;
        return btn;
    }

    // Expose callApi if needed externally
    return {
        refresh: callApi
    };
}
createAjaxTable({
    apiUrl: "{{ route('bed') }}",
    tableSelector: "#bed-table",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (bed) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="name">${bed.name}</td>
            <td class="type">${bed.bed_type?.name ?? 'N/A'}</td>
            <td class="group">${bed.bed_group?.name ?? 'N/A'}</td>
            <td class="status">${bed.is_active !== "noused" ? '<i class="fa-solid fa-square-check ms-2"></i>' : 'N/A'}</td>
            <td>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#editModal"
                    data-id="${bed.id}"
                    data-name="${bed.name}"
                    data-bed_type_id="${bed?.bed_type_id ?? ''}"
                    data-bed_group_id="${bed?.bed_group_id ?? ''}"
                    data-is_available="${bed?.is_active}">
                    Edit
                </button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteModal"
                    data-id="${bed.id}"
                    data-name="${bed.name}">
                    Delete
                </button>
            </td>
        `;
        return row;
    }
});
    // Copy Table to Clipboard
    new ClipboardJS('#copy-btn');

    function exportToExcel() {
        const table = document.getElementById("bed-table");
        const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet 1" });
        XLSX.writeFile(wb, "bed_status.xlsx");
    }

    // Export to CSV
    function exportToCSV() {
        const table = document.getElementById("bed-table");
        // const csv = XLSX.utils.table_to_csv(table);
        // Convert table to workbook
        const wb = XLSX.utils.table_to_book(table);
        // Convert workbook to CSV (first sheet)
        const csv = XLSX.utils.sheet_to_csv(wb.Sheets[wb.SheetNames[0]]);
        const blob = new Blob([csv], { type: 'text/csv' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'bed_status.csv';
        link.click();
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'pt',
            format: 'a4'
        });
        var tableElement = document.getElementById('bed-table');
        doc.autoTable({
            html: '#bed-table',
            startY: 20,
            theme: 'grid',
            headStyles: { fillColor: [22, 160, 133] },
            styles: { fontSize: 10, cellPadding: 4 }
        });

        doc.save("table.pdf");
    }

    function printTable() {
        printJS({ printable: 'bed-table', type: 'html', style: 'th { text-align: left;border-bottom:1px solid #000;cell }' });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit-id').value = button.getAttribute('data-id');
            document.getElementById('edit-name').value = button.getAttribute('data-name');
            document.getElementById('edit-bed-type').value = button.getAttribute('data-bed_type_id');
            document.getElementById('edit-bed-group').value = button.getAttribute('data-bed_group_id');

            const isAvailable = button.getAttribute('data-is_available') == 'noused'; // 0 means not available
            document.getElementById('edit-is-available').checked = isAvailable;
        });
    });
</script>

@endsection