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

                                                <button class="btn btn-success" onclick="exportToExcel('inventory-asset-report-table')">Export to Excel</button>
                                                 <button class="btn btn-danger" onclick="exportToPDF('inventory-asset-report-table')">Export to PDF</button>
        
                                             </div>  


                                            <!-- Table start -->
                                            <div class="table-responsive table-nowrap">
                                                
                                                <table class="table border" id="inventory-asset-report-table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Asset Name</th>
                                                            <th>Category</th>
                                                            <th>Supplier</th>
                                                            <th>Store</th>
                                                            <th class="text-end">Qty</th>
                                                            <th class="text-end">Total Purchase Price</th>
                                                            <th class="text-end">Salvage Value</th>
                                                            <th class="text-center">Useful Life (yrs)</th>
                                                            <th class="text-end">Annual Depreciation</th>
                                                            <th class="text-center">Expiry Date</th>
                                                            <th class="text-end">Net Book Value</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @forelse($assets as $index => $asset)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>

                                                                <td>{{ $asset->item->name ?? '-' }}</td>

                                                                <td>{{ $asset->itemCategory->item_category ?? '-' }}</td>

                                                                <td>{{ $asset->supplier->item_supplier ?? '-' }}</td>

                                                                <td>{{ $asset->store->item_store ?? '-' }}</td>

                                                                <td class="text-end">{{ $asset->quantity }}</td>

                                                                <td class="text-end">
                                                                    {{ number_format($asset->total_cost, 2) }}
                                                                </td>

                                                                <td class="text-end">
                                                                    {{ number_format($asset->salvage_value, 2) }}
                                                                </td>

                                                                <td class="text-center">
                                                                    {{ $asset->useful_life ?? '-' }}
                                                                </td>

                                                                <td class="text-end">
                                                                    {{ number_format($asset->annual_depreciation ?? 0, 2) }}
                                                                </td>

                                                                <td class="text-center">
                                                                    {{ $asset->expiry_date
                                                                        ? \Carbon\Carbon::parse($asset->expiry_date)->format('d-m-Y')
                                                                        : '-' }}
                                                                </td>

                                                                <td class="text-end fw-bold">
                                                                    {{ number_format($asset->net_book_value, 2) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="13" class="text-center text-muted">
                                                                    No assets found for the selected criteria.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>

                                            </div>

                                             {{-- Pagination Links --}}
                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $assets->currentPage();
                                        $lastPage = $assets->lastPage();
                                    @endphp

                                    @if ($assets->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $assets->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $assets->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                        @endif
                                    @endfor

                                    @if ($assets->hasMorePages())
                                        <a href="{{ $assets->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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