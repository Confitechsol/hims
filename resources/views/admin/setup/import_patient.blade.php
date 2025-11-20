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
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Import Patient</h5>
            </div>

            <div class="card-body">


                {{-- Hospital Name & Code --}}
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
                                            <button id ="exportBtn"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                    class="ti ti-download me-1"></i>Download Sample Data</button>
                                        </div>
                                    </div>

                                </div>

                                <!-- Hidden Guidelines Section -->
                                <div id="guidelinesBox" class="guidelines-box">
                                    <div class="example_txt card p-3 shadow-sm">
                                        <ol>
                                            <li class="mb-2">Your CSV data should be in the format below. The first line
                                                of your CSV
                                                file
                                                should be the column headers as in the table example. Also make sure
                                                that
                                                your file is UTF-8 to avoid unnecessary encoding problems.
                                            </li>
                                            <li class="mb-2">For patient 'Gender' use Male, Female value.</li>
                                            <li class="mb-2">For Age column 'Age (year)' and 'Age (month)' and 'Age
                                                (day)' make sure
                                                that is numbers only.</li>
                                            <li>For patient 'Marital Status' use Single, Married, Widowed, Separated,
                                                Not Specified value.</li>
                                        </ol>
                                    </div>
                                </div>
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>There were some problems with your
                                            input:</strong>
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
                                                <th>Patient</th>
                                                <th>Gender</th>
                                                <th>Blood Group</th>
                                                <th>Age(Year)</th>
                                                <th>Age(Month)</th>
                                                <th>Age(Day)</th>
                                                <th>Marital Status</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Remarks</th>
                                                <th>Known Allergies</th>
                                                <th>Identification Number</th>
                                                <th>TPA ID</th>
                                                <th>TPA Validity</th>
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
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                                <td>Sample Data</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="import_form">
                                    <form action="{{ route('patients.import') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <!-- <div class="col-md-6">
                                                <label for="" class="form-label">
                                                    Blood Group</label>
                                                <select name="blood_group" class="form-select">
                                                    <option value=""> Select</option>
                                                    <option value="1"> O+</option>
                                                    <option value="2"> A+</option>
                                                    <option value="3"> B+</option>
                                                    <option value="4"> AB+</option>
                                                    <option value="5"> O-</option>
                                                    <option value="6"> AB-</option>
                                                </select>
                                            </div> -->
                                            <div class="col-md-12">
                                                <label for="" class="form-label">
                                                    Select CSV File <span class="text-danger">*</span></label>
                                                <input type="file" name="csv_file" id="csv_file" class="form-control"
                                                    required accept=".csv">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary ms-auto d-block mt-3"><i
                                                class="fa-solid fa-cloud-arrow-up"></i> Import Patients</button>
                                    </form>
                                </div>


                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                </div>

            </div>
        </div>
    </div>
</div>


<!-- <script>
                            function toggleGuidelines() {
                                var box = document.getElementById("guidelinesBox");

                                if (box.classList.contains("show")) {
                                    box.classList.remove("show");
                                    box.classList.add("hide");

                                    setTimeout(() => {
                                        box.classList.remove("hide");
                                        box.style.display = "none";
                                    }, 350); // match fadeOut duration
                                } else {
                                    box.style.display = "block";
                                    box.classList.add("show");
                                }
                            }
                        </script> -->
<!-- SheetJS for Excel/CSV export -->
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
        link.download = 'patients.csv';
        link.click();
    }
    
</script>
<script>
    document.getElementById("exportBtn").addEventListener("click", function() {
      const table = document.getElementById("table");
      if (!table) {
        alert("Table not found!");
        return;
      }
      const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
      XLSX.writeFile(wb, "patients.xlsx");
    });
  </script>
@endsection