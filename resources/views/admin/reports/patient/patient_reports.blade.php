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
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Patient Report </h5>
                        <a href="reports/patient-reports-index" class="text-white fw-bold"><i
                                class="fa-solid fa-angles-left text-white"></i>
                            Patient</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('ipd.ipd_reports') }}" method="GET">
                        <div class="row align-items-center">

                            {{-- Date From --}}
                            <div class="col-md-3">
                                <label class="form-label">
                                    Date From <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"
                                    max="{{ now()->toDateString() }}">
                            </div>

                            {{-- Date To --}}
                            <div class="col-md-3">
                                <label class="form-label">
                                    Date To <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"
                                    max="{{ now()->toDateString() }}">
                            </div>

                            {{-- Export --}}
                            <div class="col-md-3 mt-4">
                                <button class="btn btn-success" onclick="exportToExcel('ipd-reports-table')">Export to
                                    Excel</button>
                                <button class="btn btn-danger" onclick="exportToPDF('ipd-reports-table')">Export to
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


                                                            <th>IPD No</th>
                                                            <th>Patient Name</th>
                                                            <th>Age</th>
                                                            <th>Gender</th>
                                                            <th>Mobile Number</th>
                                                            <th>Guardian Name</th>
                                                            <th>Doctor Name </th>
                                                            <th>Status</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>




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

@endsection