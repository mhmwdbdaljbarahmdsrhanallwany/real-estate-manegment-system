// كود جافاسكربت يسمح بفتح أكثر من تبويب في نفس الوقت
document.querySelectorAll('.accordion-header').forEach(header => {
    header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        content.classList.toggle('show'); // يفتح أو يغلق التبويب فقط عند الضغط
    });
});
////////////////////////////////////////////////////التفاعل مع الجدول
let currentPage = 1;
let rowsPerPage = 10;

const table = document.getElementById("customTable");
const tbody = table.querySelector("tbody");
const rows = Array.from(tbody.querySelectorAll("tr"));

function renderTable() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    rows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? "" : "none";
    });
    document.getElementById("infoBox").innerText =
        `إظهار ${start+1} إلى ${Math.min(end, rows.length)} من أصل ${rows.length} سجلات`;
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
}

function nextPage() {
    if (currentPage * rowsPerPage < rows.length) {
        currentPage++;
        renderTable();
    }
}

document.getElementById("rowsSelect").addEventListener("change", function() {
    rowsPerPage = parseInt(this.value);
    currentPage = 1;
    renderTable();
});

document.getElementById("searchBox").addEventListener("keyup", function() {
    const term = this.value.toLowerCase();
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(term) ? "" : "none";
    });
});

function sortTable(n) {
    const sorted = rows.sort((a, b) => {
        const textA = a.cells[n].innerText.toLowerCase();
        const textB = b.cells[n].innerText.toLowerCase();
        return textA.localeCompare(textB);
    });
    sorted.forEach(row => tbody.appendChild(row));
    renderTable();
}

// أول تشغيل
renderTable();