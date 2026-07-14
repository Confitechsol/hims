{{-- Approval bill footer summary (matches sample: GRAND TOTAL, advance, MOU, initial approval, further request) --}}
<div class="summary-section">
    <div class="section-title" style="margin-top: 0; margin-bottom: 8px;">Bill Summary (For Approval)</div>
    <div class="summary-row">
        <span class="summary-label">GRAND TOTAL:</span>
        <span class="summary-value">Rs. {{ number_format($grandTotal ?? 0, 2) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Less Advance:</span>
        <span class="summary-value">Rs. {{ number_format($totalAdvance ?? 0, 2) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">MOU Discount:</span>
        <span class="summary-value">Rs. {{ number_format($mouDiscountAmount ?? 0, 2) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label blue">INITIAL APPROVAL AMOUNT:</span>
        <span class="summary-value blue">Rs. {{ number_format($initialApprovalAmount ?? 0, 2) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label"><span class="red">REQUEST FOR FURTHER APPROVAL AMOUNT:</span></span>
        <span class="summary-value"><span class="red">Rs. {{ number_format($requestFurtherApproval ?? 0, 2) }}</span></span>
    </div>
</div>

@if(!empty($requestFurtherApprovalInWords))
<div class="words-section">
    <div class="words-row">
        <span class="words-label">Request for Further Approval:</span>
        <span class="words-value">{{ $requestFurtherApprovalInWords }}</span>
    </div>
</div>
@endif
