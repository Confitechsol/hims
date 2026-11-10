{{-- IPD bill payment lines: cheque no/date only when payment mode is Cheque; Refunds show as negative --}}
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
                    $isRefund = strcasecmp((string) ($payment->receipt_type ?? ''), 'Refund') === 0;
                    $isCash = ($paymentMode == '1' || $paymentMode === 1 || strtolower((string) $paymentMode) === 'cash');
                    $paymentModeText = 'N/A';
                    if ($paymentMode == '1' || $paymentMode === 1) {
                        $paymentModeText = 'Cash';
                    } elseif (! empty($paymentMode)) {
                        $paymentModeText = (string) $paymentMode;
                    }
                    if ($isRefund) {
                        $paymentModeText = trim($paymentModeText . ' (Refund)');
                    }
                    $modeNorm = strtolower(str_replace('_', ' ', trim(str_replace('(Refund)', '', $paymentModeText))));
                    $isChequePayment = $modeNorm === 'cheque';
                    $showChequeDetails = $isChequePayment && ($payment->cheque_no || $payment->cheque_date);
                    $paymentReference = trim((string) ($payment->payment_reference ?? ''));
                    $showPaymentReference = ! $isCash && ! $isChequePayment && $paymentReference !== '';
                    $paymentNote = trim((string) ($payment->note ?? ''));
                    $showPaymentNote = $paymentNote !== '';
                    $showExtraRow = $showChequeDetails || $showPaymentReference || $showPaymentNote || $isRefund;
                    $lineAmount = (float) ($payment->amount ?? 0);
                    if ($isRefund) {
                        $lineAmount = -abs($lineAmount);
                    }
                    $receiptLabel = ! empty($payment->receipt_no)
                        ? (string) $payment->receipt_no
                        : ('R/' . str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT));
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $receiptLabel }}</td>
                    <td>{{ $paymentModeText }}</td>
                    <td class="text-right">Rs. {{ number_format($lineAmount, 2) }}</td>
                </tr>
                @if($showExtraRow)
                    <tr style="background-color: #f9f9f9;">
                        <td colspan="4" style="padding-left: 30px; font-size: 8px;">
                            @if($isRefund)
                                <strong>Receipt Type:</strong> Refund
                            @endif
                            @if($showChequeDetails)
                                @if($isRefund) | @endif
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
                                @if($isRefund || $showChequeDetails) | @endif
                                <strong>Reference:</strong> {{ $paymentReference }}
                            @endif
                            @if($showPaymentNote)
                                @if($isRefund || $showChequeDetails || $showPaymentReference) | @endif
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
