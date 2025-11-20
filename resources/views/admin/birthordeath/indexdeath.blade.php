@extends('layouts.adminLayout')
@section('content')


<div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Death List</h5>
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
                                    <x-table-actions.actions id="death" name="Death Record" />
                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table" id="birth">
                                            <thead class="thead-light">
                                                <tr>			 	
                                                    <th>Reference No</th>					
                                                    <th>Case ID</th>
                                                    <th>Patient Name</th>
                                                    <th>Guardian Name</th>
                                                    <th>Gender</th>
                                                    <th>Death Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                             @foreach($deathReports as $report)
                                                    <tr>
                                                        <td>{{$report->id}}</td>
                                                        <td>{{$report->case_reference_id}}</td>
                                                        <td>{{$report->patient->patient_name ?? '-'}}</td>
                                                        <td>{{$report->guardian_name}}</td>
                                                        <td>{{$report->patient->gender ?? '-' }}</td>
                                                        <td>{{\Carbon\Carbon::parse($report->death_date)->format('d/m/Y h:i A') }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-patient_name="{{ $report->patient->patient_name ?? '' }}"
                                                                    data-patient_id="{{ $report->patient_id }}"
                                                                    data-case_id="{{ $report->case_reference_id}}"
                                                                    data-death_date="{{ \Carbon\Carbon::parse($report->death_date)->format('d/m/Y')}}"
                                                                    data-guardian_name="{{ $report->guardian_name }}"
                                                                    data-report="{{ $report->attachment_name }}"
                                                                    data-attachment="{{ $report->attachment }}"
                                                                
                                                                    data-id="">
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
      <form action="{{ route('death.delete', $report->id) }}" 
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
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Pagination Links --}}
                                    <div class="mt-3" id="pagination-wrapper">
                                      @php
                                          $currentPage = $deathReports->currentPage();
                                          $lastPage = $deathReports->lastPage();
                                    @endphp

                                    @if ($deathReports->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                    <a href="{{ $deathReports->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                     class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $lastPage; $page++)
                                    @if ($page == $currentPage)
                                    <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                    @else
                                   <a href="{{ $deathReports->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                   class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                    @endif
                                    @endfor

                                   @if ($deathReports->hasMorePages())
                                   <a href="{{ $deathReports->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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
    <x-modals.birth-modal type="add" id="createModal" title="Add Death Record" action="{{ route('death.create') }}"
        :fields="[
            [
                'name' => 'case_reference_id',
                'label' => 'Case ID',
                'type' => 'text',
                'required' => false,
                
            ],
            [
            'name' => 'patient_id',
            'label' => 'Patient ID',
            'type' => 'number',  // integer input
            'required' => true,
            'size' => '3',
            
        ],
        [
            'name' => 'patient_name',
            'label' => 'Patient Name',
             'type' => 'text' ,
            'readonly' => true,
        ],
            ['name' => 'death_date', 'label' => 'Death Date', 'type' => 'date', 'required' => true, 'size' => '4'],
           
            ['name' => 'guardian_name', 'label' => 'Guardian Name ', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'attachment_name',
                'label' => 'Report',
                'type' => 'text',
                'required' => false,
                'size' => '6',
            ],
         ['name' => 'attachment', 'label' => 'Attachment', 'type' => 'file', 'required' => false, 'size' => '6',],

        ]" :columns="3" />
    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Death Name"
        action="{{ route('tpamanagement.update') }}" :fields="[
            ['name' => 'id', 'type' => 'hidden', 'required' => true],

              [
                'name' => 'case_id',
                'label' => 'Case ID',
                'type' => 'text',
                'required' => true,
                
            ],
             [
            'name' => 'patient_id',
            'label' => 'Patient ID',
            'type' => 'number',  // integer input
            'required' => true,
            'size' => '3',
            
        ],
        [
            'name' => 'patient_name',
            'label' => 'Patient Name',
             'type' => 'text' ,
            'readonly' => true,
        ],
            ['name' => 'death_date', 'label' => 'Death Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'guardian_name', 'label' => 'Guardian Name ', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'report',
                'label' => 'Report',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
         ['name' => 'attachment', 'label' => 'Attachment', 'type' => 'file', 'required' => false, 'size' => '6',],
           
        ]" :columns="3" />

       <script>
       const patientIdField = document.getElementById("patient_id")
patientIdField.addEventListener("input", function() {
    const patientId = this.value.trim();
    const patientNameField = document.getElementById("patient_name");

    // Clear patient name if input is empty
    if (!patientId) {
        patientNameField.value = '';
        return;
    }

    // Call API
    const baseUrl = "{{ route('death.patient', ['id' => 'ID']) }}";
                const finalUrl = baseUrl.replace('ID', patientId);
    fetch(finalUrl)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                patientNameField.value = data.patient.patient_name; // Fill patient_name
            } else {
                patientNameField.value = ''; // Clear if not found
                console.log(data.message);
            }
        })
        .catch(err => {
            patientNameField.value = '';
            console.error("API Error:", err);
        });
});



</script>



@endsection()