<style>
    .pkg-select2-option-title {
        font-weight: 600;
        line-height: 1.3;
    }

    .pkg-select2-option-subtitle {
        font-size: 11px;
        color: #6c757d;
        line-height: 1.25;
        margin-top: 2px;
        white-space: normal;
    }

    .select2-container--default .select2-selection--single .pkg-select2-selection-subtitle {
        font-size: 10px;
        color: #6c757d;
        display: block;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<script>
    window.IpdPackageSelect = window.IpdPackageSelect || {};

    window.IpdPackageSelect.formatOption = function(option) {
        if (!option.id) {
            return option.text;
        }
        const el = option.element;
        const subtitle = el && el.dataset ? el.dataset.subtitle : '';
        if (!subtitle) {
            return $('<span>').text(option.text);
        }
        const wrap = $('<div></div>');
        wrap.append($('<div class="pkg-select2-option-title">').text(option.text));
        wrap.append($('<div class="pkg-select2-option-subtitle">').text(subtitle));
        return wrap;
    };

    window.IpdPackageSelect.formatSelection = function(option) {
        if (!option.id) {
            return option.text;
        }
        const el = option.element;
        const subtitle = el && el.dataset ? el.dataset.subtitle : '';
        if (!subtitle) {
            return option.text;
        }
        const wrap = $('<div style="line-height:1.2;padding:2px 0;"></div>');
        wrap.append($('<span>').text(option.text));
        wrap.append($('<span class="pkg-select2-selection-subtitle">').text(subtitle));
        return wrap;
    };

    window.IpdPackageSelect.appendOptions = function(selectEl, packages, emptyLabel) {
        const select = selectEl.jquery ? selectEl[0] : selectEl;
        if (!select) return;
        select.innerHTML = '';
        const blank = document.createElement('option');
        blank.value = '';
        blank.textContent = emptyLabel || '-- Select Package --';
        select.appendChild(blank);

        (packages || []).forEach(function(pkg) {
            const opt = document.createElement('option');
            opt.value = pkg.id;
            opt.textContent = pkg.display_title || (pkg.name + ' — ₹' + parseFloat(pkg.package_rate).toFixed(2));
            opt.dataset.subtitle = pkg.display_subtitle || '';
            opt.dataset.rate = pkg.package_rate;
            opt.dataset.gst = pkg.gst_amount ?? '';
            opt.dataset.desc = pkg.description ?? '';
            opt.dataset.packageType = pkg.package_type || 'hospital';
            opt.dataset.insuranceCompanyId = pkg.insurance_company_id ?? '';
            select.appendChild(opt);
        });
    };

    window.IpdPackageSelect.initSelect2 = function(selectEl, dropdownParent) {
        const $select = selectEl.jquery ? selectEl : window.jQuery(selectEl);
        if (!$select.length || !window.jQuery || !window.jQuery.fn.select2) {
            return;
        }
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        const parent = dropdownParent ? window.jQuery(dropdownParent) : $select.closest('.modal');
        $select.addClass('select2-input').select2({
            width: '100%',
            dropdownParent: parent.length ? parent : window.jQuery(document.body),
            templateResult: window.IpdPackageSelect.formatOption,
            templateSelection: window.IpdPackageSelect.formatSelection,
            escapeMarkup: function(m) {
                return m;
            }
        });
    };
</script>
