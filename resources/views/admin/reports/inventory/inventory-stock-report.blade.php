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
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Inventory Stock Report </h5>
                        <a href="{{ route('inventory-reports') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i>
                            Inventory</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('inventory-stock-reports') }}" method="GET">
    <div class="row align-items-center gy-4">

        <div class="col-md-4">
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

        <div class="col-md-4">
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

        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Item / Category / Supplier / Store"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-4 mt-4">
            <button type="submit" class="btn btn-primary btn-sm">
                Search
            </button>

            <a href="{{ route('inventory-stock-reports') }}"
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
                                            <div class="mb-3">

                                            <button class="btn btn-success" onclick="exportToExcel('inventory-stock-report-table')">Export to Excel</button>
                                            <button class="btn btn-danger" onclick="exportToPDF('inventory-stock-report-table')">Export to PDF</button>
        
                                          </div>  


                                            <!-- Table start -->
                                            <div class="table-responsive table-nowrap">
                                                <table class="table border" id="inventory-stock-report-table">
                                                    <thead class="thead-light">
                                                        <tr>

                                                            <th>Sl No.</th>
                                                            <th>Name</th>
                                                            <th>Category</th>
                                                            <th>Supplier</th>
                                                            <th>Store</th>
                                                            <th>Total Quantity</th>
                                                            <th>Total Issued</th>
                                                            <th>Available Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($stockReport as $index => $row)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $row->item->name ?? '-' }}</td>
                                                            <td>{{ $row->itemCategory->item_category ?? '-' }}</td>
                                                            <td>{{ $row->supplier->item_supplier ?? '-' }}</td>
                                                            <td>{{ $row->store->item_store ?? '-' }}</td>
                                                            <td >{{ $row->total_quantity }}</td>
                                                            <td >{{ $row->total_issued }}</td>
                                                            <td >{{ $row->available_quantity }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                             {{-- Pagination Links --}}
                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $stockReport->currentPage();
                                        $lastPage = $stockReport->lastPage();
                                    @endphp

                                    @if ($stockReport->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $stockReport->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $stockReport->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                        @endif
                                    @endfor

                                    @if ($stockReport->hasMorePages())
                                        <a href="{{ $stockReport->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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