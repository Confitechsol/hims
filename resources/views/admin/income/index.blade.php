@extends('layouts.adminLayout')
@section('content')
    <!-- row start -->
    <div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Income List</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    {{-- <div
                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input onkeyup="dataSearch()" type="text" id="language-search" name="search"
                                                         class="form-control shadow-sm"
                                                        placeholder="Search">
                                                </div>
                                               
                                            </div>
                
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <div class="text-end d-flex">
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary text-white ms-2 btn-md"
                                                data-bs-toggle="modal" data-bs-target="#add_tpa"><i
                                                    class="ti ti-plus me-1"></i>Add Income</a>
                                        </div>
                                        <!-- First Modal -->
                                        
                                    </div>

                                </div> --}}
                                    <x-table-actions.actions id="income" name="Income" />
                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table" id="income">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Invoice Number</th>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Income Head</th>
                                                    <th>Amount (INR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($incomes as $income)
                                                    <tr>
                                                        <td>{{ $income->name }}</td>
                                                        <td>{{ $income->invoice_no }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($income->date)->format('d-m-Y') }}</td>
                                                        <td>{{ $income->note }}</td>
                                                        <td>{{ $income->incomeHead->income_category ?? '-' }}</td>
                                                        <td>{{ $income->amount }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-id="">
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="{{route('income.destroy')}}" onsubmit="return confirm('Are you Sure?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="id" value="{{$income->id}}">
                                                                    <button type="submit"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Pagination Links --}}
                                    <div class="mt-3" id="pagination-wrapper">
                                        @php
                                            $currentPage = $incomes->currentPage();
                                            $lastPage = $incomes->lastPage();
                                        @endphp

                                        {{-- Previous --}}
                                        @if ($incomes->onFirstPage())
                                            <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                        @else
                                            <a href="{{ $incomes->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                « Prev
                                            </a>
                                        @endif

                                        {{-- Page numbers --}}
                                        @for ($page = 1; $page <= $lastPage; $page++)
                                            @if ($page == $currentPage)
                                                <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                            @else
                                                <a href="{{ $incomes->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                    class="btn btn-outline-secondary btn-sm me-1">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endfor

                                        {{-- Next --}}
                                        @if ($incomes->hasMorePages())
                                            <a href="{{ $incomes->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm">
                                                Next »
                                            </a>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
    @php
    $options = $incomeHeads->mapWithKeys(function ($item) {
        return [$item->id => $item->income_category];
    })->toArray();
    @endphp
    <x-modals.form-modal type="add" id="createModal" title="Add Income" action="{{ route('income.create') }}"
        :fields="[
            [
                'name' => 'income_head_id',
                'label' => 'Income Head',
                'type' => 'select',
                'options'=>$options,
                'required' => true,
            ],
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'invoice_no',
                'label' => 'Invoice Number',
                'type' => 'text',
            ],
            [
                'name' => 'date',
                'label' => 'Date',
                'type' => 'date',
                'required' => true,
            ],
            [
                'name' => 'amount',
                'label' => 'Amount (INR)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'document',
                'label' => 'Attach Document',
                'type' => 'file',
            ],
            [
                'name' => 'note',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => true,
                'size' => '12',
            ],
            
        ]" :columns="2" />
    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Company Name"
        action="{{ route('income') }}" :fields="[
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'income_head_id',
                'label' => 'Income Head',
                'type' => 'select',
                'options'=>$options,
                'required' => true,
            ],
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'invoice_no',
                'label' => 'Invoice Number',
                'type' => 'text',
            ],
            [
                'name' => 'date',
                'label' => 'Date',
                'type' => 'date',
                'required' => true,
            ],
            [
                'name' => 'amount',
                'label' => 'Amount (INR)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'document',
                'label' => 'Attach Document',
                'type' => 'file',
            ],
            [
                'name' => 'note',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => true,
                'size' => '12',
            ],
        ]" :columns="2" />

<script>
document.addEventListener('DOMContentLoaded', function () {
createAjaxTable({
    apiUrl: "{{ route('income') }}",
    tableSelector: "#income",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.invoice_no}</td>
            <td>${item.date}</td>
            <td>${item.note}</td>
            <td>${item.income_head.income_category}</td>
            <td>${item.amount}</td>
            <td>
                <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editModal"
                    data-id="${item.id}"
                    data-name="${item.name}">
                    <i class="ti ti-pencil"></i>
                </button>
                <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" data-bs-toggle="modal"
                data-bs-target="#deleteModal" data-id="${item.id}"
                data-name="${item.name}">
                <i class="ti ti-trash"></i>
                </button>
            </form>
            </td>
        `;
        return row;
    }
    });  
}); 
</script>
@endsection()
