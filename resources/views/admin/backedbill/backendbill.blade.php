@extends('layouts.adminLayout')


@section('content')
    <!-- ========================
        Start Page Content
    ========================= -->

    {{-- <div class="page-wrapper"> --}}

        <style>
            .modal-backdrop.show:nth-of-type(2) {
                z-index: 1060;
                /* higher backdrop for nested modal */
            }

            #new_patient {
                z-index: 1070;
                /* ensure new modal is above the first */
            }
        </style>

        <!-- Start Content -->
        <div class="content pb-0">


            <!-- row start -->
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                                <div>
                                    <h4 class="fw-bold mb-0">Backend Bill</h4>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <div class="text-end d-flex">
                                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                            data-bs-toggle="modal" data-bs-target="#add_appointment"><i
                                                class="ti ti-plus me-1"></i>Add
                                            Backend Bill</a>
                                    </div>
                                    <!-- First Modal -->
                                    <div class="modal fade" id="add_appointment" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('backend-store') }}" id="backendBillForm">
                                                    @csrf
                                                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <div class="row w-100 align-items-center">
                                                            <div class="col-md-4">
                                                                <a href="javascript:void(0);"
                                                                   class="btn btn-primary"
                                                                   id="newPatientBtn">
                                                                    <i class="ti ti-plus me-1"></i>New Patient
                                                                </a>
                                                                

                                                            </div>
                                                            
                                                            <!-- IPD Patient Search -->
                                                            <div class="col-md-4 position-relative">
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-white">
                                                                        <i class="ti ti-search"></i>
                                                                    </span>
                                                            
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        id="ipd_patient_search"
                                                                        placeholder="Search IPD Patient"
                                                                        autocomplete="off">
                                                                </div>
                                                            
                                                                <!-- Search Results -->
                                                                <div id="ipd_patient_results"
                                                                     class="list-group position-absolute w-100"
                                                                     style="z-index: 2000; display:none;">
                                                                </div>
                                                            </div>

                                                             <!-- OPD Patient Search -->
                                                             <div class="col-md-4 position-relative">
                                                             
                                                                 <div class="input-group">
                                                             
                                                                     <span class="input-group-text bg-white">
                                                                         <i class="ti ti-search"></i>
                                                                     </span>
                                                             
                                                                     <input
                                                                         type="text"
                                                                         class="form-control"
                                                                         id="opd_patient_search"
                                                                         placeholder="Search OPD Patient"
                                                                         autocomplete="off"
                                                                     >
                                                             
                                                                 </div>
                                                             
                                                                 <!-- OPD Search Results -->
                                                                 <div
                                                                     id="opd_patient_results"
                                                                     class="list-group position-absolute w-100"
                                                                     style="
                                                                         z-index: 2000;
                                                                         display: none;
                                                                         max-height: 300px;
                                                                         overflow-y: auto;
                                                                     "
                                                                 >
                                                                 </div>
                                                             
                                                             </div>
                                                             
                                                            {{-- <div class="col-md-1 text-end">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div> --}}
                                                        </div>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row align-items-center gy-3">


                                                           <div class="col-md-3">
                                                                <label for="patien_name" class="form-label">PatientName</label>
                                                                <input type="text" name="patient_name" id="patients_name" class="form-control">
                                                                <input type="hidden" name="patient_id" id="patient_id">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="age" class="form-label">Age</label>
                                                                <input type="text" name="age" id="age" class="form-control">
                                                            </div>

                                                             {{-- Gender --}}
                                                             <div class="col-md-3">
                                                                 <label for="gender" class="form-label">Gender*</label>
                                                                 <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                                                     <option value="">Select</option>
                                                                     <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                                                     </option>
                                                                     <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                                                                     </option>
                                                                     <option value="Others" {{ old('gender') == 'Others' ? 'selected' : '' }}>Others
                                                                     </option>
                                                                 </select>
                                                                 @error('gender')
                                                                     <div class="invalid-feedback">{{ $message }}
                                                                     </div>
                                                                 @enderror
                                                             </div>
                                                            
                                                            <div class="col-md-3">
                                                                <label for="doctor" class="form-label">Doctor</label>
                                                            
                                                                <input type="text"
                                                                       name="doctor_name"
                                                                       class="form-control"
                                                                       id="doctor"
                                                                       placeholder="Doctor"
                                                                       readonly>
                                                                
                                                                <input type="hidden"
                                                                       name="doctor_id"
                                                                       id="doctor_id">

                                                            </div>


                                                            <div class="col-md-3">
                                                                <label for="address" class="form-label">Address</label>
                                                                <input type="text" name="address" id="address" class="form-control">
                                                            </div>


                                                            <div class="col-md-3">
                                                                <label for="datetimepicker" class="form-label">Date</label>
                                                                <input type="date" id="datetimepicker" name="date" class="form-control" required>
                                                            </div>
                                                           <div class="col-md-3">
                                                               <label class="form-label">Patient Type</label>
                                                           
                                                               <select class="form-select" name="patienttype" id="patienttype">
                                                                   <option value="">Select Patient Type</option>
                                                                   <option value="Opd Patient">Opd Patient</option>
                                                                   <option value="Ipd Patient">Ipd Patient</option>
                                                                   <option value="New Patient">New Patient</option>
                                                               </select>
                                                           </div>

                                                            <div class="col-md-3">
                                                                <label for="discount_percentage" class="form-label">Discount Percentage</label>
                                                                <input type="text" id="discount_percentage" name="discount_percentage" class="form-control">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="adjustment_type" class="form-label">Total Adjustment</label>
                                                                <select name="adjustment_type" id="adjustment_type" class="form-select">
                                                                    <option value="add">Add</option>
                                                                    <option value="discount">Discount</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="adjustment_amount" class="form-label">Adjustment Amount</label>
                                                                <input type="number" name="adjustment_amount" id="adjustment_amount" class="form-control" min="0" step="0.01" value="0">
                                                            </div>

                                                            <div id="bill-container">
                                                                <div class="row bill-row mb-2">
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Bill Type</label>
                                                                        <input type="text" name="bill_type[]" class="form-control">
                                                                    </div>
                                                            
                                                                    <div class="col-md-3">
                                                                        <label class="form-label">Amount</label>
                                                                        <input type="text" name="amount[]" class="form-control">
                                                                    </div>
                                                            
                                                                    <div class="col-md-2 d-flex align-items-end">
                                                                        <button type="button" class="btn btn-success add-bill">+</button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-3">
                                                                <div class="col-md-6 offset-md-6">
                                                                    <div class="alert alert-light border mb-0 text-end">
                                                                        <strong>Total Amount: ₹<span id="backend_total_amount">0.00</span></strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save & Print</button>
                                                        <button type="submit" class="btn btn-secondary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                           <!-- Table start -->
                        <div class="table-responsive table-nowrap">
                            <table class="table border" id="tableBody">
                        
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Patient Name</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Patient Type</th>
                                        <th>Doctor Name</th>
                                        <th>Date</th>
                                        <th>Case No</th>
                                        <th>Total Amount</th>
                                        <th>Received Amount</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                        
                                <tbody>
                                    @forelse($bills as $key => $bill)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                        
                                            <td>{{ $bill->patient_name ?? 'N/A' }}</td>
                        
                                            <td>{{ $bill->gender ?? 'N/A' }}</td>
                        
                                            <td>{{ $bill->age ?? 'N/A' }}</td>
                        
                                            <td>{{ $bill->patienttype ?? 'N/A' }}</td>
                        
                                            <td>{{ $bill->doctor_name ?? 'N/A' }}</td>
                        
                                            <td>{{ $bill->date ?? 'N/A' }}</td>
                        
                                            <td>{{ $bill->case_no ?? 'N/A' }}</td>
                        
                                            <td>₹{{ number_format($bill->display_total_amount, 2) }}</td>
                        
                                            <td>₹{{ number_format($bill->received_amount, 2) }}</td>
                        
                                            <td>₹{{ number_format($bill->due_amount, 2) }}</td>
                        
                                            <!-- Action -->
                                            <td>
                                                <div class="d-flex gap-1">
                        
                                                    <!-- Edit -->
                                                    <a href="javascript:void(0);"
                                                       class="btn btn-sm btn-soft-info rounded-pill editBillBtn"
                                                       data-id="{{ $bill->id }}"
                                                                 data-url="{{ route('backend.edit', ['id' => $bill->id]) }}"
                                                       title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                        
                                                    <a href="{{ route('backend.pdf', ['id' => $bill->id]) }}"
                                                       class="btn btn-sm btn-soft-primary rounded-pill"
                                                                 title="Open PDF" target="_blank">
                                                        <i class="ti ti-file-type-pdf"></i>
                                                    </a>
                        
                                                                      
                                                   {{-- Delete --}}
                                                   <form action="{{ route('backend-destroy', ['id' => $bill->id]) }}"
                                                         method="POST"
                                                         class="d-inline"
                                                         onsubmit="return confirm('Are you sure you want to delete this bill?');">
                                           
                                                       @csrf
                                                       @method('DELETE')
                                           
                                                       <button type="submit"
                                                               class="btn btn-sm btn-soft-danger rounded-pill"
                                                               title="Delete">
                                                           <i class="ti ti-trash"></i>
                                                       </button>
                                           
                                                   </form>
                        
                                                </div>
                                            </td>
                                        </tr>
                        
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center text-muted">
                                                No bills found.
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
            <!-- row end -->
        </div>


        <!-- Edit Backend Bill Modal -->
                <div class="modal fade" id="edit_backend_bill" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                
                            <form method="POST" action="{{ route('backend-update') }}" id="editBackendBillForm">
                                @csrf
                                @method('PUT')
                
                                <input type="hidden" name="id" id="edit_bill_id">
                
                                <div class="modal-header"
                                     style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                
                                    <h5 class="modal-title fw-bold">
                                        Edit Backend Bill
                                    </h5>
                
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                    </button>
                                </div>
                
                                <div class="modal-body">
                
                                    <div class="row align-items-center gy-3">
                
                                        <!-- Patient Name -->
                                        <div class="col-md-3">
                                            <label class="form-label">Patient Name</label>
                                            <input type="text"
                                                   name="patient_name"
                                                   id="edit_patient_name"
                                                   class="form-control">
                                        </div>
                
                                        <div class="col-md-3">
                                            <label class="form-label">Total Adjustment</label>
                                            <select name="adjustment_type" id="edit_adjustment_type" class="form-select">
                                                <option value="add">Add</option>
                                                <option value="discount">Discount</option>
                                            </select>
                                        </div>
                
                                        <div class="col-md-3">
                                            <label class="form-label">Adjustment Amount</label>
                                            <input type="number" name="adjustment_amount" id="edit_adjustment_amount" class="form-control" min="0" step="0.01" value="0">
                                        </div>
                
                                        <!-- Age -->
                                        <div class="col-md-3">
                                            <label class="form-label">Age</label>
                                            <input type="text"
                                                   name="age"
                                                   id="edit_age"
                                                   class="form-control">
                                        </div>
                
                                        <!-- Gender -->
                                        <div class="col-md-3">
                                            <label class="form-label">Gender</label>
                
                                            <select name="gender"
                                                    id="edit_gender"
                                                    class="form-control">
                
                                                <option value="">Select</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Others">Others</option>
                
                                            </select>
                                        </div>
                
                                        <!-- Doctor -->
                                        <div class="col-md-3">
                                            <label class="form-label">Doctor</label>
                
                                            <input type="text"
                                                   name="doctor_name"
                                                   id="edit_doctor"
                                                   class="form-control">
                
                                            <input type="hidden"
                                                   name="doctor_id"
                                                   id="edit_doctor_id">
                                        </div>
                
                                        <!-- Address -->
                                        <div class="col-md-3">
                                            <label class="form-label">Address</label>
                
                                            <input type="text"
                                                   name="address"
                                                   id="edit_address"
                                                   class="form-control">
                                        </div>
                
                                        <!-- Payment Type -->
                                        <div class="col-md-3">
                                            <label class="form-label">Payment Type</label>
                
                                            <select name="shift"
                                                    id="edit_shift"
                                                    class="form-select">
                
                                                <option value="">Select Payment Type</option>
                
                                            </select>
                                        </div>
                
                                        <!-- Date -->
                                        <div class="col-md-3">
                                            <label class="form-label">Date</label>
                
                                            <input type="date"
                                                   name="date"
                                                   id="edit_date"
                                                   class="form-control">
                                        </div>
                
                                        <!-- Patient Type -->
                                        <div class="col-md-3">
                                            <label class="form-label">Patient Type</label>
                
                                            <select name="patienttype"
                                                    id="edit_patienttype"
                                                    class="form-select">
                
                                                <option value="">Select Patient Type</option>
                                                <option value="Opd Patient">Opd Patient</option>
                                                <option value="Ipd Patient">Ipd Patient</option>
                                                <option value="New Patient">New Patient</option>
                
                                            </select>
                                        </div>
                
                                        <!-- Discount -->
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Discount Percentage
                                            </label>
                
                                            <input type="text"
                                                   name="discount_percentage"
                                                   id="edit_discount_percentage"
                                                   class="form-control">
                                        </div>
                
                                    </div>
                
                                    <hr>
                
                                    <!-- Bill Items -->
                                    <h5 class="mb-3">Bill Details</h5>
                
                                    <div id="edit-bill-container">
                
                                        <div class="row edit-bill-row mb-2">
                
                                            <div class="col-md-4">
                                                <label class="form-label">Bill Type</label>
                
                                                <input type="text"
                                                       name="bill_type[]"
                                                       class="form-control edit_bill_type">
                                            </div>
                
                                            <div class="col-md-3">
                                                <label class="form-label">Amount</label>
                
                                                <input type="text"
                                                       name="amount[]"
                                                       class="form-control edit_amount">
                                            </div>
                
                                            <div class="col-md-2 d-flex align-items-end">
                
                                                <button type="button"
                                                        class="btn btn-success edit-add-bill">
                                                    +
                                                </button>
                
                                            </div>
                
                                        </div>
                
                                    </div>
                
                                    <div class="alert alert-light border text-end mt-3">
                                        <strong>Total Amount: ₹<span id="edit_backend_total_amount">0.00</span></strong>
                                    </div>
                
                                </div>
                
                                <div class="modal-footer">
                
                                    <button type="submit"
                                            class="btn btn-primary">
                                        Update Bill
                                    </button>
                
                                    <button type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                
                                </div>
                
                            </form>
                
                        </div>
                    </div>
                </div>


      
    

  

     <script>
        $(document).ready(function () {
        
            let opdSearchTimer;
        
            const opdSearchUrl = '{{ route("backend.searchOpdPatient") }}';
        
            // ==========================================
            // OPD PATIENT SEARCH
            // ==========================================
        
            $('#opd_patient_search').on('input', function () {
        
                let search = $(this).val().trim();
        
                clearTimeout(opdSearchTimer);
        
                // Less than 3 characters
                if (search.length < 3) {
        
                    $('#opd_patient_results')
                        .hide()
                        .empty();
        
                    return;
                }
        
                opdSearchTimer = setTimeout(function () {
        
                    $.ajax({
        
                        url: opdSearchUrl,
        
                        type: 'GET',
        
                        data: {
                            search: search
                        },
        
                        dataType: 'json',
        
                        beforeSend: function () {
        
                            $('#opd_patient_results')
                                .show()
                                .html(`
                                    <div class="list-group-item text-muted">
                                        Searching...
                                    </div>
                                `);
                        },
        
                        success: function (response) {
        
                            console.log('OPD Patient Response:', response);
        
                            let patients = response;
        
                            $('#opd_patient_results').empty();
        
                            if (!Array.isArray(patients) || patients.length === 0) {
        
                                $('#opd_patient_results')
                                    .show()
                                    .html(`
                                        <div class="list-group-item text-muted">
                                            No OPD patient found
                                        </div>
                                    `);
        
                                return;
                            }
        
                            $.each(patients, function (index, patient) {
        
                                let item = `
                                    <button
                                        type="button"
                                        class="list-group-item list-group-item-action opd-patient-item"
        
                                        data-id="${patient.id ?? ''}"
                                        data-patient-id="${patient.patient_id ?? ''}"
                                        data-opd-no="${patient.opd_no ?? ''}"
                                        data-name="${patient.patient_name ?? ''}"
                                        data-mobile="${patient.mobile ?? ''}"
                                        data-email="${patient.email ?? ''}"
                                        data-age="${patient.age ?? ''}"
                                        data-gender="${patient.gender ?? ''}"
                                        data-address="${patient.address ?? ''}"
                                        data-doctor-id="${patient.doctor_id ?? ''}"
                                        data-doctor-name="${patient.doctor_name ?? patient.doctor ?? ''}"
                                    >
        
                                        <div class="d-flex justify-content-between align-items-center">
        
                                            <strong>
                                                ${patient.patient_name ?? 'N/A'}
                                            </strong>
        
                                            <small class="text-primary">
                                                OPD: ${patient.opd_no ?? 'N/A'}
                                            </small>
        
                                        </div>
        
                                        <div class="mt-1">
        
                                            <small class="text-muted">
                                                ${patient.mobile ?? ''}
                                            </small>
        
                                            ${
                                                patient.gender
                                                ? `
                                                    <small class="text-muted ms-2">
                                                        ${patient.gender}
                                                    </small>
                                                `
                                                : ''
                                            }
        
                                            ${
                                                patient.age
                                                ? `
                                                    <small class="text-muted ms-2">
                                                        ${patient.age} Years
                                                    </small>
                                                `
                                                : ''
                                            }

                                            ${
            patient.doctor_name || patient.doctor
            ? `
                <small class="text-primary d-block mt-1">
                    Doctor: ${patient.doctor_name ?? patient.doctor}
                </small>
            `
            : ''
        }
        
                                        </div>
        
                                        ${
                                            patient.email
                                            ? `
                                                <small class="text-muted d-block mt-1">
                                                    ${patient.email}
                                                </small>
                                            `
                                            : ''
                                        }
        
                                        ${
                                            patient.address
                                            ? `
                                                <small class="text-muted d-block mt-1">
                                                    ${patient.address}
                                                </small>
                                            `
                                            : ''
                                        }
        
                                    </button>
                                `;
        
                                $('#opd_patient_results').append(item);
        
                            });
        
                            $('#opd_patient_results').show();
        
                        },
        
                        error: function (xhr) {
        
                            console.error(
                                'OPD Search Error:',
                                xhr.status,
                                xhr.responseText
                            );
        
                            $('#opd_patient_results')
                                .show()
                                .html(`
                                    <div class="list-group-item text-danger">
                                        Unable to search OPD patient
                                    </div>
                                `);
                        }
        
                    });
        
                }, 300);
        
            });
        
        
            // ==========================================
            // SELECT OPD PATIENT
            // ==========================================
        
            $(document).on('click', '.opd-patient-item', function () {
        
                let patient = $(this);
        
                let patientId = patient.data('patient-id');
                let opdNo = patient.data('opd-no');
                let patientName = patient.data('name');
                let age = patient.data('age');
                let gender = patient.data('gender');
                let address = patient.data('address');

                // Doctor from JSON
    let doctorId = patient.data('doctor-id');
    let doctorName = patient.data('doctor-name');
        
        
                // ==========================================
                // FILL SEARCH BOX
                // ==========================================
        
                $('#opd_patient_search').val(patientName);
        
        
                // ==========================================
                // FILL PATIENT DETAILS
                // ==========================================
        
                $('#patients_name').val(patientName);
        
                $('#age').val(age);
        
                $('#address').val(address);
        
                $('select[name="gender"]').val(gender);

                 
        
        
                // ==========================================
                // PATIENT TYPE
                // ==========================================
        
                $('#patienttype')
                    .val('Opd Patient');

                $('#patient_id').val(patientId);
        
        
                // ==========================================
                // STORE OPD DETAILS
                // ==========================================
        
                $('#opd_patient_id').val(patientId);
        
                $('#selected_opd_no').val(opdNo);
                

                // Doctor ID + Name
    $('#doctor_id').val(doctorId);
    $('#doctor').val(doctorName);

        
                // ==========================================
                // HIDE SEARCH RESULTS
                // ==========================================
        
                $('#opd_patient_results')
                    .hide()
                    .empty();
        
            });
        
        
            // ==========================================
            // CLICK OUTSIDE
            // ==========================================
        
            $(document).on('click', function (e) {
        
                if (!$(e.target).closest(
                    '#opd_patient_search, #opd_patient_results'
                ).length) {
        
                    $('#opd_patient_results')
                        .hide()
                        .empty();
                }
        
            });
        
        
            // ==========================================
            // ESCAPE
            // ==========================================
        
            $('#opd_patient_search').on('keydown', function (e) {
        
                if (e.key === 'Escape') {
        
                    $(this).val('');
        
                    $('#opd_patient_results')
                        .hide()
                        .empty();
        
                }
        
            });
        
        });
</script>



      
   
      
      <script>
       $(document).ready(function () {
       
           $('#search_ipd_patient').on('click', function () {
       
               let search = $('#ipd_patient_search').val().trim();
       
               if (!search) {
                   alert('Please enter patient name');
                   return;
               }
       
               $.ajax({
                   url: '{{ url("/ipd/search-ipd-patient") }}',
                   type: 'GET',
                   data: {
                       search: search
                   },
                   dataType: 'json',
       
                   beforeSend: function () {
                       $('#search_ipd_patient').prop('disabled', true).text('Searching...');
                   },
       
                   success: function (response) {
       
                       console.log('IPD Patient Response:', response);
       
                       /*
                        * Adjust this according to your actual API response.
                        */
                       let patient = response.patient;
       
                       if (!patient) {
                           alert('Patient not found');
       
                           // Clear fields
                           $('#patients_name').val('');
                           $('#age').val('');
                           $('select[name="gender"]').val('');
                           $('#address').val('');
       
                           return;
                       }
       
                       // Fill patient information
                       $('#patients_name').val(patient.patient_name ?? '');
                       $('#age').val(patient.age ?? '');
                       $('select[name="gender"]').val(patient.gender ?? '');
                       $('#address').val(patient.address ?? '');
       
                       // Automatically select patient type
                       $('select[name="case_type"]').val('Ipd Patient');
                   },
       
                   error: function (xhr) {
       
                       console.error('IPD Search Error:', xhr.responseText);
       
                       if (xhr.status === 404) {
                           alert('Patient not found');
                       } else {
                           alert('Unable to search IPD patient.');
                       }
                   },
       
                   complete: function () {
                       $('#search_ipd_patient')
                           .prop('disabled', false)
                           .text('Search');
                   }
               });
       
           });
       
       });
      </script>

   <script>
        $(document).ready(function () {
        
            let searchTimer;
        
            const searchUrl = '{{ route("backend.searchIpdPatient") }}';
        
            // ==========================================
            // IPD PATIENT SEARCH - AFTER 3 CHARACTERS
            // ==========================================
            $('#ipd_patient_search').on('input', function () {
        
                let search = $(this).val().trim();
        
                clearTimeout(searchTimer);
        
                // Less than 3 characters = don't search
                if (search.length < 3) {
                    $('#ipd_patient_results')
                        .hide()
                        .empty();
        
                    return;
                }
        
                searchTimer = setTimeout(function () {
        
                    $.ajax({
                        url: searchUrl,
                        type: 'GET',
                        data: {
                            search: search
                        },
                        dataType: 'json',
        
                        beforeSend: function () {
                            $('#ipd_patient_results')
                                .show()
                                .html(`
                                    <div class="list-group-item text-muted">
                                        Searching...
                                    </div>
                                `);
                        },
        
                        success: function (response) {
        
                            console.log('IPD Patient Response:', response);
        
                            // Laravel returns direct array
                            let patients = response;
        
                            $('#ipd_patient_results').empty();
        
                            if (!Array.isArray(patients) || patients.length === 0) {
        
                                $('#ipd_patient_results')
                                    .show()
                                    .html(`
                                        <div class="list-group-item text-muted">
                                            No IPD patient found
                                        </div>
                                    `);
        
                                return;
                            }
        
                            $.each(patients, function (index, patient) {
        
                                let item = `
    <button type="button"
        class="list-group-item list-group-item-action ipd-patient-item"
        data-id="${patient.id ?? ''}"
        data-patient-id="${patient.patient_id ?? ''}"
        data-ipd-no="${patient.ipd_no ?? ''}"
        data-name="${patient.patient_name ?? ''}"
        data-mobile="${patient.mobile ?? ''}"
        data-age="${patient.age ?? ''}"
        data-gender="${patient.gender ?? ''}"
        data-address="${patient.address ?? ''}"
        data-doctor-id="${patient.doctor_id ?? ''}"
        data-doctor-name="${patient.doctor_name ?? patient.doctor ?? ''}">

        <div class="d-flex justify-content-between align-items-center">

            <strong>
                ${patient.patient_name ?? 'N/A'}
            </strong>

            <small class="text-primary">
                IPD: ${patient.ipd_no ?? 'N/A'}
            </small>

        </div>

        <div class="mt-1">

            <small class="text-muted">
                ${patient.mobile ?? ''}
            </small>

            ${patient.gender ? `
                <small class="text-muted ms-2">
                    ${patient.gender}
                </small>
            ` : ''}

            ${patient.age ? `
                <small class="text-muted ms-2">
                    ${patient.age} Years
                </small>
            ` : ''}

        </div>

        ${patient.doctor_name || patient.doctor ? `
            <small class="text-primary d-block mt-1">
                Doctor: ${patient.doctor_name ?? patient.doctor}
            </small>
        ` : ''}

        ${patient.address ? `
            <small class="text-muted d-block mt-1">
                ${patient.address}
            </small>
        ` : ''}

    </button>
`;

        
                                $('#ipd_patient_results').append(item);
                            });
        
                            $('#ipd_patient_results').show();
                        },
        
                        error: function (xhr) {
        
                            console.error(
                                'IPD Search Error:',
                                xhr.responseText
                            );
        
                            $('#ipd_patient_results')
                                .show()
                                .html(`
                                    <div class="list-group-item text-danger">
                                        Unable to search IPD patient
                                    </div>
                                `);
                        }
                    });
        
                }, 300);
            });
        
        
            // ==========================================
            // SELECT IPD PATIENT
            // ==========================================
           $(document).on('click', '.ipd-patient-item', function () {

    let patient = $(this);

    let patientId  = patient.data('patient-id');
    let ipdNo      = patient.data('ipd-no');
    let patientName = patient.data('name');
    let age        = patient.data('age');
    let gender     = patient.data('gender');
    let address    = patient.data('address');

    // Doctor information
    let doctorId   = patient.data('doctor-id');
    let doctorName = patient.data('doctor-name');

    console.log('Selected IPD Doctor ID:', doctorId);
    console.log('Selected IPD Doctor Name:', doctorName);

    // Patient information
    $('#ipd_patient_search').val(patientName);
    $('#patients_name').val(patientName);
    $('#age').val(age);
    $('#address').val(address);

    $('select[name="gender"]').val(gender);

    // Patient type
    $('#patienttype').val('Ipd Patient');

    // Patient ID
    $('#patient_id').val(patientId);

    // Doctor ID + Doctor Name
    $('#doctor_id').val(doctorId);
    $('#doctor').val(doctorName);

    // IPD information
    $('#ipd_patient_id').val(patientId);
    $('#selected_ipd_no').val(ipdNo);

    // Hide search results
    $('#ipd_patient_results')
        .hide()
        .empty();
});

        
            // ==========================================
            // CLICK OUTSIDE = HIDE RESULTS
            // ==========================================
            $(document).on('click', function (e) {
        
                if (!$(e.target).closest(
                    '#ipd_patient_search, #ipd_patient_results'
                ).length) {
        
                    $('#ipd_patient_results')
                        .hide()
                        .empty();
                }
            });
        
        
            // ==========================================
            // CLEAR IPD SEARCH
            // ==========================================
            $('#ipd_patient_search').on('keydown', function (e) {
        
                if (e.key === 'Escape') {
        
                    $(this).val('');
        
                    $('#ipd_patient_results')
                        .hide()
                        .empty();
        
                    // Unlock patient type if search cleared
                    $('#case_type')
                        .prop('disabled', false)
                        .val('');
        
                    $('#patients_name').val('');
                    $('#age').val('');
                    $('#address').val('');
                    $('select[name="gender"]').val('');
                }
        
            });
        
        });
    </script>


    <script>
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('add-bill')) {

        let row = e.target.closest('.bill-row');
        let newRow = row.cloneNode(true);

        // Clear input values
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Change + button to -
        let button = newRow.querySelector('.add-bill');
        button.classList.remove('btn-success', 'add-bill');
        button.classList.add('btn-danger', 'remove-bill');
        button.innerHTML = '-';

        document.getElementById('bill-container').appendChild(newRow);
    }

    if (e.target.classList.contains('remove-bill')) {
        e.target.closest('.bill-row').remove();
    }
});
    </script>


   <script>
$(document).ready(function () {

    $('#newPatientBtn').on('click', function (e) {

        e.preventDefault();

        const form = document.getElementById('backendBillForm');

        if (!form) {
            console.error('backendBillForm not found');
            return;
        }

        // ==========================================
        // RESET ENTIRE FORM
        // ==========================================

        form.reset();

        // ==========================================
        // CLEAR PATIENT INFORMATION
        // ==========================================

        $('#patient_id').val('');
        $('#patients_name').val('');
        $('#age').val('');
        $('#address').val('');

        // ==========================================
        // CLEAR DOCTOR
        // ==========================================

        $('#doctor_id').val('');
        $('#doctor').val('').prop('readonly', false);

        // ==========================================
        // CLEAR PATIENT SEARCH
        // ==========================================

        $('#ipd_patient_search').val('');
        $('#opd_patient_search').val('');

        // ==========================================
        // CLEAR SEARCH RESULTS
        // ==========================================

        $('#ipd_patient_results')
            .hide()
            .empty();

        $('#opd_patient_results')
            .hide()
            .empty();

        // ==========================================
        // CLEAR SELECT BOXES
        // ==========================================

        $('select[name="gender"]').val('');
        $('#patienttype').val('');
        $('#shift').val('');

        // ==========================================
        // CLEAR DATE
        // ==========================================

        $('#datetimepicker').val('');

        // ==========================================
        // CLEAR DISCOUNT
        // ==========================================

        $('#discount_percentage').val('');

        // ==========================================
        // CLEAR IPD / OPD HIDDEN VALUES
        // ==========================================

        $('#ipd_patient_id').val('');
        $('#opd_patient_id').val('');

        $('#selected_ipd_no').val('');
        $('#selected_opd_no').val('');

        // ==========================================
        // RESET BILL ROWS
        // ==========================================

        const billContainer = $('#bill-container');

        // Remove all extra rows
        billContainer.find('.bill-row').not(':first').remove();

        // Get first row
        const firstRow = billContainer.find('.bill-row:first');

        // Clear first row
        firstRow.find('input').val('');

        // Reset button to +
        const button = firstRow.find('button');

        button
            .removeClass('btn-danger remove-bill')
            .addClass('btn-success add-bill')
            .html('+');

        // ==========================================
        // OPTIONAL: FOCUS PATIENT NAME
        // ==========================================

        setTimeout(function () {
            $('#patients_name').focus();
        }, 100);

    });

});
</script>

<script>
  function updateRowNumbers() {
    document.querySelectorAll('#tableBody .row-number').forEach((cell, index) => {
        cell.textContent = index + 1;
    });
}

// Call this AFTER creating/loading the rows
updateRowNumbers();
</script>


<script>
$(document).ready(function () {

    // ==========================================
    // EDIT BILL BUTTON
    // ==========================================

    $(document).on('click', '.editBillBtn', function (e) {

        e.preventDefault();

        let billId = $(this).data('id');

        console.log('Editing Bill ID:', billId);

        $.ajax({

            url: $(this).data('url'),

            type: "GET",

            dataType: "json",

            beforeSend: function () {

                // Optional loading state
                  $('#editBackendBillForm button[type="submit"]')

                    .prop('disabled', true);

            },

            success: function (response) {

                console.log('Edit Bill Response:', response);

                let bill = response.bill ?? response;

                // ==========================================
                // BASIC INFORMATION
                // ==========================================

                $('#edit_bill_id').val(bill.id ?? '');

                $('#edit_patient_name').val(
                    bill.patient_name ?? ''
                );

                $('#edit_age').val(
                    bill.age ?? ''
                );

                $('#edit_gender').val(
                    bill.gender ?? ''
                );

                $('#edit_doctor').val(
                    bill.doctor_name ?? ''
                );

                $('#edit_doctor_id').val(
                    bill.doctor_id ?? ''
                );

                $('#edit_address').val(
                    bill.address ?? ''
                );

                $('#edit_shift').val(
                    bill.shift ?? ''
                );

                $('#edit_date').val(
                    bill.date ?? ''
                );

                $('#edit_patienttype').val(
                    bill.patienttype ?? ''
                );

                $('#edit_discount_percentage').val(
                    bill.discount_percentage ?? ''
                );

                $('#edit_adjustment_type').val(bill.adjustment_type ?? 'add');
                $('#edit_adjustment_amount').val(bill.adjustment_amount ?? 0);


                // ==========================================
                // BILL ITEMS
                // ==========================================

                let billContainer = $('#edit-bill-container');

                billContainer.empty();

                /*
                 * If backend returns bill_items array
                 */

                let billItems = bill.bill_items ?? [];

                if (billItems.length > 0) {

                    $.each(billItems, function (index, item) {

                        let buttonClass =
                            index === 0
                                ? 'btn-success edit-add-bill'
                                : 'btn-danger edit-remove-bill';

                        let buttonText =
                            index === 0 ? '+' : '-';

                        let row = `
                            <div class="row edit-bill-row mb-2">

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Bill Type
                                    </label>

                                    <input type="text"
                                           name="bill_type[]"
                                           class="form-control edit_bill_type"
                                           value="${item.bill_item ?? item.bill_type ?? ''}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">
                                        Amount
                                    </label>

                                    <input type="text"
                                           name="amount[]"
                                           class="form-control edit_amount"
                                           value="${item.amount ?? ''}">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">

                                    <button type="button"
                                            class="btn ${buttonClass}">
                                        ${buttonText}
                                    </button>

                                </div>

                            </div>
                        `;

                        billContainer.append(row);

                    });

                } else {

                    // If no bill items exist

                    billContainer.html(`
                        <div class="row edit-bill-row mb-2">

                            <div class="col-md-4">
                                <label class="form-label">
                                    Bill Type
                                </label>

                                <input type="text"
                                       name="bill_type[]"
                                       class="form-control edit_bill_type"
                                       value="${bill.bill_type ?? ''}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">
                                    Amount
                                </label>

                                <input type="text"
                                       name="amount[]"
                                       class="form-control edit_amount"
                                       value="${bill.amount ?? ''}">
                            </div>

                            <div class="col-md-2 d-flex align-items-end">

                                <button type="button"
                                        class="btn btn-success edit-add-bill">
                                    +
                                </button>

                            </div>

                        </div>
                    `);

                }

                updateBackendTotal('#edit-bill-container', '#edit_adjustment_type', '#edit_adjustment_amount', '#edit_backend_total_amount');


                // ==========================================
                // OPEN MODAL
                // ==========================================

                $('#edit_backend_bill').modal('show');

            },

            error: function (xhr) {

                console.error(
                    'Edit Bill Error:',
                    xhr.responseText
                );

                alert('Unable to load bill details.');

            },

            complete: function () {

                $('#editBackendBillForm button[type="submit"]')
                    .prop('disabled', false);

            }

        });

    });


    // ==========================================
    // ADD BILL ROW IN EDIT MODAL
    // ==========================================

    $(document).on('click', '.edit-add-bill', function () {

        let row = $(this).closest('.edit-bill-row');

        let newRow = row.clone();

        newRow.find('input').val('');

        let button = newRow.find('button');

        button
            .removeClass('btn-success edit-add-bill')
            .addClass('btn-danger edit-remove-bill')
            .text('-');

        $('#edit-bill-container').append(newRow);

    });


    // ==========================================
    // REMOVE BILL ROW
    // ==========================================

    $(document).on('click', '.edit-remove-bill', function () {

        $(this)
            .closest('.edit-bill-row')
            .remove();

    });

    function updateBackendTotal(container, typeSelector, amountSelector, outputSelector) {
        let subtotal = 0;
        $(container).find('input[name="amount[]"]').each(function () {
            subtotal += parseFloat($(this).val()) || 0;
        });

        const adjustment = parseFloat($(amountSelector).val()) || 0;
        const total = $(typeSelector).val() === 'discount'
            ? Math.max(0, subtotal - adjustment)
            : subtotal + adjustment;

        $(outputSelector).text(total.toFixed(2));
    }

    $(document).on('input change', '#backendBillForm input[name="amount[]"], #adjustment_type, #adjustment_amount', function () {
        updateBackendTotal('#bill-container', '#adjustment_type', '#adjustment_amount', '#backend_total_amount');
    });

    $(document).on('input change', '#editBackendBillForm input[name="amount[]"], #edit_adjustment_type, #edit_adjustment_amount', function () {
        updateBackendTotal('#edit-bill-container', '#edit_adjustment_type', '#edit_adjustment_amount', '#edit_backend_total_amount');
    });

    $(document).on('click', '#add_appointment', function () {
        updateBackendTotal('#bill-container', '#adjustment_type', '#adjustment_amount', '#backend_total_amount');
    });

    $(document).on('click', '.add-bill', function () {
        const row = $(this).closest('.bill-row');
        const newRow = row.clone();
        newRow.find('input').val('');
        newRow.find('button').removeClass('btn-success add-bill').addClass('btn-danger remove-bill').text('-');
        $('#bill-container').append(newRow);
    });

    $(document).on('click', '.remove-bill', function () {
        $(this).closest('.bill-row').remove();
        updateBackendTotal('#bill-container', '#adjustment_type', '#adjustment_amount', '#backend_total_amount');
    });

});
</script>











@endsection