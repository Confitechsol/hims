{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>
        .module_billing {
            border-radius: 8px;
            color: #fff;
            background-color: #CB6CE7;
            width: 100%;
            padding: 15px;
            box-shadow: 5px 5px 8px 0px #bbbbbb;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>

    <div class="row justify-content-center">

        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Backend Bill Report </h5>
                        <a href="reports/patient-reports-index" class="text-white fw-bold"><i
                                class="fa-solid fa-angles-left text-white"></i>
                            backend</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row align-items-center">

                            {{-- Date From --}}
                            <div class="col-md-3">
                                <label class="form-label">
                                    Date From <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="from_date" class="form-control">
                            </div>

                            {{-- Date To --}}
                            <div class="col-md-3">
                                <label class="form-label">
                                    Date To <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="to_date" class="form-control">
                            </div>

                            {{-- Export --}}
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success" onclick="exportBackendBillToExcel()">
                                    Export to Excel
                                </button>
                                <button type="button" class="btn btn-danger" onclick="exportBackendBillToPDF()">Export to
                                    PDF</button>
                            </div>

                            {{-- Buttons --}}
                            <!-- <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Search
                                </button>

                                <a href="{{ route('ipd.ipd_reports') }}" class="btn btn-secondary btn-sm">
                                    Reset
                                </a>
                            </div> -->

                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-11">
            <div class="row pt-0">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- Table start -->
                                            <div class="table-responsive table-nowrap">
                                                <table class="table border" id="ipd-reports-table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Case No</th>
                                                            <th>Bill No</th>
                                                            <th>Patient Name</th>
                                                            <th>Age</th>
                                                            <th>Gender</th>
                                                            <th>Patient Type</th>
                                                            {{-- <th>Address</th> --}}
                                                            <th>Doctor Name </th>
                                                            <th>Bill Amount</th>
                                                            <th>Received Amount</th>
                                                            <th>Due Amount</th>
                                                            <th>Particulars</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($bills as $bill)
                                                            <tr>
                                                               <td>{{ $bill['case_no'] ?? '' }}</td>
                                                                <td>
                                                                    @forelse($bill['receipts'] ?? [] as $receipt)
                                                                        {{ $receipt['receipt_no'] ?? '-' }}<br>
                                                                    @empty
                                                                        -
                                                                    @endforelse
                                                                </td>
                                                                <td>{{ $bill['patient_name'] ?? '' }}</td>
                                                                <td>{{ $bill['age'] ?? '' }}</td>
                                                                <td>{{ $bill['gender'] ?? '' }}</td>
                                                                <td>{{ $bill['patienttype'] ?? '' }}</td>
                                                                {{-- <td>{{ $bill['address'] ?? '' }}</td> --}}
                                                                <td>{{ $bill['doctor_name'] ?? '' }}</td>
                                                                 
                                                                {{-- Bill Amount --}}
                                                                <td>
                                                                    ₹{{ number_format((float) ($bill['total_amount'] ?? 0), 2) }}
                                                                </td>
                                                                
                                                                {{-- Received Amount --}}
                                                                <td>
                                                                    @php
                                                                        $totalReceived = collect($bill['receipts'] ?? [])
                                                                            ->sum(fn($receipt) => (float) ($receipt['received_amount'] ?? 0));
                                                                    @endphp
                                                                
                                                                    @forelse($bill['receipts'] ?? [] as $receipt)
                                                                        ₹{{ number_format((float) ($receipt['received_amount'] ?? 0), 2) }}<br>
                                                                    @empty
                                                                        ₹0.00
                                                                    @endforelse
                                                                
                                                                    <hr class="my-1">
                                                                    <strong>Total: ₹{{ number_format($totalReceived, 2) }}</strong>
                                                                </td>
                                                                
                                                                {{-- Due Amount --}}
                                                                <td>
                                                                    @php
                                                                        $billAmount = (float) ($bill['total_amount'] ?? 0);
                                                                        $dueAmount = $billAmount - $totalReceived;
                                                                    @endphp
                                                                
                                                                    <strong>
                                                                        ₹{{ number_format(max(0, $dueAmount), 2) }}
                                                                    </strong>
                                                                </td>

                                                                {{-- Particulars --}}
                                                                <td>
                                                                    @forelse($bill['bill_items'] ?? [] as $item)
                                                                        {{ $item['bill_item'] ?? '-' }}
                                                                        (₹{{ number_format((float) ($item['amount'] ?? 0), 2) }})
                                                                        <br>
                                                                    @empty
                                                                        -
                                                                    @endforelse
                                                                </td>


                                                                <td data-date="{{ substr((string) ($bill['date'] ?? ''), 0, 10) }}">{{ $bill['date'] ?? '' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            {{-- Pagination Links --}}

                                            <!-- Table end -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>







    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');
            const reportTable = document.getElementById('ipd-reports-table');

            function filterReportRows() {
                const fromDate = fromDateInput.value;
                const toDate = toDateInput.value;

                Array.from(reportTable.tBodies[0].rows).forEach(row => {
                    const billDate = row.cells[row.cells.length - 1].dataset.date || '';
                    const matchesFromDate = !fromDate || (billDate && billDate >= fromDate);
                    const matchesToDate = !toDate || (billDate && billDate <= toDate);

                    row.style.display = matchesFromDate && matchesToDate ? '' : 'none';
                });
            }

            fromDateInput.addEventListener('change', filterReportRows);
            toDateInput.addEventListener('change', filterReportRows);
        });

        function getReportCellText(cell) {
            const copy = cell.cloneNode(true);
            copy.querySelectorAll('br, hr').forEach(separator => {
                separator.replaceWith(document.createTextNode('\n'));
            });

            return copy.textContent
                .split(/\r?\n/)
                .map(line => line.trim())
                .filter(Boolean)
                .join('\n');
        }

        function loadStyledXlsx() {
            if (window.XLSX && window.XLSX.__supportsCellStyles) {
                return Promise.resolve(window.XLSX);
            }

            if (!window.styledXlsxPromise) {
                window.styledXlsxPromise = new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js';
                    script.onload = () => {
                        if (!window.XLSX || !window.XLSX.utils) {
                            reject(new Error('Excel export library loaded incorrectly.'));
                            return;
                        }

                        window.XLSX.__supportsCellStyles = true;
                        resolve(window.XLSX);
                    };
                    script.onerror = () => reject(new Error('Could not load Excel formatting support.'));
                    document.head.appendChild(script);
                });
            }

            return window.styledXlsxPromise;
        }

        async function exportBackendBillToExcel() {
            const table = document.getElementById('ipd-reports-table');
            try {
                const XLSX = await loadStyledXlsx();
                const headers = Array.from(table.tHead.rows[0].cells).map(getReportCellText);
                const rows = Array.from(table.tBodies[0].rows)
                    .filter(row => row.style.display !== 'none')
                    .map(row => Array.from(row.cells).map(getReportCellText));
                const worksheet = XLSX.utils.aoa_to_sheet([headers, ...rows]);
                const workbook = XLSX.utils.book_new();
                const columnWidths = [
                    14, 32, 22, 8, 12, 18, 22, 16, 22, 16, 34, 14
                ];

                Object.keys(worksheet)
                    .filter(address => !address.startsWith('!'))
                    .forEach(address => {
                        worksheet[address].s = {
                            alignment: { vertical: 'top', wrapText: true }
                        };
                    });
                worksheet['!cols'] = columnWidths.map(wch => ({ wch }));
                worksheet['!rows'] = [headers, ...rows].map((row, rowIndex) => {
                    const lineCount = Math.max(
                        1,
                        ...row.map((value, columnIndex) => {
                            const explicitLines = value.split('\n').length;
                            const wrappedLines = value.split('\n').reduce((count, line) => {
                                return count + Math.max(1, Math.ceil(line.length / columnWidths[columnIndex]));
                            }, 0);

                            return Math.max(explicitLines, wrappedLines);
                        })
                    );

                    return { hpt: Math.max(rowIndex === 0 ? 22 : 20, lineCount * 15) };
                });

                XLSX.utils.book_append_sheet(workbook, worksheet, 'Backend Bills');
                XLSX.writeFile(workbook, 'Backend_Bill_Report.xlsx');
            } catch (error) {
                console.error(error);
                alert(`Excel export failed: ${error.message}`);
            }
        }

        function exportBackendBillToPDF() {
            const { jsPDF } = window.jspdf;
            const table = document.getElementById('ipd-reports-table');
            const headers = Array.from(table.tHead.rows[0].cells).map(getReportCellText);
            const rows = Array.from(table.tBodies[0].rows)
                .filter(row => row.style.display !== 'none')
                .map(row => Array.from(row.cells).map(getReportCellText));
            const doc = new jsPDF({ orientation: 'landscape', unit: 'pt', format: 'a4' });

            doc.autoTable({
                head: [headers],
                body: rows,
                theme: 'grid',
                headStyles: { fillColor: [22, 160, 133] },
                styles: { fontSize: 7, cellPadding: 3, overflow: 'linebreak', valign: 'top' },
                columnStyles: {
                    0: { cellWidth: 45 },
                    1: { cellWidth: 95 },
                    2: { cellWidth: 90 },
                    3: { cellWidth: 25 },
                    4: { cellWidth: 35 },
                    5: { cellWidth: 60 },
                    6: { cellWidth: 70 },
                    7: { cellWidth: 55 },
                    8: { cellWidth: 75 },
                    9: { cellWidth: 45 },
                    10: { cellWidth: 100 },
                    11: { cellWidth: 55 }
                },
                margin: { left: 20, right: 20 }
            });
            doc.save('Backend_Bill_Report.pdf');
        }
    </script>
@endsection