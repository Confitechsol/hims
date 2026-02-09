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
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Inventory Item Report </h5>
                        <a href="{{ route('inventory-reports') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i>
                            Inventory</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('inventory-issue-reports') }}" method="GET">
                        <div class="row align-items-center gy-4">

                            <div class="col-md-3">
                                <label class="form-label">
                                    Date From
                                </label>
                                <input
                                    type="date"
                                    name="date_from"
                                    class="form-control"
                                    value="{{ request('date_from') }}"
                                    max="{{ now()->toDateString() }}"
                                >
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">
                                    Date To
                                </label>
                                <input
                                    type="date"
                                    name="date_to"
                                    class="form-control"
                                    value="{{ request('date_to') }}"
                                    max="{{ now()->toDateString() }}"
                                >
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Item / Category / Department / Issued To"
                                    value="{{ request('search') }}"
                                >
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Return Status</label>
                                <select name="is_returned" class="form-select">
                                    <option value="">All</option>
                                    <option value="no" {{ request('is_returned') == 'no' ? 'selected' : '' }}>
                                        Not Returned
                                    </option>
                                    <option value="yes" {{ request('is_returned') == 'yes' ? 'selected' : '' }}>
                                        Returned
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Search
                                </button>

                                <a href="{{ route('inventory-issue-reports') }}"
                                class="btn btn-secondary btn-sm">
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
                                            


                                            <!-- Table start -->
                                            <div class="table-responsive table-nowrap">
    <table class="table border">
        <thead class="thead-light">
            <tr>
                <th>Sl No.</th>
                <th>Issue Date</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Department</th>
                <th>Issued To</th>
                <th class="text-end">Quantity</th>
                <th>Status</th>
                <th>Issue Age (Days)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($issues as $index => $issue)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        {{ $issue->issue_date
                            ? \Carbon\Carbon::parse($issue->issue_date)->format('d-m-Y')
                            : '-' }}
                    </td>

                    <td>{{ $issue->item->name ?? '-' }}</td>

                    <td>{{ $issue->category->item_category ?? '-' }}</td>

                    <td>{{ $issue->department->name ?? '-' }}</td>

                    <td>{{ $issue->issuedTo->name ?? '-' }}</td>

                    <td class="text-end">{{ $issue->quantity }}</td>

                    <td>
                        <span class="badge {{ $issue->is_returned == 'yes' ? 'bg-success' : 'bg-warning' }}">
                            {{ $issue->is_returned == 'yes' ? 'Returned' : 'Issued' }}
                        </span>
                    </td>

                    <td>
                        {{ $issue->issue_age !== null ? $issue->issue_age : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        No issued items found
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