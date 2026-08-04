{{-- Insurance Final Bill / Tax Invoice settlement footer --}}
@php
    $s = $insuranceFinalSummary ?? [];
@endphp
<div class="summary-section">
    <div class="section-title" style="margin-top: 0; margin-bottom: 8px;">Payment Summary</div>

    <div class="summary-row">
        <span class="summary-label">Total Bill Amount:</span>
        <span class="summary-value">Rs. {{ number_format($s['total_bill'] ?? 0, 2) }}</span>
    </div>

    <div class="summary-row">
        <span class="summary-label">Approval Amount:</span>
        <span class="summary-value">Rs. {{ number_format($s['approval_amount'] ?? 0, 2) }}</span>
    </div>

    <div class="summary-row">
        <span class="summary-label">MOU Discount:</span>
        <span class="summary-value">Rs. {{ number_format($s['mou_discount'] ?? 0, 2) }}</span>
    </div>

    @if(($s['special_discount'] ?? 0) > 0)
        <div class="summary-row">
            <span class="summary-label">Hospital Discount:</span>
            <span class="summary-value">Rs. {{ number_format($s['special_discount'] ?? 0, 2) }}</span>
        </div>
    @endif

    <div class="summary-row">
        <span class="summary-label">Balance Amount:</span>
        <span class="summary-value">Rs. {{ number_format($s['balance_amount'] ?? 0, 2) }}</span>
    </div>

    <div class="summary-row">
        <span class="summary-label">Less Advance:</span>
        <span class="summary-value">
            Rs. {{ number_format($s['advance'] ?? 0, 2) }}
            @if(!empty($s['advance_receipts_text']))
                <span style="font-size: 8px; font-weight: normal;">({{ $s['advance_receipts_text'] }})</span>
            @endif
        </span>
    </div>

    <div class="summary-row">
        <span class="summary-label">Due on Account of Patient Party:</span>
        <span class="summary-value">Rs. {{ number_format($s['due_patient_party'] ?? 0, 2) }}</span>
    </div>

    <div class="summary-row">
        <span class="summary-label"><span class="red">{{ $s['due_on_account_label'] ?? 'Due Amount (On A/C Insurance)' }}:</span></span>
        <span class="summary-value"><span class="red">Rs. {{ number_format($s['due_on_account'] ?? 0, 2) }}</span></span>
    </div>
</div>

@if(!empty($dueOnAccountInWords))
<div class="words-section">
    <div class="words-row">
        <span class="words-label"><strong>Due on A/C (in words):</strong></span>
        <span class="words-value"><strong>{{ $dueOnAccountInWords }}</strong></span>
    </div>
</div>
@endif
