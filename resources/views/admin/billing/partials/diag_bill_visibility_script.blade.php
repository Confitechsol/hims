<script>
document.addEventListener('change', function(e) {
    const toggle = e.target.closest('.diag-bill-vis-toggle');
    if (!toggle) {
        return;
    }

    const billId = toggle.getAttribute('data-bill-id');
    const field = toggle.getAttribute('data-field');
    const baseUrl = toggle.getAttribute('data-url');
    const value = toggle.checked ? 1 : 0;
    const csrfMeta = document.querySelector("meta[name='csrf-token']");
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    fetch(baseUrl.replace('__ID__', billId), {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ field: field, value: value })
    })
        .then(function(res) {
            return res.json().then(function(body) {
                return { ok: res.ok, body: body };
            });
        })
        .then(function(result) {
            if (!result.ok || !result.body.success) {
                toggle.checked = !toggle.checked;
                alert((result.body && result.body.message) ? result.body.message : 'Unable to update bill visibility.');
            }
        })
        .catch(function() {
            toggle.checked = !toggle.checked;
            alert('Unable to update bill visibility.');
        });
});
</script>
