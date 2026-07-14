<script>
window.BillingTpaInsurance = (function () {
    function getInsuranceCompanyIdFromDropdown() {
        const dd = document.getElementById('tpa_dropdown');
        if (!dd || !dd.value) {
            return '';
        }
        const opt = dd.options[dd.selectedIndex];
        return opt && opt.dataset.insuranceCompanyId ? opt.dataset.insuranceCompanyId : '';
    }

    function appendInsuranceParams(url) {
        const insId = getInsuranceCompanyIdFromDropdown();
        const patientIdEl = document.getElementById('patient_id');
        const patientId = patientIdEl ? patientIdEl.value : '';
        const params = [];
        if (insId) {
            params.push('insurance_company_id=' + encodeURIComponent(insId));
        }
        if (patientId) {
            params.push('patient_id=' + encodeURIComponent(patientId));
        }
        if (!params.length) {
            return url;
        }
        const sep = url.includes('?') ? '&' : '?';
        return url + sep + params.join('&');
    }

    function setTpaOption(option, tpa) {
        option.value = tpa.id;
        let label = tpa.name || 'TPA';
        if (tpa.code) {
            label += ' (' + tpa.code + ')';
        }
        if (tpa.insurance_company_name) {
            label += ' — ' + tpa.insurance_company_name;
        }
        option.textContent = label;
        if (tpa.insurance_company_id) {
            option.dataset.insuranceCompanyId = tpa.insurance_company_id;
        } else {
            delete option.dataset.insuranceCompanyId;
        }
        if (tpa.insurance_company_name) {
            option.dataset.insuranceCompanyName = tpa.insurance_company_name;
        } else {
            delete option.dataset.insuranceCompanyName;
        }
        if (tpa.preferred) {
            option.dataset.preferred = '1';
        } else {
            delete option.dataset.preferred;
        }
        if (tpa.ipd_id) {
            option.dataset.ipdId = tpa.ipd_id;
        }
    }

    /**
     * Upsert TPA option with latest insurance data and optionally select + activate.
     */
    function upsertAndSelectTpa(tpa, options) {
        options = options || {};
        const tpaDropdown = document.getElementById('tpa_dropdown');
        if (!tpaDropdown || !tpa || !tpa.id) {
            return false;
        }

        let option = null;
        for (let i = 0; i < tpaDropdown.options.length; i++) {
            if (String(tpaDropdown.options[i].value) === String(tpa.id)) {
                option = tpaDropdown.options[i];
                break;
            }
        }

        if (!option) {
            if (tpaDropdown.options.length > 0 && tpaDropdown.options[0].disabled) {
                tpaDropdown.remove(0);
            }
            option = document.createElement('option');
            if (tpaDropdown.options.length > 0) {
                tpaDropdown.insertBefore(option, tpaDropdown.options[1] || null);
            } else {
                tpaDropdown.appendChild(option);
            }
        }

        setTpaOption(option, tpa);

        if (options.select !== false) {
            const activateCheckbox = document.getElementById('activate_tpa');
            const container = document.getElementById('tpa_dropdown_container');
            if (activateCheckbox) {
                activateCheckbox.checked = true;
            }
            if (container) {
                container.style.display = 'block';
            }
            tpaDropdown.required = true;
            tpaDropdown.value = String(tpa.id);
            if (typeof options.onSelected === 'function') {
                options.onSelected(tpa.id);
            } else {
                tpaDropdown.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }

        const helpText = document.getElementById('tpa_help_text');
        if (helpText && options.helpText) {
            helpText.textContent = options.helpText;
            helpText.className = 'text-success';
        }

        return true;
    }

    function selectPreferredTpa(tpas, onSelected) {
        if (!Array.isArray(tpas) || !tpas.length) {
            return false;
        }
        const preferred = tpas.find(t => t && t.preferred) || tpas[0];
        return upsertAndSelectTpa(preferred, {
            helpText: preferred.from_ipd
                ? 'Using latest IPD admission TPA / insurance'
                : (tpas.length + ' TPA(s) found for this patient'),
            onSelected: onSelected,
        });
    }

    function applyRateSourceHint(row, data) {
        const amountCell = row.querySelector('.test_amount')?.closest('td');
        if (!amountCell) {
            return;
        }
        let hint = amountCell.querySelector('.rate-source-hint');
        if (!hint) {
            hint = document.createElement('small');
            hint.className = 'rate-source-hint d-block mt-1';
            amountCell.appendChild(hint);
        }
        if (data.rate_source === 'insurance_panel') {
            hint.className = 'rate-source-hint d-block mt-1 text-success';
            hint.textContent = 'Insurance panel' + (data.insurer_test_name ? ': ' + data.insurer_test_name : '');
        } else if (data.rate_source === 'tpa') {
            hint.className = 'rate-source-hint d-block mt-1 text-info';
            hint.textContent = 'TPA rate';
        } else {
            hint.className = 'rate-source-hint d-block mt-1 text-muted';
            hint.textContent = data.rate_source === 'standard' ? 'Standard charge' : '';
        }
    }

    function clearRateSourceHints() {
        document.querySelectorAll('.rate-source-hint').forEach(el => el.remove());
    }

    return {
        getInsuranceCompanyIdFromDropdown,
        appendInsuranceParams,
        setTpaOption,
        upsertAndSelectTpa,
        selectPreferredTpa,
        applyRateSourceHint,
        clearRateSourceHints,
    };
})();
</script>
