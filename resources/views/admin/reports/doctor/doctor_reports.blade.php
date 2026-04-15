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
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Doctor Report </h5>
                        <a href="reports/patient-reports-index" class="text-white fw-bold"><i
                                class="fa-solid fa-angles-left text-white"></i>
                            Doctor</a>
                    </div>
                </div>

                <div class="card-body">

                <form method="GET" action="{{ route('doctors.patient.count') }}">
                <label>Select Year:</label>
            
                <select name="year">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>

                    <button class="btn btn-success" type="submit">Filter</button>

                    <button class="btn btn-success" onclick="exportExcel()">
                        Export Excel
                    </button>
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
                                                            <th>Doctor Name</th>
                                                            <th>January</th>
                                                            <th>February</th>
                                                            <th>March</th>
                                                            <th>April</th>
                                                            <th>May</th>
                                                            <th>June</th>
                                                            <th>July</th>
                                                            <th>August</th>
                                                            <th>September</th>
                                                            <th>October</th>
                                                            <th>November</th>
                                                            <th>December</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                     @foreach($doctors as $doctor)
                                                    <tr>
                                                        <td>{{ $doctor->name }}</td>
                                                    
                                                        @for ($m = 1; $m <= 12; $m++)
                                                            <td>{{ $doctorData[$doctor->id][$m] ?? 0 }}</td>
                                                        @endfor
                                                    
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







    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>
    <!-- Use XLSX-STYLE library to support formatting -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx-style@latest/dist/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for the doctor dropdown
            $('#doctor').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        });
    </script>
    <script>
function exportAllData() {
    let fromDate = document.getElementById('from_date').value;
    let toDate = document.getElementById('to_date').value;

    if (!fromDate || !toDate) {
        alert("Please select date range");
        return;
    }

    fetch(`{{ url('/patient-report') }}?from_date=${fromDate}&to_date=${toDate}`)
        .then(response => response.json())
        .then(data => {

            let excelData = [];

            excelData.push(["SAMARITAN CLINIC PVT. LTD."]);
            excelData.push(["10/4D, ELGIN ROAD"]);
            excelData.push([""]);
            excelData.push(["KOLKATA"]);
            excelData.push([""]);
            excelData.push([""]);
            excelData.push(["DOCTOR WISE ADMISSION REGISTER (PACKAGE)"]);
            excelData.push([`From: ${fromDate} To: ${toDate}`]);
            excelData.push([""]); 

        

            // Header
            excelData.push([
                "ID",
                "ADM DT",
                "PATIENT NAME",
                "DATE OF BIRTH",
                "AGE",
                "SEX",
                "MOBILE NO",
                "AREA",
                "UNDER DOCTOR",
                "Location",
                "DISTRICT",
                "STATE",
              //"Department",
              "M. Exe",
              "Language",
             "NEWS Paper",
             "MONTH"
                
            ]);

                        // Rows
            (data.data || []).forEach(item => {
                // For each ipds row, export a row with correct doctor and m.exe
                if (item.ipds && item.ipds.length > 0) {
                    item.ipds.forEach(ipd => {
                        // UNDER DOCTOR: if cons_doctor matches doctor.id, show doctor.name, else blank
                        let doctorName = '';
                        if (
                            ipd.doctor &&
                            ipd.doctor.id &&
                            ipd.cons_doctor &&
                            String(ipd.doctor.id) === String(ipd.cons_doctor)
                        ) {
                            doctorName = ipd.doctor.name;
                        }
                        // M. Exe: refference field
                        let mExe = ipd.refference || '';
                        // Get month name from created_at
                        let monthName = '';
                        if (item.created_at) {
                            const dateObj = new Date(item.created_at);
                            if (!isNaN(dateObj)) {
                                monthName = dateObj.toLocaleString('default', { month: 'long' });
                            }
                        }
                        excelData.push([
                            item.id,
                            item.created_at,
                            item.patient_name,
                            item.dob,
                            item.age,
                            item.gender,
                            item.mobileno,
                            item.area,
                            doctorName,
                            item.address,
                            (item.district_name && item.district_name.name) ? item.district_name.name : item.district,
                            (item.state_name && item.state_name.name) ? item.state_name.name : item.state,
                            mExe,
                            Array.isArray(item.languages_speak) ? item.languages_speak.join(', ') : item.languages_speak,
                            item.newspaper_preference,
                            monthName
                        ]);
                    });
                } else {
                    // If no ipds, still export a row
                    let monthName = '';
                    if (item.created_at) {
                        const dateObj = new Date(item.created_at);
                        if (!isNaN(dateObj)) {
                            monthName = dateObj.toLocaleString('default', { month: 'long' });
                        }
                    }
                    excelData.push([
                        item.id,
                        item.created_at,
                        item.patient_name,
                        item.dob,
                        item.age,
                        item.gender,
                        item.mobileno,
                        item.area,
                        '', // doctorName
                        item.address,
                        (item.district_name && item.district_name.name) ? item.district_name.name : item.district,
                        (item.state_name && item.state_name.name) ? item.state_name.name : item.state,
                        '', // mExe
                        Array.isArray(item.languages_speak) ? item.languages_speak.join(', ') : item.languages_speak,
                        item.newspaper_preference,
                        monthName
                    ]);
                }
            });

           // ✅ CREATE SHEET
        let ws = XLSX.utils.aoa_to_sheet(excelData);

                 // MERGE HEADER ROWS
         ws['!merges'] = [
             { s: { r: 0, c: 0 }, e: { r: 0, c: 15 } },
             { s: { r: 1, c: 0 }, e: { r: 1, c: 15 } },
             { s: { r: 3, c: 0 }, e: { r: 3, c: 15 } },
             { s: { r: 4, c: 0 }, e: { r: 4, c: 15 } }
         ];
         
                // CENTER + BOLD HEADER FUNCTION
         function centerRow(row) {
             for (let col = 0; col <= 15; col++) {
                 let cell = XLSX.utils.encode_cell({ r: row, c: col });
                 if (!ws[cell]) continue;
                 ws[cell].s = {
                     alignment: { horizontal: "center" },
                     font: { bold: true }
                 };
             }
         }
         
         // APPLY STYLE
         centerRow(0);
         centerRow(1);
         centerRow(3);
         centerRow(4);
         
                 // ✅ CREATE WORKBOOK
                 let wb = XLSX.utils.book_new();
                 XLSX.utils.book_append_sheet(wb, ws, "Patients");
         
                 // ✅ DOWNLOAD
                 XLSX.writeFile(wb, "Patient_Report.xlsx");
         
             })
             .catch(error => {
                 console.error("Error:", error);
             });
         
         }
         </script>

         <script>
function exportExcel() {
    let table = document.getElementById("ipd-reports-table");

    // Convert table to worksheet
    let wb = XLSX.utils.book_new();
    let ws = XLSX.utils.table_to_sheet(table);

    // Append sheet
    XLSX.utils.book_append_sheet(wb, ws, "Doctor Report");

    // Download Excel file
    XLSX.writeFile(wb, "doctor-report.xlsx");
}
</script>

         
         
         
@endsection