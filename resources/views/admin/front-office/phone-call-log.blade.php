@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-phone me-2"></i>Phone Call Log</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <a href="{{ route('visitors') }}" class="btn btn-secondary text-white fs-13 btn-md"><i class="ti ti-arrow-left me-1"></i>Back to Visitors</a>
                                        <button class="btn btn-success text-white fs-13 btn-md ms-2" data-bs-toggle="modal" data-bs-target="#createCallModal"><i class="ti ti-plus me-1"></i>Add Call</button>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCallModal"><i class="ti ti-plus me-1"></i>Add Call</button>
                                        <button class="btn btn-primary copy-btn" data-clipboard-target="#callLogsTable">Copy</button>
                                        <button class="btn btn-success" onclick="exportToExcel('callLogsTable')">Export to Excel</button>
                                        <button class="btn btn-info" onclick="exportToCSV('callLogsTable')">Export to CSV</button>
                                        <button class="btn btn-danger" onclick="exportToPDF('callLogsTable')">Export to PDF</button>
                                        <button class="btn btn-warning" onclick="printTable('callLogsTable')">Print</button>
                                    </div>
                                </div>

                                <div class="input-icon-start position-relative mb-3">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <form method="GET" class="d-flex gap-2">
                                        <input type="text" name="search" class="form-control shadow-sm" 
                                               placeholder="Search by Name, Phone, or Purpose" 
                                               value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-outline-primary">Search</button>
                                    </form>
                                </div>

                                <div class="table-responsive table-nowrap">
                                    <table class="table table-striped" id="callLogsTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Date</th>
                                                <th>Next Follow Up Date</th>
                                                <th>Call Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($callLogs as $log)
                                                @php
                                                    // Support multiple possible column names
                                                    $nextFollow = $log->next_follow_up_date ?? $log->follow_up_date ?? $log->follow_up_date ?? null;
                                                    $callType = $log->call_type ?? $log->purpose ?? $log->description ?? '-';
                                                    $dateVal = $log->date ?? $log->created_at ?? null;
                                                @endphp
                                                <tr>
                                                    <td>{{ $log->name ?? '-' }}</td>
                                                    <td>{{ $log->contact ?? ($log->phone ?? '-') }}</td>
                                                    <td>{{ $dateVal ? \Carbon\Carbon::parse($dateVal)->format('d-m-Y') : '-' }}</td>
                                                    <td>{{ $nextFollow ? \Carbon\Carbon::parse($nextFollow)->format('d-m-Y') : '-' }}</td>
                                                    <td>{{ $callType }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn ms-2"
                                                                data-id="{{ $log->id ?? '' }}"
                                                                data-name="{{ $log->name ?? '' }}"
                                                                data-contact="{{ $log->contact ?? $log->phone ?? '' }}"
                                                                data-purpose="{{ $log->purpose ?? $log->description ?? '' }}"
                                                                data-id_proof="{{ $log->id_proof ?? '' }}"
                                                                data-visit_to="{{ $log->visit_to ?? '' }}"
                                                                data-related_to="{{ $log->related_to ?? '' }}"
                                                                data-no_of_pepple="{{ $log->no_of_pepple ?? 1 }}"
                                                                data-date="{{ $dateVal ? \Carbon\Carbon::parse($dateVal)->format('Y-m-d') : '' }}"
                                                                data-in_time="{{ isset($log->in_time) ? \Carbon\Carbon::parse($log->in_time)->format('H:i') : '' }}"
                                                                data-out_time="{{ isset($log->out_time) ? \Carbon\Carbon::parse($log->out_time)->format('H:i') : '' }}"
                                                                data-note="{{ $log->note ?? '' }}"
                                                                data-next_follow_up_date="{{ $nextFollow ? \Carbon\Carbon::parse($nextFollow)->format('Y-m-d') : '' }}"
                                                                data-call_type="{{ $callType }}"
                                                                data-call_duration="{{ $log->call_duration ?? '' }}">
                                                                <i class="ti ti-pencil"></i>
                                                            </button>

                                                            <form method="POST" action="{{ route('visitors.delete', $log->id ?? 0) }}" onsubmit="return confirm('Delete this visitor?');" class="ms-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" title="Delete">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">No call logs found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3" id="pagination-wrapper">
                                    @if($callLogs->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $callLogs->previousPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}" class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $callLogs->lastPage(); $page++)
                                        @if ($page == $callLogs->currentPage())
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $callLogs->url($page) }}{{ request('search') ? '&search=' . request('search') : '' }}" class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                        @endif
                                    @endfor

                                    @if ($callLogs->hasMorePages())
                                        <a href="{{ $callLogs->nextPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}" class="btn btn-outline-secondary btn-sm">Next »</a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection()

@php
    $purposeOptions = [];
    if (!empty($purposes)) {
        $purposeOptions = collect($purposes)->mapWithKeys(function ($item) {
            return [$item->visitors_purpose => $item->visitors_purpose];
        })->toArray();
    }
@endphp

{{-- Include edit modal so edit buttons on this page can open/populate the shared modal --}}
<x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Visitor"
    action="{{ url('/visitors/update') }}" :fields="[
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
        ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
        ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
        ['name' => 'next_follow_up_date', 'label' => 'Next Follow Up Date', 'type' => 'date', 'required' => false, 'size' => '4'],
        ['name' => 'call_type', 'label' => 'Call Type', 'type' => 'text', 'required' => false, 'size' => '4'],
        ['name' => 'call_duration', 'label' => 'Duration (secs)', 'type' => 'number', 'required' => false, 'size' => '4'],
    ]" :columns="3" />

{{-- Create modal for Phone Call Log --}}
<x-modals.form-modal type="add" id="createCallModal" title="Add Call Log" action="{{ route('phone-call-log.create') }}" :fields="[
    ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '4'],
    ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => true, 'size' => '4'],
    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
    ['name' => 'next_follow_up_date', 'label' => 'Next Follow Up Date', 'type' => 'date', 'required' => false, 'size' => '4'],
    ['name' => 'call_type', 'label' => 'Call Type', 'type' => 'select', 'options' => ['Incoming' => 'Incoming', 'Outgoing' => 'Outgoing', 'Follow-up' => 'Follow-up'], 'required' => false, 'size' => '4'],
    ['name' => 'call_duration', 'label' => 'Duration (secs)', 'type' => 'number', 'required' => false, 'size' => '4'],
    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
    ]" :columns="3" />

