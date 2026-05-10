<?php
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div style="margin-left:240px; padding:20px; background:#f4f4f7; min-height:100vh;">

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
    <h2 style="color:#95298e;">All Tickets</h2>

    <input id="search" placeholder="Search..."
        style="padding:8px; width:220px; border-radius:6px; border:1px solid #ccc;">
</div>

<div style="background:white; padding:15px; border-radius:10px;">
<table style="width:100%; border-collapse:collapse;">
<thead>
<tr style="text-align:left; border-bottom:1px solid #ddd;">
    <th>S/N</th>
    <th>Tag</th>
    <th>Model</th>
    <th>Hardware</th>
    <th>Branch</th>
    <th>Problem</th>
    <th>Status</th>
    <th>Created</th>
    <th>Returned</th>
    <th>Actions</th>
</tr>
</thead>

<tbody id="ticketsBody"></tbody>
</table>
</div>
</div>

<!-- MODAL -->
<div id="modal" style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.5);justify-content:center;align-items:center;">

<div style="background:white;padding:20px;width:420px;border-radius:10px;">
<h2 style="color:#95298e;">Ticket Details</h2>

<div id="modalContent"></div>

<button onclick="closeModal()"
style="margin-top:10px;width:100%;background:#95298e;color:white;padding:8px;border:none;">
Close
</button>

</div>
</div>

<script>

let tickets = [];

/* LOAD */
function loadTickets() {
    fetch("/projects/PC_STATUS/backend/api/tickets/list.php")
    .then(res => res.json())
    .then(data => {
        tickets = data;
        render(data);
    });
}

/* RENDER */
function render(data) {

    let html = "";

    data.forEach(t => {

        const id = t.id;

        let color =
            t.status === "Closed" ? "#10b981" :
            t.status === "Active" ? "#3b82f6" :
            "#f59e0b";

        html += `
        <tr>

            <td>${t.serialNumber || "-"}</td>
            <td>${t.tagNumber || "-"}</td>
            <td>${t.pcModel || "-"}</td>
            <td>${t.hardwareType || "PC"}</td>
            <td>${t.branch || "-"}</td>
            <td>${t.problem || "-"}</td>

            <td>
                <span style="background:${color};color:white;padding:4px 8px;border-radius:20px;">
                    ${t.status}
                </span>
            </td>

            <td>${t.createdAt || "-"}</td>
            <td>${t.returnedAt || "-"}</td>

            <td onclick="event.stopPropagation()" style="display:flex;gap:5px;flex-wrap:wrap">

                <button onclick="setStatus(${id}, 'Active')"
                    style="background:#3b82f6;color:white;border:none;padding:5px;">
                    Active
                </button>

                <button onclick="setStatus(${id}, 'Pending')"
                    style="background:#f59e0b;color:white;border:none;padding:5px;">
                    Pending
                </button>

                <button onclick="setStatus(${id}, 'Closed')"
                    style="background:#10b981;color:white;border:none;padding:5px;">
                    Close
                </button>

                <button onclick="assignTicket(${id})"
                    style="background:#6b7280;color:white;border:none;padding:5px;">
                    Assign
                </button>

                <button onclick="printTicket(${id})"
                    style="background:#000;color:white;border:none;padding:5px;">
                    🖨️
                </button>

                <!-- EDIT BUTTON (REPLACED VIEW) -->
                <button onclick="editTicket(${id})"
                    style="background:#95298e;color:white;border:none;padding:5px;">
                    Edit
                </button>

            </td>
        </tr>`;
    });

    document.getElementById("ticketsBody").innerHTML = html;
}

/* SEARCH FIX */
document.getElementById("search").addEventListener("input", function() {

    const v = this.value.toLowerCase();

    const filtered = tickets.filter(t =>
        JSON.stringify(t).toLowerCase().includes(v)
    );

    render(filtered);
});

/* STATUS FIX */
function setStatus(id, status) {

    fetch("/projects/PC_STATUS/backend/api/tickets/update-full.php", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: new URLSearchParams({ id, status })
    })
    .then(res => res.json())
    .then(() => loadTickets());
}

/* ASSIGN → SET PENDING */
function assignTicket(id) {

    const user = prompt("Assign to:");

    if (!user) return;

    fetch("/projects/PC_STATUS/backend/api/tickets/update-full.php", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: new URLSearchParams({
            id,
            assignedTo: user,
            status: "Pending"
        })
    }).then(() => loadTickets());
}

/* PRINT */
function printTicket(id) {

    const t = tickets.find(x => x.id == id);
    if (!t) return;

    const w = window.open("", "_blank");

    w.document.write(`
        <h2>Ticket</h2>
        <p>Serial: ${t.serialNumber}</p>
        <p>Tag: ${t.tagNumber}</p>
        <p>Model: ${t.pcModel}</p>
        <p>Problem: ${t.problem}</p>
        <p>Status: ${t.status}</p>
        <button onclick="window.print()">Print</button>
    `);

    w.document.close();
}

/* EDIT (REPLACED VIEW) */
function editTicket(id) {
    window.location.href =
        "/projects/PC_STATUS/frontend/pages/ticket-view.php?id=" + id;
}

/* MODAL FULL FIX */
function openModal(id) {

    const t = tickets.find(x => x.id == id);
    if (!t) return;

    document.getElementById("modal").style.display = "flex";

    document.getElementById("modalContent").innerHTML = `
        <p><b>Serial:</b> ${t.serialNumber}</p>
        <p><b>Tag:</b> ${t.tagNumber}</p>
        <p><b>Model:</b> ${t.pcModel}</p>
        <p><b>Hardware:</b> ${t.hardwareType}</p>
        <p><b>Branch:</b> ${t.branch}</p>
        <p><b>Problem:</b> ${t.problem}</p>
        <p><b>Status:</b> ${t.status}</p>
        <p><b>Created:</b> ${t.createdAt}</p>
        <p><b>Returned:</b> ${t.returnedAt}</p>
    `;
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

loadTickets();

</script>

<?php include "../includes/footer.php"; ?>