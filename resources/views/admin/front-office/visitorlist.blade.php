@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Visitor List</h5>
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
                                    <x-table-actions.actions id="visitors" name="Visitor" />
                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table" id="visitors">
                                            <thead class="thead-light">
                                                <tr>			 	
                                                    <th>Purpose</th>												
                                                    <th>Name</th>
                                                    <th>Visit To</th>
                                                    <th>Related To</th>
                                                    <th>Phone</th>
                                                    <th>Date</th>
                                                    <th>In Time</th>
                                                    <th>Out Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                             @foreach($visitorsReports as $report)
                                                    <tr>
                                                        <td>{{ $report->purpose }}</td>
                                                        <td>{{ $report->name }}</td>
                                                        <td>{{ $report->visit_to ?? '-' }}</td>
                                                        <td>{{ $report->related_to ?? '-' }}</td>
                                                        <td>{{ $report->contact ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</td>
                                                        <td>
                                                          {{ isset($report->in_time)
                                                          ? \Carbon\Carbon::parse($report->in_time)->format('h:i A')
                                                           : '-' }}
                                                         </td>
                                                          <td>
                                                          {{ isset($report->out_time)
                                                          ? \Carbon\Carbon::parse($report->out_time)->format('h:i A')
                                                           : '-' }}
                                                         </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-id="{{ $report->id }}"
                                                                    data-name="{{ $report->name }}"
                                                                    data-purpose="{{ $report->purpose }}"
                                                                    data-contact="{{ $report->contact }}"
                                                                    data-id_proof="{{ $report->id_proof }}"
                                                                    data-visit_to="{{ $report->visit_to }}"
                                                                    data-related_to="{{ $report->related_to }}"
                                                                    data-no_of_pepple="{{ $report->no_of_pepple }}"
                                                                    data-date="{{ optional($report->date)->format('Y-m-d') ?? $report->date }}"
                                                                    data-in_time="{{ optional($report->in_time)?->format('H:i') ?? '' }}"
                                                                    data-out_time="{{ optional($report->out_time)?->format('H:i') ?? '' }}"
                                                                    data-note="{{ $report->note }}">
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="{{ route('visitors.delete', $report->id) }}" class="ms-2">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="id" value="{{ $report->id }}">
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
                                          $currentPage = $visitorsReports->currentPage();
                                          $lastPage = $visitorsReports->lastPage();
                                    @endphp

                                    @if ($visitorsReports->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                    <a href="{{ $visitorsReports->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                     class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $lastPage; $page++)
                                    @if ($page == $currentPage)
                                    <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                    @else
                                   <a href="{{ $visitorsReports->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                   class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                    @endif
                                    @endfor

                                   @if ($visitorsReports->hasMorePages())
                                   <a href="{{ $visitorsReports->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                   class="btn btn-outline-secondary btn-sm">Next »</a>
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
        $purposeOptions = [];
        if (!empty($purposes)) {
            $purposeOptions = collect($purposes)->mapWithKeys(function ($item) {
                return [$item->visitors_purpose => $item->visitors_purpose];
            })->toArray();
        }
        
        $visitToDropdown = [];
        if (!empty($visitToOptions)) {
            $visitToDropdown = array_combine($visitToOptions, $visitToOptions);
        }
        
        $relatedToDropdown = [];
        if (!empty($relatedToOptions)) {
            $relatedToDropdown = array_combine($relatedToOptions, $relatedToOptions);
        }
    @endphp

    <x-modals.form-modal type="add" id="createModal" title="Add Visitor" action="{{ route('visitors.create') }}"
        :fields="[
            [
                'name' => 'purpose',
                'label' => 'Purpose',
                'type' => 'select',
                'options' => $purposeOptions,
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'id_proof', 'label' => 'ID Card', 'type' => 'text', 'required' => false, 'size' => '12'],
            [
                'name' => 'visit_to',
                'label' => 'Visit To',
                'type' => 'select',
                'options' => $visitToDropdown,
                'required' => false,
                'size' => '4',
            ],
            [
                'name' => 'related_to',
                'label' => 'Related To',
                'type' => 'select',
                'options' => $relatedToDropdown,
                'required' => false,
                'size' => '4',
            ],
            ['name' => 'no_of_pepple', 'label' => 'Number Of Person', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'in_time', 'label' => 'In Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'out_time', 'label' => 'Out Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
            ['name' => 'image', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '12'],
        ]" :columns="3" />

    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Visitor"
        action="{{ url('/visitors/update') }}" :fields="[
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'purpose',
                'label' => 'Purpose',
                'type' => 'select',
                'options' => $purposeOptions,
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'id_proof', 'label' => 'ID Card', 'type' => 'text', 'required' => false, 'size' => '12'],
            [
                'name' => 'visit_to',
                'label' => 'Visit To',
                'type' => 'select',
                'options' => $visitToDropdown,
                'required' => false,
                'size' => '4',
            ],
            [
                'name' => 'related_to',
                'label' => 'Related To',
                'type' => 'select',
                'options' => $relatedToDropdown,
                'required' => false,
                'size' => '4',
            ],
            ['name' => 'no_of_pepple', 'label' => 'Number Of Person', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'in_time', 'label' => 'In Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'out_time', 'label' => 'Out Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
            ['name' => 'image', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '12'],
        ]" :columns="3" />

@endsection()