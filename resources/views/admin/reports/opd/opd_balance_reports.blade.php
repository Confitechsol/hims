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
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> OPD Balance Report </h5>
                        <a href="{{ route('opd.reports') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i>
                            OPD</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('opd.opd_reports') }}" method="GET">
                        <div class="row align-items-center gy-4">

                            {{-- Date From --}}
                            <div class="col-md-3">
                                <label class="form-label">
                                    Date From <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    name="date_from"
                                    class="form-control"
                                    value="{{ request('date_from') }}"
                                    max="{{ now()->toDateString() }}"
                                >
                            </div>

                            {{-- Date To --}}
                            <div class="col-md-3">
                                <label class="form-label">
                                    Date To <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    name="date_to"
                                    class="form-control"
                                    value="{{ request('date_to') }}"
                                    max="{{ now()->toDateString() }}"
                                >
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">All</option>
                                    <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ request('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            {{-- Search --}}
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="OPD No / Patient Name / Mobile"
                                    value="{{ request('search') }}"
                                >
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Search
                                </button>

                                <a href="{{ route('opd.opd_reports') }}" class="btn btn-secondary btn-sm">
                                    Reset
                                </a>
                            </div>

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
                                            

                                            <div class="table-responsive table-nowrap">
                                                <!-- Table start -->
                                                <table class="table border">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>OPD No</th>
                                                            <th>Patient Name</th>
                                                            <th>Case ID</th>
                                                            <th>Age</th>
                                                            <th>Gender</th>
                                                            <th>Mobile Number</th>
                                                            <th>Antenatal</th>
                                                            <th>Discharged</th>
                                                            <th>Net Amount (SAR)</th>
                                                            <th>Paid Amount (SAR)</th>
                                                            <th>Balance Amount (SAR)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($opdReports as $opd)
                                                            <tr>
                                                                <td>{{ $opd->opd_no }}</td>
                                                                <td>{{ $opd->patient->name ?? '-' }}</td>
                                                                <td>{{ $opd->case_type ?? '-' }}</td>
                                                                <td>{{ $opd->patient->age ?? '-' }}</td>
                                                                <td>{{ $opd->patient->gender ?? '-' }}</td>
                                                                <td>{{ $opd->patient->mobile ?? '-' }}</td>
                                                                <td>
                                                                    {{ strtolower($opd->case_type) === 'antenatal' ? 'Yes' : 'No' }}
                                                                </td>
                                                                <td>
                                                                    {{ $opd->status == 'discharged' ? 'Yes' : 'No' }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($opd->amount ?? 0, 2) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($opd->paid_amount ?? 0, 2) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format(($opd->amount ?? 0) - ($opd->paid_amount ?? 0), 2) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="11" class="text-center text-muted">
                                                                    No OPD records found
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>


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