{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
<style>
    .guidelines-box {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transform: scale(0.1) translateY(10px);
        transition: all 0.4s ease;
    }

    .guidelines-box.show {
        max-height: 600px;
        /* big enough to fit content */
        opacity: 1;
        transform: scale(1) translateY(0);
    }

    .table thead tr th {
        white-space: nowrap;
    }

    .table tbody tr td {
        white-space: nowrap;
    }

    .import_form {
        padding: 20px;
        background-color: #e0b3ec38;
        margin-top: 25px;
        border-radius: 8px;
        box-shadow: 1px 1px 6px 3px #ececec
    }
</style>
<div class="row justify-content-center">
    {{-- Settings Form --}}
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-cogs me-2"></i> Import Radiology Test
                </h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <div
                                    class="d-flex align-items-sm-center justify-content-end flex-sm-row flex-column gap-2 mb-3 pb-3">

                                    <div class="page_btn d-flex">

                                        <!-- Guidelines Button -->
                                        <button type="button" class="btn btn-primary" onclick="toggleGuidelines()">
                                            Guidelines
                                        </button>

                                        <div class="text-end d-flex">
                                            <a href="{{ route('radiologyTests.export') }}"
                                               class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                <i class="ti ti-download me-1"></i>Download Sample Data
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <!-- Hidden Guidelines Section -->
                                <div id="guidelinesBox" class="guidelines-box">
                                    <div class="example_txt card p-3 shadow-sm">
                                        <ol>
                                            <li class="mb-2">
                                                Your CSV data should follow the sample format. The first row must contain column headers.
                                            </li>

                                            <li class="mb-2">
                                                Ensure your file uses UTF-8 encoding to avoid character issues.
                                            </li>

                                            <li class="mb-2">
                                                For numeric fields such as <strong>Report Days, Charge, Amount</strong>, use numbers only.
                                            </li>

                                            <li>
                                                For dropdown-based columns like <strong>Category, Charge Category, Charge Name</strong>,
                                                use exactly the names shown in the system.
                                            </li>
                                        </ol>
                                    </div>
                                </div>

                                {{-- Success --}}
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                {{-- Error --}}
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                {{-- Validation Errors --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>There were some problems with your input:</strong>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="table">
                                        <thead>
                                            <tr>
                                                <th>Test Name</th>
                                                <th>Short Name</th>
                                                <th>Test Type</th>
                                                <th>Category</th>
                                                <th>Charge Category</th>
                                                <th>Charge Name</th>
                                                <th>Sub Category</th>
                                                <th>Method</th>
                                                <th>Report Days</th>
                                                <th>Tax (%)</th>
                                                <th>Charge (INR)</th>
                                                <th>Amount (INR)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="import_form">
                                    <form action="{{ route('radiology.import') }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">
                                                    Select CSV File <span class="text-danger">*</span>
                                                </label>
                                                <input type="file" name="file" id="csv_file"
                                                       class="form-control" required accept=".csv">
                                            </div>
                                        </div>

                                        <button type="submit"
                                                class="btn btn-primary ms-auto d-block mt-3">
                                            <i class="fa-solid fa-cloud-arrow-up"></i> Import Radiology
                                        </button>

                                    </form>
                                </div>

                            </div> <!-- card-body -->
                        </div> <!-- card -->
                    </div> <!-- col -->

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script>
    function toggleGuidelines() {
        var box = document.getElementById("guidelinesBox");
        box.classList.toggle("show");
    }
    // Export to CSV
    function exportToCSV() {
        const table = document.getElementById("table");
        console.log(document.getElementById("table"));
        // const csv = XLSX.utils.table_to_csv(table);
        // Convert table to workbook
        const wb = XLSX.utils.table_to_book(table);
        // Convert workbook to CSV (first sheet)
        const csv = XLSX.utils.sheet_to_csv(wb.Sheets[wb.SheetNames[0]]);
        const blob = new Blob([csv], { type: 'text/csv' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'pathologyTests.csv';
        link.click();
    }
    
</script>

@endsection