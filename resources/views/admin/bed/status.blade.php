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
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Bed Status</h5>
                </div>

                <div class="card-body">
                    <!-- Action Buttons -->
                    <div class="mb-3">
                        <button class="btn btn-primary" id="copy-btn" data-clipboard-target="#bed-table">Copy</button>
                        <button class="btn btn-success" onclick="exportToExcel()">Export to Excel</button>
                        <button class="btn btn-info" onclick="exportToCSV()">Export to CSV</button>
                        <button class="btn btn-danger" onclick="exportToPDF()">Export to PDF</button>
                        <button class="btn btn-warning" onclick="printTable()">Print</button>
                    </div>
                    <!-- Search Input Box -->
                    <div class="mb-3">
                        <input type="text" id="search-input" class="form-control" placeholder="Search for beds..."
                            onkeyup="searchTable()">
                    </div>
                    <!--  Start Table -->
                    <div class="table-responsive" id="bed-table-wrapper">
                        <table class="table datatable table-nowrap" id="bed-table">
                            <thead class="">
                                <tr>
                                    <th><button class="sort" data-sort="name">Name <span class="arrow"></span></button></th>
                                    <th><button class="sort" data-sort="type">Bed Type <span class="arrow"></span></button></th>
                                    <th><button class="sort" data-sort="group">Bed Group <span class="arrow"></span></button></th>
                                    <th><button class="sort" data-sort="floor">Floor <span class="arrow"></span></button></th>
                                    <th><button class="sort" data-sort="status">Status <span class="arrow"></span></button></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($beds as $bed)
                                    <tr>
                                        <td class="name">{{ $bed->name }}</td>
                                        <td class="type">{{ $bed->bedType->name ?? 'N/A' }}</td>
                                        <td class="group">{{ $bed->bedGroup->name ?? 'N/A' }}</td>
                                        <td class="floor">{{ $bed->bedGroup->floor ?? 'N/A' }}</td>
                                        <td class="status">{{ $bed->is_active == "yes" ? 'Available' : 'Alloted' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    const options = {
        valueNames: ['name', 'type', 'group', 'floor', 'status']
    };

    const bedTable = new List('bed-table-wrapper', options);
</script>

<script>
    function searchTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search-input");
        filter = input.value.toLowerCase();
        table = document.getElementById("bed-table");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows and hide those that don't match the search query
        for (i = 1; i < tr.length; i++) { // Skip the header row
            td = tr[i].getElementsByTagName("td");
            let rowContainsSearchText = false;
            // Loop through all columns of the row to check for a match
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        rowContainsSearchText = true;
                    }
                }
            }

            if (rowContainsSearchText) {
                tr[i].style.display = ""; // Show the row
            } else {
                tr[i].style.display = "none"; // Hide the row
            }
        }
    }
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
@endsection