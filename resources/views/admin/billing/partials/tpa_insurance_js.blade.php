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
        if (!insId) {
            return url;
        }
        const sep = url.includes('?') ? '&' : '?';
        return url + sep + 'insurance_company_id=' + encodeURIComponent(insId);
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
        }
        if (tpa.insurance_company_name) {
            option.dataset.insuranceCompanyName = tpa.insurance_company_name;
        }
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
        applyRateSourceHint,
        clearRateSourceHints,
    };
})();
</script>
