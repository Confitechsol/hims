{{-- IPD bill payment lines: cheque no/date only when payment mode is Cheque --}}
@if(isset($payments) && $payments->count() > 0)
    <div class="section-title">Payment Details</div>
    <table class="payment-table">
        <thead>
            <tr>
                <th>Payment Date</th>
                <th>Receipt No.</th>
                <th>Payment Mode</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                @php
                    $paymentMode = $payment->payment_mode ?? null;
                    $isCash = ($paymentMode == '1' || $paymentMode === 1 || strtolower((string) $paymentMode) === 'cash');
                    $paymentModeText = 'N/A';
                    if ($paymentMode == '1' || $paymentMode === 1) {
                        $paymentModeText = 'Cash';
                    } elseif (! empty($paymentMode)) {
                        $paymentModeText = (string) $paymentMode;
                    }
                    $modeNorm = strtolower(str_replace('_', ' ', trim($paymentModeText)));
                    $isChequePayment = $modeNorm === 'cheque';
                    $showChequeDetails = $isChequePayment && ($payment->cheque_no || $payment->cheque_date);
                    $paymentReference = trim((string) ($payment->payment_reference ?? ''));
                    $showPaymentReference = ! $isCash && ! $isChequePayment && $paymentReference !== '';
                    $paymentNote = trim((string) ($payment->note ?? ''));
                    $showPaymentNote = $paymentNote !== '';
                    $showExtraRow = $showChequeDetails || $showPaymentReference || $showPaymentNote;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d-m-Y') }}</td>
                    <td>R/{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $paymentModeText }}</td>
                    <td class="text-right">Rs. {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @if($showExtraRow)
                    <tr style="background-color: #f9f9f9;">
                        <td colspan="4" style="padding-left: 30px; font-size: 8px;">
                            @if($showChequeDetails)
                                @if($payment->cheque_no)
                                    <strong>Cheque No.:</strong> {{ $payment->cheque_no }}
                                @endif
                                @if($payment->cheque_date)
                                    @if($payment->cheque_no) | @endif
                                    <strong>Cheque Date:</strong>
                                    {{ \Carbon\Carbon::parse($payment->cheque_date)->format('d-m-Y') }}
                                @endif
                            @endif
                            @if($showPaymentReference)
                                <strong>Reference:</strong> {{ $paymentReference }}
                            @endif
                            @if($showPaymentNote)
                                @if($showChequeDetails || $showPaymentReference) | @endif
                                <strong>Note:</strong> {{ $paymentNote }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="3" class="text-right">Total Payments:</td>
                <td class="text-right">Rs. {{ number_format($breakup['total_payments'] ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>
@endif
