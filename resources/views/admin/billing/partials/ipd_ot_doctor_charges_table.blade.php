{{-- OT doctor procedure fees: Anesthesia + Surgeon (no visit count column) --}}
@if(isset($otDoctorChargeRows) && $otDoctorChargeRows->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="3">Doctor Charge (Anesthesia + Surgeon)</th>
            </tr>
            <tr>
                <th>Doctor</th>
                <th>Date Range</th>
                <th class="text-right">Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($otDoctorChargeRows as $row)
                <tr>
                    <td>
                        {{ $row->doctor_label ?? 'N/A' }}
                        @if(!empty($row->visit_type_label))
                            <span style="font-size: 8px; color: #555;"> — {{ $row->visit_type_label }}</span>
                        @endif
                        @if(isset($row->rate_per_visit) && ($row->visit_count ?? 0) > 0)
                            @ {{ number_format($row->rate_per_visit, 2) }}
                        @endif
                    </td>
                    <td>
                        {{ $row->from_date ? \Carbon\Carbon::parse($row->from_date)->format('d/m/Y') : 'N/A' }}
                        To
                        {{ $row->to_date ? \Carbon\Carbon::parse($row->to_date)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="text-right">Rs. {{ number_format($row->total_amount ?? 0, 2) }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="2" class="text-right">Subtotal:</td>
                <td class="text-right">Rs. {{ number_format($otDoctorChargesDisplaySubtotal ?? $otDoctorChargeRows->sum('total_amount'), 2) }}</td>
            </tr>
        </tbody>
    </table>
@endif
