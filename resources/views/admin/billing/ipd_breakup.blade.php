<div class="row">
    <div class="col-12">
        <!-- Patient Information -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i> Patient Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>IPD Number:</strong> {{ $ipd->ipd_no }}
                    </div>
                    <div class="col-md-3">
                        <strong>Patient Name:</strong> {{ $ipd->patient->patient_name ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Phone:</strong> {{ $ipd->patient->mobileno ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Admission Date:</strong> {{ \Carbon\Carbon::parse($ipd->date)->format('d/m/Y') }}
                    </div>
                </div>
                @if($ipd->doctor)
                <div class="row mt-2">
                    <div class="col-md-3">
                        <strong>Consultant Doctor:</strong> {{ $ipd->doctor->name ?? 'N/A' }} {{ $ipd->doctor->surname ?? '' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Bed Group:</strong> {{ $ipd->bedGroup->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Bed:</strong> {{ $ipd->bedDetail->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Status:</strong> 
                        <span class="badge {{ $ipd->discharged == 'yes' ? 'bg-danger' : 'bg-success' }}">
                            {{ $ipd->discharged == 'yes' ? 'Discharged' : 'Admitted' }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Charges Breakdown -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i> Charges Breakdown</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-bed me-2 text-primary"></i> Bed Charges</td>
                                <td class="text-end">{{ number_format($breakup['bed_charges'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-box me-2 text-secondary"></i> Package Charges</td>
                                <td class="text-end">{{ number_format($breakup['package_charges'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-file-invoice me-2 text-info"></i> IPD Charges</td>
                                <td class="text-end">{{ number_format($breakup['ipd_charges'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-flask me-2 text-success"></i> Pathology Charges</td>
                                <td class="text-end">{{ number_format($breakup['pathology_charges'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-microscope me-2 text-warning"></i> Radiology Charges</td>
                                <td class="text-end">{{ number_format($breakup['radiology_charges'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-doctor me-2 text-danger"></i> Doctor Visit Charges</td>
                                <td class="text-end">{{ number_format($breakup['doctor_visit_charges'], 2) }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Total Charges</strong></td>
                                <td class="text-end"><strong>{{ number_format($breakup['total_charges'], 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detailed Date-wise Breakdown -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Detailed Date-wise Breakdown</h6>
            </div>
            <div class="card-body">
                @if(isset($detailedBreakup) && $detailedBreakup['grouped_by_date']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="12%">Date</th>
                                    <th width="8%">Type</th>
                                    <th width="50%">Description</th>
                                    <th width="15%">Details</th>
                                    <th width="15%" class="text-end">Amount (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detailedBreakup['grouped_by_date'] as $date => $charges)
                                    @php
                                        $dateTotal = $charges->sum('amount');
                                        $dateFormatted = \Carbon\Carbon::parse($date)->format('d/m/Y');
                                    @endphp
                                    <tr class="table-info">
                                        <td colspan="4"><strong>{{ $dateFormatted }}</strong></td>
                                        <td class="text-end"><strong>₹ {{ number_format($dateTotal, 2) }}</strong></td>
                                    </tr>
                                    @foreach($charges as $charge)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($charge['date'])->format('H:i') }}</td>
                                            <td>
                                                @if($charge['type'] == 'bed')
                                                    <span class="badge bg-primary">Bed</span>
                                                @elseif($charge['type'] == 'package')
                                                    <span class="badge bg-secondary">Package</span>
                                                @elseif($charge['type'] == 'ipd')
                                                    <span class="badge bg-info">IPD</span>
                                                @elseif($charge['type'] == 'pathology')
                                                    <span class="badge bg-success">Pathology</span>
                                                @elseif($charge['type'] == 'radiology')
                                                    <span class="badge bg-warning">Radiology</span>
                                                @elseif($charge['type'] == 'doctor_visit')
                                                    <span class="badge bg-danger">Doctor</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ $charge['type'] ?? '-' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($charge['type'] == 'bed')
                                                    {{ $charge['description'] }}
                                                    @if(isset($charge['period_start']) && isset($charge['period_end']))
                                                        <br><small class="text-muted">Period: {{ \Carbon\Carbon::parse($charge['period_start'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($charge['period_end'])->format('d/m/Y') }}</small>
                                                    @endif
                                                @elseif($charge['type'] == 'package')
                                                    {{ $charge['description'] ?? ('Package - ' . ($charge['package_name'] ?? 'N/A')) }}
                                                @elseif($charge['type'] == 'ipd')
                                                    {{ $charge['description'] }}
                                                    @if(isset($charge['qty']) && $charge['qty'] > 1)
                                                        <br><small class="text-muted">Qty: {{ $charge['qty'] }}</small>
                                                    @endif
                                                @elseif($charge['type'] == 'pathology')
                                                    {{ $charge['test_name'] ?? $charge['description'] }}
                                                @elseif($charge['type'] == 'radiology')
                                                    {{ $charge['test_name'] ?? $charge['description'] }}
                                                @elseif($charge['type'] == 'doctor_visit')
                                                    {{ $charge['description'] }}
                                                @else
                                                    {{ $charge['description'] ?? '-' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($charge['type'] == 'bed')
                                                    <small>
                                                        Bed: {{ $charge['bed'] ?? 'N/A' }}<br>
                                                        Rate: ₹{{ number_format($charge['rate'] ?? 0, 2) }}/day<br>
                                                        Days: {{ $charge['days'] ?? 1 }}
                                                    </small>
                                                @elseif($charge['type'] == 'package')
                                                    <small>{{ $charge['package_name'] ?? 'Package' }}</small>
                                                @elseif($charge['type'] == 'ipd')
                                                    <small>
                                                        Category: {{ $charge['category'] ?? 'N/A' }}<br>
                                                        @if(isset($charge['qty']) && $charge['qty'] > 1)
                                                            Qty: {{ $charge['qty'] }}
                                                        @endif
                                                    </small>
                                                @elseif($charge['type'] == 'doctor_visit')
                                                    <small>
                                                        Doctor: {{ $charge['doctor'] ?? 'N/A' }}<br>
                                                        Charge: {{ $charge['charge_name'] ?? 'N/A' }}
                                                    </small>
                                                @else
                                                    <small>-</small>
                                                @endif
                                            </td>
                                            <td class="text-end">{{ number_format($charge['amount'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Grand Total</strong></td>
                                    <td class="text-end"><strong>₹ {{ number_format($breakup['total_charges'], 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>No detailed charges found for this period.
                    </div>
                @endif
            </div>
        </div>

        <!-- Discount & approval amounts -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-percent me-2"></i> Discount &amp; Insurance Approval Amounts</h6>
            </div>
            <div class="card-body">
                <form id="discountForm">
                    @csrf
                    <input type="hidden" name="ipd_id" value="{{ $ipd->id }}">

                    @if(!empty($isInsuranceIpd))
                        <p class="text-muted small mb-3 mb-md-2">
                            <strong>Approval bill (before TPA):</strong> set MOU discount and initial approval, then export Approval Bill.
                            <br>
                            <strong>Final bill (after TPA response):</strong> update only <strong>Final Approval Amount</strong> and <strong>Hospital Discount</strong> (if any), save, then export Final Bill.
                        </p>

                        <div class="border rounded p-3 mb-3 bg-light">
                            <h6 class="small text-uppercase text-muted mb-3">For approval bill (send with discharge papers)</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="mou_discount" class="form-label">MOU Discount (₹) <small class="text-muted">TPA/Insurance</small></label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="mou_discount" name="mou_discount" placeholder="0.00" value="{{ number_format((float)($ipd->mou_discount ?? 0), 2, '.', '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="initial_approval_amount" class="form-label">Initial Approval Amount (₹)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="initial_approval_amount" name="initial_approval_amount" placeholder="0.00" value="{{ number_format((float)($ipd->initial_approval_amount ?? 0), 2, '.', '') }}">
                                    <small class="text-muted">Pre-auth / initial authorization</small>
                                </div>
                            </div>
                        </div>

                        <div class="border rounded p-3 mb-3 border-success">
                            <h6 class="small text-uppercase text-success mb-3">For final bill (after insurer approves)</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="final_approval_amount" class="form-label">Final Approval Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="final_approval_amount" name="final_approval_amount" placeholder="0.00" value="{{ number_format((float)($ipd->final_approval_amount ?? 0), 2, '.', '') }}">
                                    <small class="text-muted">Amount authorized by insurer for this admission</small>
                                </div>
                                <div class="col-md-4">
                                    <label for="special_discount" class="form-label">Hospital Discount (₹)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="special_discount" name="special_discount" placeholder="0.00" value="{{ number_format((float)($ipd->special_discount ?? 0), 2, '.', '') }}">
                                    <small class="text-muted">Optional concession from hospital</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted small mb-3">Discounts apply on the final bill after discharge.</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="mou_discount" class="form-label">MOU Discount (₹)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="mou_discount" name="mou_discount" placeholder="0.00" value="{{ number_format((float)($ipd->mou_discount ?? 0), 2, '.', '') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="special_discount" class="form-label">Special / Hospital Discount (₹)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="special_discount" name="special_discount" placeholder="0.00" value="{{ number_format((float)($ipd->special_discount ?? 0), 2, '.', '') }}">
                            </div>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary" id="discountSaveBtn">
                                <i class="fas fa-save me-1"></i> Save billing amounts
                            </button>
                        </div>
                    </div>
                </form>
                <div id="discountMessage" class="mt-2 small" style="display: none;"></div>
            </div>
        </div>

        @if(!empty($isInsuranceIpd))
        <div class="card mb-3 border-primary">
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0"><i class="fas fa-file-medical me-2"></i> Insurance Approval Bill</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="exportApprovalBillBtn" data-ipd-id="{{ $ipd->id }}">
                    <i class="fas fa-file-pdf me-1"></i> Export Approval Bill
                </button>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Enter <strong>Initial Approval Amount</strong> and <strong>MOU Discount</strong> above before exporting.
                </p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block">Grand Total</small>
                            <strong>₹ {{ number_format($grandTotal ?? 0, 2) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block">MOU Discount</small>
                            <strong>₹ {{ number_format($mouDiscountAmount ?? 0, 2) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block">Initial Approval Amount</small>
                            <strong class="text-primary" id="previewInitialApproval">₹ {{ number_format($initialApprovalAmount ?? 0, 2) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 h-100 bg-light">
                            <small class="text-muted d-block">Request for Further Approval</small>
                            <strong class="text-danger" id="previewRequestFurtherApproval">₹ {{ number_format($requestFurtherApproval ?? 0, 2) }}</strong>
                        </div>
                    </div>
                </div>
                <p class="small text-muted mt-2 mb-0">Formula: Grand Total − MOU Discount − Initial Approval Amount</p>
            </div>
        </div>

        <div class="card mb-3 border-success">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Insurance Final Bill Preview</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    After insurer response: enter <strong>Final Approval Amount</strong> and <strong>Hospital Discount</strong> (if applicable), click <strong>Save billing amounts</strong>, then use <strong>Export Final Bill</strong>.
                    Due on A/C insurer = Final Approval Amount. Hospital discount appears on the final bill when entered.
                </p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block">Total Bill Amount</small>
                            <strong>₹ {{ number_format($grandTotal ?? 0, 2) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block">Final Approval Amount</small>
                            <strong class="text-primary" id="previewFinalApproval">₹ {{ number_format($finalApprovalAmount ?? 0, 2) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block">Hospital Discount</small>
                            <strong id="previewHospitalDiscount">₹ {{ number_format((float)($ipd->special_discount ?? 0), 2) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 h-100 bg-light">
                            <small class="text-muted d-block">Balance Amount</small>
                            <strong class="text-danger" id="previewInsuranceBalance">₹ {{ number_format($insuranceBalanceAmount ?? 0, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- DUE ON ACCOUNT OF PATIENT PARTY (Under Doctor - Final Bill) -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user-doctor me-2"></i> DUE ON ACCOUNT OF PATIENT PARTY</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Under doctor at admission. This amount is deducted from outstanding and shown on the final bill.</p>
                <form id="duePatientPartyForm" class="row g-3">
                    @csrf
                    <input type="hidden" name="ipd_id" value="{{ $ipd->id }}">
                    <div class="col-md-4">
                        <label for="due_patient_party_doctor_id" class="form-label">Doctor</label>
                        <select class="form-select" id="due_patient_party_doctor_id" name="due_patient_party_doctor_id">
                            <option value="">-- Select Doctor --</option>
                            @foreach($doctors ?? [] as $doc)
                                <option value="{{ $doc->id }}" {{ (isset($ipd->due_patient_party_doctor_id) && $ipd->due_patient_party_doctor_id == $doc->id) ? 'selected' : '' }}>
                                    {{ $doc->name ?? '' }} {{ $doc->surname ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="due_patient_party_receipt_type" class="form-label">Receipt Type</label>
                        <select class="form-select" id="due_patient_party_receipt_type" name="due_patient_party_receipt_type">
                            <option value="">-- Select Type --</option>
                            <option value="Current" {{ (isset($ipd->due_patient_party_receipt_type) && $ipd->due_patient_party_receipt_type == 'Current') ? 'selected' : '' }}>Current</option>
                            <option value="Patient Due" {{ (isset($ipd->due_patient_party_receipt_type) && $ipd->due_patient_party_receipt_type == 'Patient Due') ? 'selected' : '' }}>Patient Due</option>
                            <option value="Corporate Due" {{ (isset($ipd->due_patient_party_receipt_type) && $ipd->due_patient_party_receipt_type == 'Corporate Due') ? 'selected' : '' }}>Corporate Due</option>
                            <option value="In Admissible" {{ (isset($ipd->due_patient_party_receipt_type) && $ipd->due_patient_party_receipt_type == 'In Admissible') ? 'selected' : '' }}>In Admissible</option>
                            <option value="Booking" {{ (isset($ipd->due_patient_party_receipt_type) && $ipd->due_patient_party_receipt_type == 'Booking') ? 'selected' : '' }}>Booking</option>
                            <option value="Refund" {{ (isset($ipd->due_patient_party_receipt_type) && $ipd->due_patient_party_receipt_type == 'Refund') ? 'selected' : '' }}>Refund</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="due_patient_party_amount" class="form-label">Amount (₹)</label>
                        @php
                            $savedDue = (float) ($ipd->due_patient_party_amount ?? 0);
                            $outstanding = (float) ($breakup['outstanding'] ?? 0);
                            $initialDue = $savedDue > 0 ? $savedDue : $outstanding;
                        @endphp
                        <input type="number" step="0.01" min="0" class="form-control" id="due_patient_party_amount" name="due_patient_party_amount" placeholder="₹ {{ number_format($outstanding, 2) }}" value="{{ number_format($initialDue, 2, '.', '') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" id="duePatientPartySaveBtn">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                    </div>
                </form>
                <div id="duePatientPartyMessage" class="mt-2 small" style="display: none;"></div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="card mb-3" id="paymentSummaryCard" data-outstanding="{{ $breakup['outstanding'] }}" data-due-patient-party="{{ $breakup['due_patient_party_amount'] ?? 0 }}">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Payment Summary</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-success h-100">
                            <div class="card-body">
                                <h6 class="text-success">Total Payments</h6>
                                <h4 class="text-success mb-0">₹ {{ number_format($breakup['total_payments'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-warning h-100">
                            <div class="card-body">
                                <h6 class="text-warning">Outstanding (before discount)</h6>
                                <h4 class="text-warning mb-0">₹ {{ number_format($breakup['outstanding'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="card border-info h-100">
                            <div class="card-body">
                                <h6 class="text-info">Total Discount</h6>
                                <h4 class="text-info mb-0" id="summaryTotalDiscount">₹ {{ number_format($breakup['total_discount'] ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="card border-secondary h-100">
                            <div class="card-body">
                                <h6 class="text-secondary">Due (Patient Party)</h6>
                                <h4 class="text-secondary mb-0" id="summaryDuePatientParty">₹ {{ number_format($breakup['due_patient_party_amount'] ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="card border-danger h-100">
                            <div class="card-body">
                                <h6 class="text-danger">Net Balance (Due)</h6>
                                <h4 class="text-danger mb-0 fw-bold" id="summaryNetBalance">₹ {{ number_format($breakup['net_balance'] ?? $breakup['outstanding'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TPA Section (Hidden for now, but kept for future use) -->
        <div class="card mb-3" style="display: none;" id="tpaSection">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-building me-2"></i> TPA Charges</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">TPA charges will be displayed here in future updates.</p>
            </div>
        </div>
    </div>
</div>
