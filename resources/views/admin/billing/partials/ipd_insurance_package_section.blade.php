{{-- Package section for insurance final bill: package lines + excluded medicines/implants --}}
@if(isset($packageDetails) && $packageDetails->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="2">PACKAGE BED CHARGE</th>
            </tr>
            <tr>
                <th>Particulars</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packageDetails as $pkg)
                <tr>
                    <td>
                        PACKAGE - ({{ $pkg['package_name_display'] ?? ($pkg['package_name'] ?? 'N/A') }})
                        @if($packageDetails->count() > 1 && !empty($pkg['procedure_label']))
                            <span style="font-size: 8px; color: #555;"> — {{ $pkg['procedure_label'] }}</span>
                        @endif
                    </td>
                    <td class="text-right">Rs. {{ number_format($pkg['amount'] ?? 0, 2) }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td class="text-right">Subtotal:</td>
                <td class="text-right">Rs. {{ number_format($breakup['package_charges'] ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>
@endif

@if(isset($excludedMedicineImplantCharges) && $excludedMedicineImplantCharges->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="5">Medicines / Implants (Excluded from Package)</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Charge Name</th>
                <th>Qty</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($excludedMedicineImplantCharges as $charge)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($charge->date)->format('d-m-Y') }}</td>
                    <td>{{ $charge->chargeCategory->name ?? 'N/A' }}</td>
                    <td>{{ $charge->charge->name ?? 'N/A' }}</td>
                    <td>{{ $charge->qty }}</td>
                    <td class="text-right">Rs. {{ number_format($charge->net_amount, 2) }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="4" class="text-right">Subtotal:</td>
                <td class="text-right">Rs. {{ number_format($excludedMedicineImplantCharges->sum('net_amount'), 2) }}</td>
            </tr>
        </tbody>
    </table>
@endif
