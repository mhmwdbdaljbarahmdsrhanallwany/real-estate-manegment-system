/* /////////////////////////////////////////////تفاعل بيانات الاشتراك */
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('institutions-table');
    const rows = table.getElementsByTagName('tr');
    let sortDirection = {};

    function filterTable() {
        const date = document.getElementById('filter-date').value;
        const status = document.getElementById('filter-status').value;
        const packageType = document.getElementById('filter-package').value;
        const penalty = document.getElementById('filter-penalty').value;
        const search = document.getElementById('filter-search').value.toLowerCase();

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length === 0) continue;

            const rowName = cells[0].innerText.toLowerCase();
            const rowDate = cells[1].innerText;
            const rowStatus = cells[2].innerText;
            const rowPackage = cells[3].innerText;
            const rowPenalty = cells[4].innerText;

            let show = true;

            if (date && rowDate !== date) show = false;
            if (status && rowStatus !== status) show = false;
            if (packageType && rowPackage !== packageType) show = false;
            if (penalty && rowPenalty !== penalty) show = false;
            if (search && !rowName.includes(search)) show = false;

            rows[i].style.display = show ? '' : 'none';
        }
    }

    function sortTable(columnIndex) {
        const tbody = table.querySelector('tbody');
        const rowsArray = Array.from(tbody.querySelectorAll('tr'));
        const direction = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
        sortDirection[columnIndex] = direction;

        rowsArray.sort((a, b) => {
            const aText = a.querySelectorAll('td')[columnIndex].innerText.toLowerCase();
            const bText = b.querySelectorAll('td')[columnIndex].innerText.toLowerCase();

            if (aText < bText) return direction === 'asc' ? -1 : 1;
            if (aText > bText) return direction === 'asc' ? 1 : -1;
            return 0;
        });

        rowsArray.forEach(row => tbody.appendChild(row));
    }

    function exportToExcel() {
        let tableHTML = table.outerHTML.replace(/ /g, '%20');
        const a = document.createElement('a');
        a.href = 'data:application/vnd.ms-excel,' + tableHTML;
        a.download = 'institutions.xls';
        a.click();
    }

    function exportToPDF() {
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>جدول المؤسسات</title></head><body>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

    document.querySelectorAll('#filter-date, #filter-status, #filter-package, #filter-penalty, #filter-search')
        .forEach(el => el.addEventListener('input', filterTable));

    table.querySelectorAll('th').forEach(th => {
        th.addEventListener('click', () => {
            const columnIndex = th.getAttribute('data-column');
            sortTable(columnIndex);
        });
    });

    document.getElementById('export-excel').addEventListener('click', exportToExcel);
    document.getElementById('export-pdf').addEventListener('click', exportToPDF);
});