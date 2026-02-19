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
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> IPD Balance Report </h5>
                        <a href="ipdReportsIndex" class="text-white fw-bold"><i
                                class="fa-solid fa-angles-left text-white"></i>
                            IPD</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('ipd.ipd_balance_reports') }}" method="GET">
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
                                    placeholder="IPD No / Patient Name / Mobile"
                                    value="{{ request('search') }}"
                                >
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Search
                                </button>

                                <a href="{{ route('ipd.ipd_balance_reports') }}" class="btn btn-secondary btn-sm">
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
                                            <div
                                                class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input type="text" class="form-control shadow-sm" placeholder="Search">

                                                </div>
                                            </div>


                                            <!-- Table start -->
                                            <div class="table-responsive table-nowrap">
                                                <table class="table border">
                                                    <thead class="thead-light">
                                                        <tr>

                                                            <th>IPD No</th>
                                                            <th>Case ID </th>
                                                            <th>Patient Name</th>
                                                            <th>Age</th>
                                                            <th>Gender</th>
                                                            
                                                            <th>Mobile Number</th>
                                                            <th>Guardian Name</th>
                                                            <th>Discharge</th>
                                                            <th>Patient Active</th>
                                                            <th>Net Amount(SAR) </th>
                                                            <th>Balance Amount(SAR) </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($ipdReports as $report)
                                                            <tr>
                                                                <td>{{ $report->ipd_no ?? '-' }}</td>
                                                                <td>{{ $report->case_id ?? '-' }}</td>

                                                                <td>{{ $report->patient->patient_name ?? '-' }}</td>

                                                                <td>
                                                                    {{ $report->patient->age ?? '-' }}
                                                                </td>

                                                                <td>
                                                                    {{ $report->patient->gender ?? '-' }}
                                                                </td>

                                                            

                                                                <td>
                                                                    {{ $report->patient->mobileno ?? '-' }}
                                                                </td>

                                                                <td>
                                                                    {{ $report->patient->guardian_name ?? '-' }}
                                                                </td>

                                                                <td>
                                                                    {{ strtolower($report->discharged) === 'yes' ? 'Yes' : 'No' }}
                                                                </td>

                                                                <td>
                                                                    {{ $report->status ?? '-' }}
                                                                </td>

                                                                <td>
                                                                    {{ number_format($report->amount_charged ?? 0, 2) }}
                                                                </td>

                                                                <td>
                                                                    {{ number_format($report->amount_paid ?? 0, 2) }}
                                                                </td>

                                                                <td>
                                                                    {{ number_format($report->balance ?? 0, 2) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="13" class="text-center">
                                                                    No IPD Records Found
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>

                                                </table>
                                            </div>
                                                                     {{-- Pagination Links --}}
                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $ipdReports->currentPage();
                                        $lastPage = $ipdReports->lastPage();
                                    @endphp

                                    @if ($ipdReports->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $ipdReports->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $ipdReports->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                        @endif
                                    @endfor

                                    @if ($ipdReports->hasMorePages())
                                        <a href="{{ $ipdReports->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm">Next »</a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                    @endif

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