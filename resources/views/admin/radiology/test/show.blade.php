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
                                                            @if($ipdCharge || $opdCharge)
                                                                <button type="button" class="btn btn-sm btn-warning text-white" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#editTpaChargeModal"
                                                                        data-org-id="{{ $organisation->id }}"
                                                                        data-org-name="{{ $organisation->organisation_name }}"
                                                                        data-ipd-charge-id="{{ $ipdCharge->id ?? '' }}"
                                                                        data-ipd-charge="{{ $ipdCharge->org_charge ?? '' }}"
                                                                        data-opd-charge-id="{{ $opdCharge->id ?? '' }}"
                                                                        data-opd-charge="{{ $opdCharge->org_charge ?? '' }}"
                                                                        data-standard-charge-ipd="{{ $test->standard_charge_ipd ?? 0 }}"
                                                                        data-standard-charge-opd="{{ $test->standard_charge_opd ?? 0 }}"
                                                                        onclick="editTpaCharge(this)">
                                                                    <i class="ti ti-edit"></i> Edit
                                                                </button>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
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
                <form action="{{ route('radiology.test.updateTpaCharge') }}" method="POST" id="editTpaChargeForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_tpa_charge_id">
                        <input type="hidden" name="charge_type" id="edit_charge_type">
                        
                        <div class="mb-3">
                            <label class="form-label">TPA Organization:</label>
                            <input type="text" class="form-control" id="edit_org_name" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Standard Charge IPD (INR):</label>
                            <input type="text" class="form-control" id="edit_standard_charge_ipd" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">TPA Charge IPD (INR): <span class="text-danger">*</span></label>
                            <input type="number" name="org_charge" id="edit_tpa_charge_ipd" class="form-control" step="0.01" min="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Standard Charge OPD (INR):</label>
                            <input type="text" class="form-control" id="edit_standard_charge_opd" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">TPA Charge OPD (INR): <span class="text-danger">*</span></label>
                            <input type="number" name="org_charge_opd" id="edit_tpa_charge_opd" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editTpaCharge(button) {
            const orgId = button.getAttribute('data-org-id');
            const orgName = button.getAttribute('data-org-name');
            const ipdChargeId = button.getAttribute('data-ipd-charge-id');
            const ipdCharge = button.getAttribute('data-ipd-charge');
            const opdChargeId = button.getAttribute('data-opd-charge-id');
            const opdCharge = button.getAttribute('data-opd-charge');
            const standardChargeIpd = button.getAttribute('data-standard-charge-ipd');
            const standardChargeOpd = button.getAttribute('data-standard-charge-opd');
            
            document.getElementById('edit_org_name').value = orgName;
            document.getElementById('edit_standard_charge_ipd').value = '₹' + parseFloat(standardChargeIpd || 0).toFixed(2);
            document.getElementById('edit_standard_charge_opd').value = '₹' + parseFloat(standardChargeOpd || 0).toFixed(2);
            
            // For now, we'll edit IPD charge first, then OPD separately
            // This is a simplified version - you may want to create separate endpoints for IPD/OPD
            if (ipdChargeId) {
                document.getElementById('edit_tpa_charge_id').value = ipdChargeId;
                document.getElementById('edit_charge_type').value = 'IPD';
                document.getElementById('edit_tpa_charge_ipd').value = ipdCharge || '';
            } else {
                document.getElementById('edit_tpa_charge_id').value = '';
                document.getElementById('edit_tpa_charge_ipd').value = '';
            }
            
            if (opdChargeId) {
                document.getElementById('edit_tpa_charge_opd').value = opdCharge || '';
            } else {
                document.getElementById('edit_tpa_charge_opd').value = '';
            }
        }
    </script>
@endsection
