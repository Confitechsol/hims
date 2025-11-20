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
                                                    <th>Reference No</th>
                                                    <th>Case ID</th>
                                                    <th>Child Name</th>
                                                    <th>Gender</th>
                                                    <th>Birth Date</th>
                                                    <th>Mother Name</th>
                                                    <th>Father Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                             @foreach($birthReports as $report)
                                                    <tr>
                                                        <td>{{$report->id}}</td>
                                                        <td>{{$report->case_reference_id}}</td>
                                                        <td>{{$report->child_name}}</td>
                                                        <td>{{$report->gender}}</td>
                                                        <td>{{\Carbon\Carbon::parse($report->created_at)->format('d/m/Y h:i A') }}</td>
                                                        <td>{{$report->mother_name }}</td>
                                                        <td>{{$report->father_name}}</td>
                                                        
                                                        {{-- <td>
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
                                                    </tr> --}}
                                                    <td>
    <div class="d-flex">
        <button
            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
            data-id="{{ $report->id }}">
            <i class="ti ti-pencil"></i>
        </button>

        <form action="{{ route('birth.delete', $report->id) }}" 
              method="POST" 
              style="display:inline;">
            @csrf
            @method('DELETE')

            <button type="submit" 
                    onclick="return confirm('Are you sure you want to delete this record?')" 
                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                <i class="ti ti-trash"></i>
            </button>
        </form>

    </div>
</td>

                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Pagination Links --}}
                                    <div class="mt-3" id="pagination-wrapper">
                                      @php
                                          $currentPage = $birthReports->currentPage();
                                          $lastPage = $birthReports->lastPage();
                                    @endphp

                                    @if ($birthReports->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                    <a href="{{ $birthReports->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                     class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $lastPage; $page++)
                                    @if ($page == $currentPage)
                                    <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                    @else
                                   <a href="{{ $birthReports->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                   class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                    @endif
                                    @endfor

                                   @if ($birthReports->hasMorePages())
                                   <a href="{{ $birthReports->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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
    <x-modals.birth-modal type="add" id="createModal" class="modal-fullscreen" title="Add Birth Record" action="{{ route('birth.create') }}"
        :fields="[
            [
                'name' => 'child_name',
                'label' => 'Child Name',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            [ 'name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'required' => true, 'options' => [     'Male' => 'Male',   'Female' => 'Female' ], 'size' => '3'],

            ['name' => 'weight', 'label' => 'Weight', 'type' => 'text', 'required' => true, 'size' => '4'],
            
            ['name' => 'baby_image', 'label' => 'Child Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            ['name' => 'birth_date', 'label' => 'Birth Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'contact_person_phone', 'label' => 'Phone','required' => true, 'type' => 'text',  'size' => '6'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'text',  'size' => '12'],
            ['name' => 'caseId', 'label' => 'Case Id', 'type' => 'text',  'size' => '6'],
           [
                'name' => 'mother_name',
                'label' => 'Mother Name ',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'mother_image', 'label' => 'Mother Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            
            [
                'name' => 'father_name',
                'label' => 'Father Name ',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'father_image', 'label' => 'Father Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            
            [
                'name' => 'report',
                'label' => 'Report',
                'required' => true,
                'type' => 'text',
                'size' => '5',
            ],
           ['name' => 'report_image', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '6',],

             [
                'name' => 'icd_code',
                'label' => 'ICD Code',
                'required' => true,
                'type' => 'text',
                'size' => '5',
            ],
           
        ]" :columns="4" />
    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Birth"
        action="{{ route('birth.update') }}" :fields="[
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'child_name',
                'label' => 'Child Name',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            [ 'name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'required' => true, 'options' => [     'Male' => 'Male',   'Female' => 'Female' ], 'size' => '3'],

            ['name' => 'weight', 'label' => 'Weight', 'type' => 'text', 'required' => true, 'size' => '4'],
            
            ['name' => 'baby_image', 'label' => 'Child Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            ['name' => 'birth_date', 'label' => 'Birth Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'contact_person_phone', 'label' => 'Phone', 'type' => 'text',  'size' => '6'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'text',  'size' => '12'],
            ['name' => 'caseId', 'label' => 'Case Id', 'type' => 'text',  'size' => '6'],
           [
                'name' => 'mother_name',
                'label' => 'Mother Name ',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'mother_image', 'label' => 'Mother Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            [
                'name' => 'contact_person_name',
                'label' => 'Contact Person Name',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            [
                'name' => 'father_name',
                'label' => 'Father Name ',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'father_image', 'label' => 'Father Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            
            [
                'name' => 'report',
                'label' => 'Report',
                'type' => 'text',
                'size' => '5',
            ],
           
        ]" :columns="3" />

   

@endsection()