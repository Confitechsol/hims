new ClipboardJS('.copy-btn'); // Make copy buttons dynamic

function exportToExcel(tableId) {
    const table = document.getElementById(tableId);
    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet 1" });
    XLSX.writeFile(wb, `${tableId}_status.xlsx`);
}

function exportToCSV(tableId) {
    const table = document.getElementById(tableId);
    const wb = XLSX.utils.table_to_book(table);
    const csv = XLSX.utils.sheet_to_csv(wb.Sheets[wb.SheetNames[0]]);
    const blob = new Blob([csv], { type: 'text/csv' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `${tableId}_status.csv`;
    link.click();
}

function exportToPDF(tableId) {
    console.log(tableId);
    
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: 'landscape',
        unit: 'pt',
        format: 'a4'
    });
    const tableElement = document.getElementById(tableId);
    doc.autoTable({
        html: `#${tableId}`,
        startY: 20,
        theme: 'grid',
        headStyles: { fillColor: [22, 160, 133] },
        styles: { fontSize: 10, cellPadding: 4 }
    });

    doc.save(`${tableId}_table.pdf`);
}

function printTable(tableId) {
    printJS({ printable: tableId, type: 'html', style: 'th { text-align: left;border-bottom:1px solid #000; }' });
}

function searchTable(input, tableId) {
    var filter = input.value.toLowerCase();
    var table = document.getElementById(tableId);
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) { // Skip header row
        var td = tr[i].getElementsByTagName("td");
        var rowContainsSearchText = false;

        for (var j = 0; j < td.length; j++) {
            if (td[j]) {
                var txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    rowContainsSearchText = true;
                    break;
                }
            }
        }

        tr[i].style.display = rowContainsSearchText ? "" : "none";
    }
}
function createAjaxTable({
    apiUrl,
    tableSelector,
    paginationSelector,
    searchInputSelector,
    perPageSelector,
    rowRenderer
}) {
    let debounceTimer;
    const searchInput = document.querySelector(searchInputSelector);
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                callApi(1);
            }, 500);
        });
    }

    // Public call function (can be used by pagination too)
    function callApi(page = 1) {
        const searchTerm = searchInput?.value || '';
        const perPage = document.querySelector(perPageSelector)?.value || 5;

        const url = new URL(apiUrl, window.location.origin);
        url.searchParams.set("search", searchTerm);
        url.searchParams.set("page", page);
        url.searchParams.set("perPage", perPage);

        fetch(url)
            .then(res => res.json())
            .then(data => {
                updateTable(data.result.data);        
                updatePagination(data.result);
            })
            .catch(error => {
                console.error("Error fetching table data:", error);
                alert("Error fetching data. Please try again.");
            });
    }

    function updateTable(items) {
        const tableBody = document.querySelector(`${tableSelector} tbody`);
        if (!tableBody) return;
        tableBody.innerHTML = "";

        items.forEach(item => {
            const row = rowRenderer(item);
            tableBody.appendChild(row);
        });
    }

    function updatePagination(pagination) {
        const wrapper = document.querySelector(paginationSelector);
        if (!wrapper) return;
        wrapper.innerHTML = "";

        const currentPage = pagination.current_page;
        const lastPage = pagination.last_page;

        const prevBtn = createButton("« Prev", currentPage > 1, () => callApi(currentPage - 1));
        wrapper.appendChild(prevBtn);

        for (let page = 1; page <= lastPage; page++) {
            const btn = createButton(page, true, () => callApi(page), page === currentPage);
            wrapper.appendChild(btn);
        }
        const nextBtn = createButton("Next »", currentPage < lastPage, () => callApi(currentPage + 1));
        wrapper.appendChild(nextBtn);
    }

    function createButton(label, enabled, onClick, isActive = false) {
        const btn = document.createElement("button");
        btn.textContent = label;
        btn.className = `btn btn-sm me-1 ${isActive ? 'btn-primary' : 'btn-outline-secondary'}`;
        btn.disabled = !enabled;
        if (enabled) btn.onclick = onClick;
        return btn;
    }

    // Expose callApi if needed externally
    return {
        refresh: callApi
    };
}