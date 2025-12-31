@extends('layouts.adminLayout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Prescription Details</h4>
                    <div>
                        <a href="{{ route('ipd.prescription.edit', $prescription->id) }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-edit"></i> Edit
                        </a>
                        <a href="{{ route('ipd.prescription.print', $prescription->id) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="ti ti-printer"></i> Print
                        </a>
                        <a href="{{ route('ipd.show', $prescription->ipd_id) }}" class="btn btn-secondary btn-sm">
                            <i class="ti ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Prescription Number: <strong>{{ $prescription->prescription_number }}</strong></h6>
                            <p class="mb-1"><strong>Date:</strong> {{ $prescription->date->format('d/m/Y') }}</p>
                            <p class="mb-1"><strong>Prescribed By:</strong> 
                                {{ $prescription->prescribedBy ? $prescription->prescribedBy->name . ' (' . ($prescription->prescribedBy->doctor_id ?? 'N/A') . ')' : 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Patient Details</h6>
                            @if($prescription->ipd && $prescription->ipd->patient)
                                <p class="mb-1"><strong>Patient:</strong> {{ $prescription->ipd->patient->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Patient ID:</strong> {{ $prescription->ipd->patient->patient_id ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>IPD ID:</strong> {{ $prescription->ipd->ipd_id ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Age:</strong> {{ $prescription->ipd->patient->age ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Gender:</strong> {{ $prescription->ipd->patient->gender ?? 'N/A' }}</p>
                            @else
                                <p class="mb-1 text-muted">Patient information not available</p>
                            @endif
                        </div>
                    </div>

                    @if($prescription->header_note)
                    <div class="mb-4">
                        <h6>Header Note</h6>
                        <div class="border p-3 rounded">
                            {!! $prescription->header_note !!}
                        </div>
                    </div>
                    @endif

                    @if($prescription->medicines->count() > 0)
                    <div class="mb-4">
                        <h6>Medicines</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Medicine</th>
                                        <th>Dosage</th>
                                        <th>Interval</th>
                                        <th>Duration</th>
                                        <th>Instruction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescription->medicines as $index => $medicine)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $medicine->pharmacy->name ?? 'N/A' }}</td>
                                        <td>
                                            {{ $medicine->medicineDosage->name ?? 'N/A' }}
                                            @if($medicine->medicineDosage && $medicine->medicineDosage->unit)
                                                {{ $medicine->medicineDosage->unit->unit_name ?? '' }}
                                            @endif
                                        </td>
                                        <td>{{ $medicine->doseInterval->name ?? 'N/A' }}</td>
                                        <td>{{ $medicine->doseDuration->name ?? 'N/A' }}</td>
                                        <td>{{ $medicine->instruction ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($prescription->tests->count() > 0)
                    <div class="mb-4">
                        <h6>Tests</h6>
                        <div class="row">
                            @if($prescription->pathologyTests->count() > 0)
                            <div class="col-md-6">
                                <h6 class="text-primary">Pathology Tests</h6>
                                <ul>
                                    @foreach($prescription->pathologyTests as $test)
                                        <li>{{ $test->pathology->test_name ?? $test->pathology->name ?? 'N/A' }}
                                            @if($test->pathology && $test->pathology->short_name)
                                                ({{ $test->pathology->short_name }})
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if($prescription->radiologyTests->count() > 0)
                            <div class="col-md-6">
                                <h6 class="text-info">Radiology Tests</h6>
                                <ul>
                                    @foreach($prescription->radiologyTests as $test)
                                        <li>{{ $test->radiology->test_name ?? $test->radiology->name ?? 'N/A' }}
                                            @if($test->radiology && $test->radiology->short_name)
                                                ({{ $test->radiology->short_name }})
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($prescription->finding_description)
                    <div class="mb-4">
                        <h6>Findings</h6>
                        <div class="border p-3 rounded">
                            <p><strong>Categories:</strong> {{ $prescription->finding_categories ?? '-' }}</p>
                            <p><strong>Findings:</strong> {{ $prescription->findings ?? '-' }}</p>
                            <p><strong>Description:</strong> {{ $prescription->finding_description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($prescription->footer_note)
                    <div class="mb-4">
                        <h6>Footer Note</h6>
                        <div class="border p-3 rounded">
                            {!! $prescription->footer_note !!}
                        </div>
                    </div>
                    @endif

                    @if($prescription->attachment)
                    <div class="mb-4">
                        <h6>Attachment</h6>
                        <a href="{{ asset('storage/' . $prescription->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-download"></i> Download {{ $prescription->attachment_name }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

