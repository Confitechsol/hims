@extends('layouts.adminLayout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #750096">
                            <i class="fas fa-cogs me-2"></i> Corporate Due
                        </h5>
                        <a href="{{ route('hospital-reports-index') }}" class="text-white fw-bold">
                            <i class="fa-solid fa-angles-left text-white"></i> Hospital
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('hospital.corporate_due_report') }}" class="row align-items-end g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Date From</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Date To</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            <a href="{{ route('hospital.corporate_due_report') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <div id="corporate-due-error" class="alert alert-danger d-none" role="alert"></div>
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <button type="button" id="export-corporate-due-excel" class="btn btn-success btn-sm"
                            onclick="exportCorporateDueToExcel()" disabled>
                            Export to Excel
                        </button>
                        <button type="button" id="export-corporate-due-pdf" class="btn btn-danger btn-sm"
                            onclick="exportCorporateDueToPdf()" disabled>
                            Export to PDF
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Discharge ID</th>
                                    <th>IPD No</th>
                                    <th>Patient Name</th>
                                    <th>TPA Name</th>
                                    <th>Discharge Date</th>
                                    <th>Final Approval Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="corporate-due-body">
                                <tr>
                                    <td colspan="9" class="text-center">Loading corporate due data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="corporate-due-total" class="text-muted"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const corporateDueHeaders = [
            'Discharge ID',
            'IPD No',
            'Patient Name',
            'TPA Name',
            'Discharge Date',
            'Final Approval Amount',
            'Paid Amount',
            'Due Amount',
            'Status'
        ];
        let corporateDueRows = [];

        function exportCorporateDueToExcel() {
            if (!corporateDueRows.length) {
                alert('There are no corporate due records to export.');
                return;
            }

            if (!window.XLSX) {
                alert('Excel export is unavailable. Please reload the page and try again.');
                return;
            }

            const worksheet = XLSX.utils.aoa_to_sheet([corporateDueHeaders, ...corporateDueRows]);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Corporate Due');
            XLSX.writeFile(workbook, 'Corporate_Due_Report.xlsx');
        }

        function exportCorporateDueToPdf() {
            if (!corporateDueRows.length) {
                alert('There are no corporate due records to export.');
                return;
            }

            if (!window.jspdf || !window.jspdf.jsPDF) {
                alert('PDF export is unavailable. Please reload the page and try again.');
                return;
            }

            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ orientation: 'landscape', unit: 'pt', format: 'a4' });
            if (typeof pdf.autoTable !== 'function') {
                alert('PDF export is unavailable. Please reload the page and try again.');
                return;
            }

            pdf.autoTable({
                head: [corporateDueHeaders],
                body: corporateDueRows,
                theme: 'grid',
                headStyles: { fillColor: [117, 0, 150] },
                styles: { fontSize: 8, cellPadding: 4, overflow: 'linebreak' },
                margin: { left: 20, right: 20 }
            });
            pdf.save('Corporate_Due_Report.pdf');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById('corporate-due-body');
            const errorElement = document.getElementById('corporate-due-error');
            const totalElement = document.getElementById('corporate-due-total');
            const exportButtons = [
                document.getElementById('export-corporate-due-excel'),
                document.getElementById('export-corporate-due-pdf')
            ];
            const params = new URLSearchParams();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (startDate) params.set('start_date', startDate);
            if (endDate) params.set('end_date', endDate);

            function formatAmount(value) {
                if (value === null || value === undefined || value === '') {
                    return '-';
                }
                const amount = Number(value);
                return Number.isFinite(amount)
                    ? amount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    : '-';
            }

            fetch(`{{ route('corparate-due-report') }}?${params.toString()}`, {
                headers: { Accept: 'application/json' }
            })
                .then(async function (response) {
                    const result = await response.json();
                    if (!response.ok || result.status !== true) {
                        const validationErrors = result.errors
                            ? Object.values(result.errors).flat().join(' ')
                            : '';
                        throw new Error(validationErrors || result.message || 'Unable to load corporate due data.');
                    }
                    return result;
                })
                .then(function (result) {
                    tableBody.replaceChildren();

                    const rows = result.data || [];
                    corporateDueRows = rows.map(function (item) {
                        return [
                            item.discharge_id,
                            item.ipd_no,
                            item.patient_name,
                            item.tpa_name,
                            item.discharge_date,
                            Number(item.final_approval_amount) || 0,
                            Number(item.paid_amount) || 0,
                            Number(item.due_amount) || 0,
                            item.payment_status
                        ].map(function (value) {
                            return value === null || value === undefined || value === '' ? '-' : value;
                        });
                    });
                    exportButtons.forEach(function (button) {
                        button.disabled = corporateDueRows.length === 0;
                    });

                    if (rows.length === 0) {
                        const row = tableBody.insertRow();
                        const cell = row.insertCell();
                        cell.colSpan = 9;
                        cell.className = 'text-center';
                        cell.textContent = 'No corporate due records found.';
                        totalElement.textContent = 'Total records: 0';
                        return;
                    }

                    let approvalTotal = 0;
                    let paidTotal = 0;
                    let dueTotal = 0;

                    corporateDueRows.forEach(function (values) {
                        const row = tableBody.insertRow();
                        const approval = Number(values[5]) || 0;
                        const paid = Number(values[6]) || 0;
                        const due = Number(values[7]) || 0;
                        approvalTotal += approval;
                        paidTotal += paid;
                        dueTotal += due;

                        values.forEach(function (value, index) {
                            const cell = row.insertCell();
                            cell.textContent = index >= 5 && index <= 7
                                ? formatAmount(value)
                                : value;
                        });
                    });

                    totalElement.textContent = `Total records: ${corporateDueRows.length} | Final approval: ${formatAmount(approvalTotal)} | Paid: ${formatAmount(paidTotal)} | Due: ${formatAmount(dueTotal)}`;
                })
                .catch(function (error) {
                    tableBody.replaceChildren();
                    const row = tableBody.insertRow();
                    const cell = row.insertCell();
                    cell.colSpan = 9;
                    cell.className = 'text-center';
                    cell.textContent = 'Corporate due data could not be loaded.';
                    errorElement.textContent = error.message;
                    errorElement.classList.remove('d-none');
                });
        });
    </script>
@endsection
