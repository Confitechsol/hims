@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-eye me-2"></i>Radiology Test Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="{{ route('radiology.test.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('radiology.test.edit', $test->id) }}" class="btn btn-warning text-white me-2">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                            <button class="btn btn-primary" onclick="window.print()">
                                <i class="ti ti-printer me-1"></i>Print
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Test Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Test Name:</th>
                                    <td>{{ $test->test_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Short Name:</th>
                                    <td>{{ $test->short_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Test Type:</th>
                                    <td>{{ $test->test_type ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $test->radiologyCategory->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Sub Category:</th>
                                    <td>{{ $test->sub_category ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Report Days:</th>
                                    <td>{{ $test->report_days ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3">Charge Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Standard Charge IPD (INR):</th>
                                    <td class="fw-bold">₹{{ number_format($test->standard_charge_ipd ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Standard Charge OPD (INR):</th>
                                    <td class="fw-bold">₹{{ number_format($test->standard_charge_opd ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($groupedTpaCharges && $groupedTpaCharges->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">
                                <i class="fas fa-building me-2"></i>TPA Charges
                            </h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>TPA Organization</th>
                                                    <th>Standard Charge IPD (INR)</th>
                                                    <th>TPA Charge IPD (INR)</th>
                                                    <th>Standard Charge OPD (INR)</th>
                                                    <th>TPA Charge OPD (INR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($groupedTpaCharges as $orgId => $tpaData)
                                                    @php
                                                        $organisation = $tpaData['organisation'];
                                                        $ipdCharge = $tpaData['ipd_charge'];
                                                        $opdCharge = $tpaData['opd_charge'];
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $organisation->organisation_name ?? '-' }}</strong>
                                                            <br>
                                                            <small class="text-muted">Code: {{ $organisation->code ?? '-' }}</small>
                                                        </td>
                                                        <td>₹{{ number_format($test->standard_charge_ipd ?? 0, 2) }}</td>
                                                        <td class="fw-bold">
                                                            @if($ipdCharge)
                                                                ₹{{ number_format($ipdCharge->org_charge ?? 0, 2) }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>₹{{ number_format($test->standard_charge_opd ?? 0, 2) }}</td>
                                                        <td class="fw-bold">
                                                            @if($opdCharge)
                                                                ₹{{ number_format($opdCharge->org_charge ?? 0, 2) }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-1">
                                                                @if($ipdCharge)
                                                                    <button
                                                                        class="btn btn-sm btn-soft-success rounded-pill edit-tpa-charge-btn"
                                                                        data-id="{{ $ipdCharge->id }}"
                                                                        data-org_charge="{{ $ipdCharge->org_charge }}"
                                                                        data-org_name="{{ $organisation->organisation_name ?? 'TPA' }}"
                                                                        data-charge-type="IPD"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editTpaChargeModal"
                                                                        title="Edit IPD Charge">
                                                                        <i class="ti ti-pencil"></i> IPD
                                                                    </button>
                                                                @endif
                                                                @if($opdCharge)
                                                                    <button
                                                                        class="btn btn-sm btn-soft-info rounded-pill edit-tpa-charge-btn"
                                                                        data-id="{{ $opdCharge->id }}"
                                                                        data-org_charge="{{ $opdCharge->org_charge }}"
                                                                        data-org_name="{{ $organisation->organisation_name ?? 'TPA' }}"
                                                                        data-charge-type="OPD"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editTpaChargeModal"
                                                                        title="Edit OPD Charge">
                                                                        <i class="ti ti-pencil"></i> OPD
                                                                    </button>
                                                                @endif
                                                                @if(!$ipdCharge && !$opdCharge)
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                No TPA charges configured for this test. Standard charges (IPD/OPD) will be used for all TPAs.
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit TPA Charge Modal -->
    <div class="modal fade" id="editTpaChargeModal" tabindex="-1" aria-labelledby="editTpaChargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTpaChargeModalLabel">Edit TPA Charge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('radiology.test.update-tpa-charge') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="tpa_charge_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">TPA Organization</label>
                            <input type="text" class="form-control" id="tpa_org_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Charge Type</label>
                            <input type="text" class="form-control" id="tpa_charge_type_display" readonly>
                            <input type="hidden" name="charge_type" id="tpa_charge_type">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Standard Charge (INR)</label>
                            <input type="text" class="form-control" id="tpa_standard_charge" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TPA Charge (INR) <span class="text-danger">*</span></label>
                            <input type="number" name="org_charge" id="tpa_org_charge" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update TPA Charge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle edit TPA charge button click
            document.querySelectorAll('.edit-tpa-charge-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const orgCharge = this.getAttribute('data-org_charge');
                    const orgName = this.getAttribute('data-org_name');
                    const chargeType = this.getAttribute('data-charge-type');
                    
                    document.getElementById('tpa_charge_id').value = id;
                    document.getElementById('tpa_org_charge').value = orgCharge;
                    document.getElementById('tpa_org_name').value = orgName;
                    document.getElementById('tpa_charge_type').value = chargeType;
                    document.getElementById('tpa_charge_type_display').value = chargeType;
                    
                    // Set standard charge based on type
                    const standardCharge = chargeType === 'IPD' 
                        ? {{ $test->standard_charge_ipd ?? 0 }}
                        : {{ $test->standard_charge_opd ?? 0 }};
                    document.getElementById('tpa_standard_charge').value = '₹' + standardCharge.toFixed(2);
                });
            });
        });
    </script>
@endsection
