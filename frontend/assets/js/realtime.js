
function loadStats() {
    fetch("/projects/PC_STATUS/backend/api/dashboard/stats.php")
    .then(res => res.json())
    .then(data => {

        document.getElementById("total").innerText = data.total;
        document.getElementById("open").innerText = data.open;
        document.getElementById("progress").innerText = data.progress;
        document.getElementById("resolved").innerText = data.resolved;

        const notif = document.getElementById("notifCount");
        if (notif) notif.innerText = data.open;
    });
}

function loadTickets() {
    fetch("/projects/PC_STATUS/backend/api/tickets/list.php")
    .then(res => res.json())
    .then(res => {

        const data = res.data || res;

        let html = "";

        data.forEach(t => {
            html += `
            <tr>
                <td>${t.serialNumber || "-"}</td>
                <td>${t.tagNumber || "-"}</td>
                <td>${t.pcModel || "-"}</td>
                <td>${t.hardwareType || "-"}</td>
                <td>${t.branch || "-"}</td>
                <td>${t.status || "open"}</td>
            </tr>
            `;
        });

        const table = document.getElementById("ticketsBody");
        if (table) table.innerHTML = html;
    });
}

// INITIAL LOAD
loadStats();
loadTickets();

// REAL-TIME LOOP (EVERY 5 SECONDS)
setInterval(() => {
    loadStats();
    loadTickets();
}, 5000);