@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Birth List</h5>
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
                                    <x-table-actions.actions id="birth" name="Birth Record" />
                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table" id="birth">
                                            <thead class="thead-light">
                                                <tr>			 	
                                                    <th>Purpose</th>												
                                                    <th>Name</th>
                                                    <th>Visit To</th>
                                                    <th>IPD/OPD/Staff</th>
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
                                                        <td>{{$report->visitors_purpose}}</td>
                                                        <td>{{$report->visitorBooks[0]->name ?? '-' }}</td>
                                                        <td>{{$report->visitorBooks[0]->visit_to ?? '-'}}</td>
                                                        <td>{{$report->visitorBooks[0]->related_to ?? '-'}}</td>
                                                        <td>{{$report->visitorBooks[0]->contact ?? '-' }}</td>
                                                        <td>
                                                          {{ isset($report->visitorBooks[0]->date)
                                                         ? \Carbon\Carbon::parse($report->visitorBooks[0]->date)->format('Y-m-d')
                                                         : '-' }}
                                                        </td>
                                                        <td>
                                                          {{ isset($report->visitorBooks[0]->in_time)
                                                          ? \Carbon\Carbon::parse($report->visitorBooks[0]->in_time)->format('h:i A')
                                                           : '-' }}
                                                         </td>

                                                          <td>
                                                          {{ isset($report->visitorBooks[0]->out_time)
                                                          ? \Carbon\Carbon::parse($report->visitorBooks[0]->out_time)->format('h:i A')
                                                           : '-' }}
                                                         </td>


                                                  

    
                                                        <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-id="">
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="">

                                                                    <input type="hidden" name="id" value="">
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
    <x-modals.birth-modal type="add" id="createModal" title="Add Birth Record" action="{{ route('tpamanagement.store') }}"
        :fields="[
            [
                'name' => 'organisation_name',
                'label' => 'organisation Name',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'contact_no', 'label' => 'Phone', 'type' => 'text', 'required' => true, 'size' => '4'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'contact_person_name',
                'label' => 'Contact Person Name',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            [
                'name' => 'contact_person_phone',
                'label' => 'Contact Person Phone',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
        ]" :columns="3" />
    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Company Name"
        action="{{ route('tpamanagement.update') }}" :fields="[
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'organisation_name',
                'label' => 'organisation Name',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'contact_no', 'label' => 'Phone', 'type' => 'text', 'required' => true, 'size' => '4'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'contact_person_name',
                'label' => 'Contact Person Name',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            [
                'name' => 'contact_person_phone',
                'label' => 'Contact Person Phone',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
        ]" :columns="3" />




@endsection()