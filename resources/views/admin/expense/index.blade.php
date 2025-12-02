@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Expense List</h5>
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
                                                    class="ti ti-plus me-1"></i>Add Expense</a>
                                        </div>
                                        <!-- First Modal -->
                                        
                                    </div>

                                </div> --}}
                                    <x-table-actions.actions id="expense" name="Expense" />
                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table" id="expense">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Invoice Number</th>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Expense Head</th>
                                                    <th>Amount (INR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 @foreach($expenses as $expense)
                                                    <tr>
                                                        <td>{{ $expense->name }}</td>
                                                        <td>{{ $expense->invoice_no }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                                                        <td>{{ $expense->note }}</td>
                                                        <td>{{ $expense->expenseHead->exp_category ?? '-' }}</td>
                                                        <td>{{ $expense->amount }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-id="{{ $expense->id }}"
                                                                    data-name="{{ $expense->name }}"
                                                                    data-invoice_number="{{ $expense->invoice_no }}"
                                                                    data-date="{{ optional($expense->date)?->format('Y-m-d') ?? $expense->date }}"
                                                                    data-description="{{ $expense->note }}"
                                                                    data-amount="{{ $expense->amount }}"
                                                                    data-expense_name="{{ $expense->expenseHead->id ?? '' }}"
                                                                    data-attach_document="{{ $expense->attach_document ?? '' }}">
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="{{ route('expense.delete', $expense->id) }}" class="ms-2">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="id" value="{{ $expense->id }}">
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
                                            $currentPage = $expenses->currentPage();
                                            $lastPage = $expenses->lastPage();
                                        @endphp

                                        {{-- Previous --}}
                                        @if ($expenses->onFirstPage())
                                            <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                        @else
                                            <a href="{{ $expenses->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                « Prev
                                            </a>
                                        @endif

                                        {{-- Page numbers --}}
                                        @for ($page = 1; $page <= $lastPage; $page++)
                                            @if ($page == $currentPage)
                                                <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                            @else
                                                <a href="{{ $expenses->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                    class="btn btn-outline-secondary btn-sm me-1">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endfor

                                        {{-- Next --}}
                                        @if ($expenses->hasMorePages())
                                            <a href="{{ $expenses->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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
        $expenseOptions = [];
        if (!empty($expenseHeads)) {
            $expenseOptions = collect($expenseHeads)->mapWithKeys(function ($item) {
                return [$item->id => $item->exp_category];
            })->toArray();
        }
    @endphp

    <x-modals.form-modal type="add" id="createModal" title="Add Expense" action="{{ route('expense.create') }}"
        :fields="[
            [
                'name' => 'expense_name',
                'label' => 'Expense Head',
                'type' => 'select',
                'options' => $expenseOptions,
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'invoice_number', 'label' => 'Invoice Number', 'type' => 'text', 'required' => true, 'size' => '4'],
           
           [
    'name' => 'date',
    'label' => 'Date',
    'type' => 'date',
    'required' => true,
    'size' => '12'
         ],

           
            [
                'name' => 'amount',
                'label' => 'Amount (INR) ',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            ['name' => 'attach_document', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '6',],

            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
        ]" :columns="3" />
    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Expense"
        action="{{ url('/expense/update') }}" :fields="[
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'expense_name',
                'label' => 'Expense Head',
                'type' => 'select',
                'options' => $expenseOptions,
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'invoice_number', 'label' => 'Invoice Number', 'type' => 'text', 'required' => true, 'size' => '4'],
           
           [
    'name' => 'date',
    'label' => 'Date',
    'type' => 'date',
    'required' => true,
    'size' => '12'
         ],

           
            [
                'name' => 'amount',
                'label' => 'Amount (INR) ',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            ['name' => 'attach_document', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '6',],

            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'text',
                'size' => '6',
            ],
        ]" :columns="3" />


@endsection()