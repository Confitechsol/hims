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
