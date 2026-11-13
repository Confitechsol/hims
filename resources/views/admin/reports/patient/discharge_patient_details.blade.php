@extends('layouts.adminLayout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #750096">
                            <i class="fas fa-cogs me-2"></i> Discharge Patient Details
                        </h5>
                        <a href="{{ route('patient-reports-index') }}" class="text-white fw-bold">
                            <i class="fa-solid fa-angles-left text-white"></i> Patient
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('patient.discharge_patient_details') }}" class="row align-items-end g-3">
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
                            <a href="{{ route('patient.discharge_patient_details') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <div id="report-error" class="alert alert-danger d-none" role="alert"></div>
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <button type="button" id="export-discharge-excel" class="btn btn-success btn-sm" onclick="exportDischargeReportToExcel()" disabled>
                            Export to Excel
                        </button>
                        <button type="button" id="export-discharge-pdf" class="btn btn-danger btn-sm" onclick="exportDischargeReportToPdf()" disabled>
                            Export to PDF
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Discharge ID</th>
                                    <th>Patient Name</th>
                                    <th>IPD Reference</th>
                                    <th>Doctor</th>
                                    <th>Payment Type</th>
                                    <th>Admission Date</th>
                                    <th>Discharge Date</th>
                                    <th>Discharge Time</th>
                                    <th>Bed</th>
                                    <th>Discharge Type</th>
                                    <th>OT Done By</th>
                                    <th>Total Bill</th>
                                    <th>Discount</th>
                                    <th>Doctor Visit</th>
                                </tr>
                            </thead>
                            <tbody id="discharge-report-body">
                                <tr>
                                    <td colspan="14" class="text-center">Loading discharge details...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="report-total" class="text-muted"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dischargeReportHeaders = [
            'Discharge ID',
            'Patient Name',
            'IPD Reference',
            'Doctor',
            'Payment Type',
            'Admission Date',
            'Discharge Date',
            'Discharge Time',
            'Bed',
            'Discharge Type',
            'OT Done By',
            'Total Bill',
            'Discount',
            'Doctor Visit'
        ];
        let dischargeReportRows = [];

        function exportDischargeReportToExcel() {
            if (!dischargeReportRows.length) {
                alert('There are no discharge details to export.');
                return;
            }

            if (!window.XLSX) {
                alert('Excel export is unavailable. Please reload the page and try again.');
                return;
            }

            const worksheet = XLSX.utils.aoa_to_sheet([dischargeReportHeaders, ...dischargeReportRows]);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Discharge Details');
            XLSX.writeFile(workbook, 'Discharge_Patient_Details.xlsx');
        }

        function exportDischargeReportToPdf() {
            if (!dischargeReportRows.length) {
                alert('There are no discharge details to export.');
                return;
            }

            if (!window.jspdf || !window.jspdf.jsPDF) {
                alert('PDF export is unavailable. Please reload the page and try again.');
                return;
            }

            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ orientation: 'landscape', unit: 'pt', format: 'a4' });
            pdf.autoTable({
                head: [dischargeReportHeaders],
                body: dischargeReportRows,
                theme: 'grid',
                headStyles: { fillColor: [117, 0, 150] },
                styles: { fontSize: 7, cellPadding: 3, overflow: 'linebreak' },
                margin: { left: 20, right: 20 }
            });
            pdf.save('Discharge_Patient_Details.pdf');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById('discharge-report-body');
            const errorElement = document.getElementById('report-error');
            const totalElement = document.getElementById('report-total');
            const exportButtons = [
                document.getElementById('export-discharge-excel'),
                document.getElementById('export-discharge-pdf')
            ];
            const params = new URLSearchParams();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (startDate) params.set('start_date', startDate);
            if (endDate) params.set('end_date', endDate);

            fetch(`{{ route('ipd.ipd-discharge-patient-reports') }}?${params.toString()}`, {
                headers: { Accept: 'application/json' }
            })
                .then(async function (response) {
                    const result = await response.json();
                    if (!response.ok || result.status !== true) {
                        const validationErrors = result.errors
                            ? Object.values(result.errors).flat().join(' ')
                            : '';
                        throw new Error(validationErrors || result.message || 'Unable to load discharge details.');
                    }
                    return result;
                })
                .then(function (result) {
                    tableBody.replaceChildren();
                    dischargeReportRows = (result.data || []).map(function (item) {
                        const ipd = item.ipd_details || {};
                        const patient = ipd.patient || {};
                        const doctor = ipd.doctor || {};
                        return [
                            item.id,
                            patient.patient_name,
                            ipd.refference,
                            doctor.name,
                            ipd.is_cashless,
                            item.admission_date,
                            item.discharge_date,
                            item.discharge_time,
                            item.bed,
                            item.ot_name,
                            item.ot_done_by,
                            item.total_bill_amount,
                            item.total_discount,
                            item.doctor_visit_amount
                        ].map(function (value) {
                            return value === null || value === undefined || value === '' ? '-' : value;
                        });
                    });
                    exportButtons.forEach(function (button) {
                        button.disabled = dischargeReportRows.length === 0;
                    });

                    if (dischargeReportRows.length === 0) {
                        const row = tableBody.insertRow();
                        const cell = row.insertCell();
                        cell.colSpan = 14;
                        cell.className = 'text-center';
                        cell.textContent = 'No discharged patients found.';
                    } else {
                        dischargeReportRows.forEach(function (values) {
                            const row = tableBody.insertRow();
                            values.forEach(function (value) {
                                const cell = row.insertCell();
                                cell.textContent = value;
                            });
                        });
                    }

                    totalElement.textContent = `Total records: ${result.total}`;
                })
                .catch(function (error) {
                    tableBody.replaceChildren();
                    const row = tableBody.insertRow();
                    const cell = row.insertCell();
                    cell.colSpan = 14;
                    cell.className = 'text-center';
                    cell.textContent = 'Discharge details could not be loaded.';
                    errorElement.textContent = error.message;
                    errorElement.classList.remove('d-none');
                });
        });
    </script>
@endsection
