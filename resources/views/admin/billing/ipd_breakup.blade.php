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
                                <td class="text-end">{{ number_format($breakup['bed_charges'], 2) }}</td>
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
                                                @elseif($charge['type'] == 'ipd')
                                                    <span class="badge bg-info">IPD</span>
                                                @elseif($charge['type'] == 'pathology')
                                                    <span class="badge bg-success">Pathology</span>
                                                @elseif($charge['type'] == 'radiology')
                                                    <span class="badge bg-warning">Radiology</span>
                                                @elseif($charge['type'] == 'doctor_visit')
                                                    <span class="badge bg-danger">Doctor</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($charge['type'] == 'bed')
                                                    {{ $charge['description'] }}
                                                    @if(isset($charge['period_start']) && isset($charge['period_end']))
                                                        <br><small class="text-muted">Period: {{ \Carbon\Carbon::parse($charge['period_start'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($charge['period_end'])->format('d/m/Y') }}</small>
                                                    @endif
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
                                                @endif
                                            </td>
                                            <td>
                                                @if($charge['type'] == 'bed')
                                                    <small>
                                                        Bed: {{ $charge['bed'] ?? 'N/A' }}<br>
                                                        Rate: ₹{{ number_format($charge['rate'] ?? 0, 2) }}/day<br>
                                                        Days: {{ $charge['days'] ?? 1 }}
                                                    </small>
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

        <!-- Payment Summary -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Payment Summary</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6 class="text-success">Total Payments</h6>
                                <h3 class="text-success mb-0">₹ {{ number_format($breakup['total_payments'], 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-body">
                                <h6 class="text-danger">Outstanding Amount</h6>
                                <h3 class="text-danger mb-0">₹ {{ number_format($breakup['outstanding'], 2) }}</h3>
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
