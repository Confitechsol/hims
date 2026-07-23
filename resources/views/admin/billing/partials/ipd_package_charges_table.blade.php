@if(isset($packageDetails) && collect($packageDetails)->count() > 0)
    @php
        $billPackageRows = collect($packageDetails);
        $showOriginalAmount = !empty($showOriginalAmount);
        $showProcedureColumn = (bool) ($showPackageProcedureColumn ?? ($billPackageRows->count() > 1));
        $headerColspan = 2 + ($showProcedureColumn ? 1 : 0) + ($showOriginalAmount ? 1 : 0);
        $subtotalColspan = $headerColspan - 1;
    @endphp
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="{{ $headerColspan }}">Package Charges</th>
            </tr>
            <tr>
                @if($showProcedureColumn)
                    <th>Procedure</th>
                @endif
                <th>Package Name</th>
                @if($showOriginalAmount)
                    <th class="text-right">Original Amount</th>
                @endif
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($billPackageRows as $pkg)
                <tr>
                    @if($showProcedureColumn)
                        <td>{{ $pkg['procedure_label'] ?? 'N/A' }}</td>
                    @endif
                    <td>{{ $pkg['package_name_display'] ?? ($pkg['package_name'] ?? 'N/A') }}</td>
                    @if($showOriginalAmount)
                        <td class="text-right">Rs. {{ number_format($pkg['original_amount'] ?? 0, 2) }}</td>
                    @endif
                    <td class="text-right">Rs. {{ number_format($pkg['amount'] ?? 0, 2) }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="{{ $subtotalColspan }}" class="text-right">Subtotal:</td>
                <td class="text-right">Rs. {{ number_format($breakup['package_charges'] ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>
@endif
