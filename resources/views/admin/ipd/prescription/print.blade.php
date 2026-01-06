<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - {{ $prescription->prescription_number }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .prescription-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .prescription-body {
            margin: 20px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Print</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; margin-left: 10px;">Close</button>
    </div>

    <div class="prescription-header">
        <h2>PRESCRIPTION</h2>
        <p><strong>Prescription No:</strong> {{ $prescription->prescription_number }}</p>
        <p><strong>Date:</strong> {{ $prescription->date->format('d/m/Y') }}</p>
    </div>

    <div class="prescription-body">
        <div class="section">
            <div class="section-title">Patient Details</div>
            @if($prescription->ipd)
                <p><strong>Patient Name:</strong> {{ $prescription->ipd->patient->name ?? 'N/A' }}</p>
                <p><strong>IPD ID:</strong> {{ $prescription->ipd->ipd_id ?? 'N/A' }}</p>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Prescribed By</div>
            <p>{{ $prescription->prescribedBy ? $prescription->prescribedBy->name . ' (' . ($prescription->prescribedBy->doctor_id ?? 'N/A') . ')' : 'N/A' }}</p>
        </div>

        @if($prescription->header_note)
        <div class="section">
            <div class="section-title">Header Note</div>
            <div>{!! $prescription->header_note !!}</div>
        </div>
        @endif

        @if($prescription->medicines->count() > 0)
        <div class="section">
            <div class="section-title">Medicines</div>
            <table>
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
                        <td>{{ $medicine->medicineDosage->name ?? 'N/A' }}</td>
                        <td>{{ $medicine->doseInterval->name ?? 'N/A' }}</td>
                        <td>{{ $medicine->doseDuration->name ?? 'N/A' }}</td>
                        <td>{{ $medicine->instruction ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($prescription->tests->count() > 0)
        <div class="section">
            <div class="section-title">Tests</div>
            @if($prescription->pathologyTests->count() > 0)
            <p><strong>Pathology Tests:</strong></p>
            <ul>
                @foreach($prescription->pathologyTests as $test)
                    <li>{{ $test->pathology->name ?? 'N/A' }}</li>
                @endforeach
            </ul>
            @endif
            @if($prescription->radiologyTests->count() > 0)
            <p><strong>Radiology Tests:</strong></p>
            <ul>
                @foreach($prescription->radiologyTests as $test)
                    <li>{{ $test->radiology->name ?? 'N/A' }}</li>
                @endforeach
            </ul>
            @endif
        </div>
        @endif

        @if($prescription->finding_description)
        <div class="section">
            <div class="section-title">Findings</div>
            <p><strong>Categories:</strong> {{ $prescription->finding_categories ?? '-' }}</p>
            <p><strong>Findings:</strong> {{ $prescription->findings ?? '-' }}</p>
            <p><strong>Description:</strong> {{ $prescription->finding_description }}</p>
        </div>
        @endif

        @if($prescription->footer_note)
        <div class="section">
            <div class="section-title">Footer Note</div>
            <div>{!! $prescription->footer_note !!}</div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>

