@if(isset($packageDetails) && $packageDetails->count() > 0)
    @php
        $showOriginalAmount = !empty($showOriginalAmount);
        $headerColspan = $showOriginalAmount ? 4 : 3;
        $subtotalColspan = $showOriginalAmount ? 3 : 2;
    @endphp
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="{{ $headerColspan }}">Package Charges</th>
            </tr>
            <tr>
                <th>Procedure</th>
                <th>Package Name</th>
                @if($showOriginalAmount)
                    <th class="text-right">Original Amount</th>
                @endif
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packageDetails as $pkg)
                <tr>
                    <td>{{ $pkg['procedure_label'] ?? 'N/A' }}</td>
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
