{{-- Shared IPD bill visibility checkboxes for pathology / radiology billing forms --}}
@php
    $bill = $bill ?? null;
    $prefix = $prefix ?? 'bill_vis';
    $flag = function (string $field) use ($bill) {
        $default = ($bill->{$field} ?? true) ? '1' : '0';
        return (string) old($field, $default) === '1';
    };
@endphp
<div class="border rounded p-3 bg-light mb-3" id="{{ $prefix }}_section">
    <label class="form-label fw-semibold mb-2">Show on IPD bills</label>
    <div class="d-flex flex-wrap gap-3">
        <div class="form-check">
            <input type="hidden" name="show_on_approval_bill" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="{{ $prefix }}_approval"
                name="show_on_approval_bill" @checked($flag('show_on_approval_bill'))>
            <label class="form-check-label" for="{{ $prefix }}_approval">Export Approval Bill</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="show_on_approval_preview" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="{{ $prefix }}_approval_preview"
                name="show_on_approval_preview" @checked($flag('show_on_approval_preview'))>
            <label class="form-check-label" for="{{ $prefix }}_approval_preview">Approval Bill Preview</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="show_on_final_preview" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="{{ $prefix }}_final_preview"
                name="show_on_final_preview" @checked($flag('show_on_final_preview'))>
            <label class="form-check-label" for="{{ $prefix }}_final_preview">Preview Final Bill</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="show_on_final_bill" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="{{ $prefix }}_final_bill"
                name="show_on_final_bill" @checked($flag('show_on_final_bill'))>
            <label class="form-check-label" for="{{ $prefix }}_final_bill">Generate Final Bill</label>
        </div>
    </div>
    <small class="text-muted d-block mt-1">Uncheck to exclude this bill from the selected IPD bill PDF (lines and totals).</small>
</div>
