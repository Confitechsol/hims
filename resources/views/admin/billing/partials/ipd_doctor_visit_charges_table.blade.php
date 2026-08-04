{{-- Routine doctor visit charges (excludes OT Anesthesia / OT Surgeon) --}}
@if(isset($doctorVisitGroupedByVisitType) && $doctorVisitGroupedByVisitType->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="3">Doctor Visit Charges</th>
            </tr>
            <tr>
                <th>Doctor</th>
                <th>Visits</th>
                <th class="text-right">Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctorVisitGroupedByVisitType as $visitTypeGroup)
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="3">{{ $visitTypeGroup->visit_type_label ?? 'Other' }}</td>
                </tr>
                @foreach($visitTypeGroup->rows as $row)
                    <tr>
                        <td>
                            {{ $row->doctor_label ?? 'N/A' }}
                            @if(isset($row->rate_per_visit))
                                @ {{ number_format($row->rate_per_visit, 2) }}
                            @endif
                        </td>
                        <td>{{ $row->visit_count ?? 0 }} {{ ($row->visit_count ?? 0) == 1 ? 'Visit' : 'Visits' }}</td>
                        <td class="text-right">Rs. {{ number_format($row->total_amount ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="2" class="text-right">Subtotal:</td>
                <td class="text-right">Rs. {{ number_format($doctorVisitChargesDisplaySubtotal ?? ($breakup['doctor_visit_charges'] ?? 0), 2) }}</td>
            </tr>
        </tbody>
    </table>
@endif
