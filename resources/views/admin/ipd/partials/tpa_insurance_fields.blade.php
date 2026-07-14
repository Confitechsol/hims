@php
    $ipd = $ipd ?? new \App\Models\IpdDetail();
    $tpas = $tpas ?? collect();
    $insuranceCompanies = $insuranceCompanies ?? collect();
    $patientSuggestedTpaId = optional($ipd->patient)->organisation_id;
    $selectedTpaId = old('organisation_id', $ipd->organisation_id ?? ($patientSuggestedTpaId ?? ''));
    $selectedInsuranceId = old('insurance_company_id', $ipd->insurance_company_id ?? '');
    $isCashless = old('is_cashless', $ipd->is_cashless ?? false);
    $selectedTpa = $tpas->firstWhere('id', (int) $selectedTpaId);
    $select2DropdownParent = $select2DropdownParent ?? '#tpa-insurance-section';
    $sectionClass = $sectionClass ?? 'form-section mb-4';
    $ipdInsuranceSaved = method_exists($ipd, 'isInsuranceBilling') ? $ipd->isInsuranceBilling() : false;
    $showUnsavedInsuranceHint = ! empty($ipd->id) && ! $ipdInsuranceSaved && ($selectedTpaId || $selectedInsuranceId || $patientSuggestedTpaId);
    $selectedInsurance = $insuranceCompanies->firstWhere('id', (int) $selectedInsuranceId);
@endphp

<style>
    #tpa-insurance-section .tpa-insurance-docs {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed var(--border-color, #e5e7eb);
    }

    #tpa-insurance-section .cashless-strip {
        margin-top: 1rem;
        padding: 0.65rem 0.9rem;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 8px;
        background: #fafafa;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    #tpa-insurance-section .cashless-strip .form-check-input {
        margin-top: 0;
        cursor: pointer;
        flex-shrink: 0;
    }

    #tpa-insurance-section .cashless-strip .form-check-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        cursor: pointer;
        margin-bottom: 0;
    }

    #tpa-insurance-section .field-hint {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.78rem;
        color: #6b7280;
        line-height: 1.35;
        word-break: break-word;
    }

    #tpa-insurance-section .ipd-tpa-insurance-select + .select2-container {
        width: 100% !important;
    }

    #tpa-insurance-section .select2-container--default .select2-selection--single {
        min-height: 42px;
        border: 1px solid var(--border-color, #dee2e6);
        border-radius: 6px;
        display: flex;
        align-items: center;
    }

    #tpa-insurance-section .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.35;
        padding-left: 0.875rem;
        padding-right: 2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    #tpa-insurance-section .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }

    #tpa-insurance-section .select2-results__option {
        white-space: normal;
        word-break: break-word;
        line-height: 1.35;
    }

    #tpa-insurance-section .select2-selection__rendered .select2-selection__placeholder {
        color: #adb5bd;
    }
</style>

<div class="{{ $sectionClass }}" id="tpa-insurance-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="bi bi-shield-check"></i>
        </div>
        <h6 class="section-title mb-0 pb-0">TPA &amp; Insurance</h6>
    </div>
    <div class="section-body">
        @if($showUnsavedInsuranceHint)
            <div class="alert alert-warning py-2 px-3 mb-3 small" role="alert">
                <strong>Not saved on this admission yet.</strong>
                TPA may be pre-filled from the patient profile. Confirm TPA &amp; Insurance below, then click
                <strong>Save</strong> on this page before exporting the approval bill.
            </div>
        @endif
        <div class="form-row cols-2">
            <div class="field-group">
                <label for="organisation_id" class="form-label">TPA Name</label>
                <select name="organisation_id" id="organisation_id" class="form-select ipd-tpa-insurance-select">
                    <option value="">Select TPA</option>
                    @foreach($tpas as $tpa)
                        <option value="{{ $tpa->id }}"
                            data-code="{{ $tpa->code }}"
                            title="{{ $tpa->organisation_name }}"
                            {{ (string) $selectedTpaId === (string) $tpa->id ? 'selected' : '' }}>
                            {{ $tpa->organisation_name }}
                        </option>
                    @endforeach
                </select>
                <small class="field-hint" id="tpa_code_hint">
                    @if($selectedTpa && $selectedTpa->code)
                        Code: {{ $selectedTpa->code }}
                    @else
                        Choose the third-party administrator for this admission.
                    @endif
                </small>
            </div>

            <div class="field-group">
                <label for="insurance_company_id" class="form-label">Insurance Name</label>
                <select name="insurance_company_id" id="insurance_company_id" class="form-select ipd-tpa-insurance-select">
                    <option value="">Select Insurance</option>
                    @foreach($insuranceCompanies as $company)
                        <option value="{{ $company->id }}"
                            data-code="{{ $company->code }}"
                            title="{{ $company->code ? $company->name . ' (' . $company->code . ')' : $company->name }}"
                            {{ (string) $selectedInsuranceId === (string) $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                <small class="field-hint" id="insurance_company_help">
                    @if($selectedInsurance && $selectedInsurance->code)
                        Code: {{ $selectedInsurance->code }}
                    @else
                        Select the insurance company for this admission (independent of TPA).
                    @endif
                </small>
            </div>
        </div>

        <div class="cashless-strip">
            <input type="checkbox" class="form-check-input" name="is_cashless" id="is_cashless" value="1"
                {{ $isCashless ? 'checked' : '' }}>
            <label class="form-check-label" for="is_cashless">Cashless admission</label>
        </div>

        <div class="form-row cols-3 tpa-insurance-docs">
            <div class="field-group">
                <label for="insurance_policy_no" class="form-label">Insurance Policy No.</label>
                <input type="text" name="insurance_policy_no" id="insurance_policy_no" class="form-control"
                    value="{{ old('insurance_policy_no', $ipd->insurance_policy_no ?? '') }}"
                    placeholder="Enter policy number" maxlength="100">
            </div>

            <div class="field-group">
                <label for="insurance_card_no" class="form-label">Card No.</label>
                <input type="text" name="insurance_card_no" id="insurance_card_no" class="form-control"
                    value="{{ old('insurance_card_no', $ipd->insurance_card_no ?? '') }}"
                    placeholder="Enter card number" maxlength="100">
            </div>

            <div class="field-group">
                <label for="ccn_no" class="form-label">CCN No.</label>
                <input type="text" name="ccn_no" id="ccn_no" class="form-control"
                    value="{{ old('ccn_no', $ipd->ccn_no ?? '') }}"
                    placeholder="Enter CCN number" maxlength="100">
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="field-group">
                <label for="initial_approval_amount" class="form-label">Initial Approval Amount (INR)</label>
                <input type="number" name="initial_approval_amount" id="initial_approval_amount" class="form-control"
                    step="0.01" min="0"
                    value="{{ old('initial_approval_amount', isset($ipd->initial_approval_amount) ? number_format((float) $ipd->initial_approval_amount, 2, '.', '') : '') }}"
                    placeholder="Amount approved by insurer/TPA">
                <small class="field-hint">Shown on Insurance Approval Bill. Further approval = Grand Total − MOU Discount − this amount.</small>
            </div>
            <div class="field-group">
                <label for="final_approval_amount" class="form-label">Final Approval Amount (INR)</label>
                <input type="number" name="final_approval_amount" id="final_approval_amount" class="form-control"
                    step="0.01" min="0"
                    value="{{ old('final_approval_amount', isset($ipd->final_approval_amount) ? number_format((float) $ipd->final_approval_amount, 2, '.', '') : '') }}"
                    placeholder="Enter after insurer final response">
                <small class="field-hint">Manual entry after insurer response. Used as Approval Amount / Due on A/C on Insurance Final Bill.</small>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    function boot() {
        const $ = window.jQuery;
        if (!$ || !$.fn.select2) {
            setTimeout(boot, 50);
            return;
        }

        const tpaSelect = document.getElementById('organisation_id');
        const insuranceSelect = document.getElementById('insurance_company_id');
        const insuranceHelp = document.getElementById('insurance_company_help');
        const tpaCodeHint = document.getElementById('tpa_code_hint');
        const section = document.getElementById('tpa-insurance-section');

        if (!tpaSelect || !insuranceSelect) {
            return;
        }

        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }
            const title = option.element ? (option.element.getAttribute('title') || option.text) : option.text;
            return $('<span>').attr('title', title).text(option.text);
        }

        function initSelect2(el) {
            const $el = $(el);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            $el.select2({
                width: '100%',
                dropdownAutoWidth: false,
                placeholder: el.id === 'organisation_id' ? 'Select TPA' : 'Select Insurance',
                allowClear: true,
                dropdownParent: $(@json($select2DropdownParent)),
                templateResult: formatOption,
                templateSelection: formatOption,
            });
        }

        function updateTpaHint() {
            const option = tpaSelect.options[tpaSelect.selectedIndex];
            if (!option || !option.value) {
                tpaCodeHint.textContent = 'Choose the third-party administrator for this admission.';
                return;
            }
            const code = option.getAttribute('data-code');
            tpaCodeHint.textContent = code ? ('Code: ' + code) : 'TPA selected.';
        }

        function updateInsuranceHint() {
            const option = insuranceSelect.options[insuranceSelect.selectedIndex];
            if (!option || !option.value) {
                insuranceHelp.textContent = 'Select the insurance company for this admission (independent of TPA).';
                return;
            }
            const code = option.getAttribute('data-code');
            insuranceHelp.textContent = code ? ('Code: ' + code) : 'Insurance company selected.';
        }

        initSelect2(tpaSelect);
        initSelect2(insuranceSelect);
        updateTpaHint();
        updateInsuranceHint();

        $(tpaSelect).on('change select2:select select2:clear', function () {
            updateTpaHint();
        });

        $(insuranceSelect).on('change select2:select select2:clear', function () {
            updateInsuranceHint();
        });

        function syncTpaInsuranceSelectsForSubmit() {
            [tpaSelect, insuranceSelect].forEach(function (el) {
                if (!el || !window.jQuery) {
                    return;
                }
                const val = window.jQuery(el).val();
                window.jQuery(el).prop('disabled', false);
                if (val !== null && val !== undefined && val !== '') {
                    el.value = val;
                }
            });
        }

        const hostForm = section.closest('form');
        if (hostForm && !hostForm.dataset.tpaInsuranceSubmitBound) {
            hostForm.dataset.tpaInsuranceSubmitBound = '1';
            hostForm.addEventListener('submit', function () {
                syncTpaInsuranceSelectsForSubmit();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
</script>
