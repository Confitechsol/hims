{{-- Quick toggles for pathology / radiology bill list --}}
@php
    $visFields = [
        'show_on_approval_bill' => 'Export Approval Bill',
        'show_on_approval_preview' => 'Approval Bill Preview',
        'show_on_final_preview' => 'Preview Final Bill',
        'show_on_final_bill' => 'Generate Final Bill',
    ];
@endphp
@foreach ($visFields as $visField => $visLabel)
    <td class="text-center">
        <input type="checkbox"
            class="form-check-input diag-bill-vis-toggle mt-0"
            data-bill-id="{{ $bill->id }}"
            data-field="{{ $visField }}"
            data-url="{{ $toggleUrl }}"
            title="{{ $visLabel }}"
            @checked($bill->{$visField} ?? true)>
    </td>
@endforeach
