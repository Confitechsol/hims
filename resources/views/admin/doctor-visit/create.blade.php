@extends('layouts.adminLayout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-plus-circle me-2"></i>Doctor Visit</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('doctor-visit.store') }}" method="POST" id="docvisitSection">
                    @csrf
                    
                    <!-- Patient and Bill Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Patient <span class="text-danger">*</span></label>
                            <div class="autocomplete-container">
                                <input type="text" id="patient_search" class="form-control" placeholder="Search by name, ID, or mobile" autocomplete="off" required>
                                <input type="hidden" name="patient_id" id="patient_id" required>
                                <div id="patient_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                            <small class="text-muted">Start typing to see suggestions</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Case Reference</label>
                            <div class="autocomplete-container">
                                <input type="text" id="prescription_search" class="form-control" placeholder="Search prescriptions" autocomplete="off">
                                <input type="hidden" name="case_reference_id" id="case_reference_id">
                                <div id="prescription_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Reference Doctor ({{ count($doctors ?? []) }} found)</label>
                            <select name="doctor_id" id="doctor_id" class="form-select add-select2">
                                <option value="">Select Doctor</option>
                                @if(isset($doctors) && count($doctors) > 0)
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            Dr. {{ $doctor->name }} {{ $doctor->surname }}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No doctors found</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reporting Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date" id="date" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>


                    

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Visit Type <span class="text-danger">*</span></label>
                            <select name="visit_type" class="form-select" required>
                                <option value="">Select</option>
                                <option value="OPD">OPD</option>
                                <option value="IPD">IPD</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Follow-up">Follow-up</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Rate (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="rate" class="form-control" step="0.01" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" step="0.01" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Doctor Pay Type</label>
                            <select name="doctor_pay_type" class="form-select">
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Doctor Pay Amount</label>
                            <input type="number" name="doctor_pay_amount" class="form-control" step="0.01">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Visit Date</label>
                            <input type="date" name="visit_date" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Visit Time</label>
                            <input type="time" name="visit_time" class="form-control" required>
                        </div>
                    </div>

                    <!-- Test Selection Table -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Previous Visit Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="testTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">Doctor Name <span class="text-danger">*</span></th>
                                            <th width="12%">Rate</th>
                                            <th width="18%">No. of visit <span class="text-danger">*</span></th>                                          
                                            <th width="18%">Amount (INR) <span class="text-danger">*</span></th>
                                            <th width="12%">Pay Amount</th>
                                            <th width="12%">Date</th>
                                            <th width="12%">Time</th>
                                            <th width="12%">Entry</th>
                                            <th width="12%">Visit Type</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testTableBody">
                                        <tr class="test-row">
                                            <td>
                                                
                                            </td>
                                            <td>
                                               
                                            </td>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
.autocomplete-container {
    position: relative;
    width: 100%;
}

.autocomplete-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.autocomplete-suggestion {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.autocomplete-suggestion:hover {
    background-color: #f8f9fa;
}

.autocomplete-suggestion.highlighted {
    background-color: #e3f2fd;
}

.suggestion-name {
    font-weight: 500;
    color: #333;
}

.suggestion-details {
    font-size: 0.85em;
    color: #666;
    margin-top: 2px;
}
</style>


@endsection
