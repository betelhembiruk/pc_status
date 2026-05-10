<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";

$role = $_SESSION['user']['role'] ?? 'user';
?>

<script>
const CURRENT_ROLE = "<?= $role ?>";
</script>

<div style="margin-left:240px; padding:20px; background:#f4f4f7; min-height:100vh;">

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
    <h2 style="color:#95298e;">
        <?= $role === 'user' ? 'My Tickets' : 'All Tickets' ?>
    </h2>

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

    <!-- 🔥 NEW COLUMN -->
    <th>Assigned To</th>

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

/* ================= LOAD ================= */
function loadTickets() {
    fetch("/projects/PC_STATUS/backend/api/tickets/list.php")
    .then(res => res.json())
    .then(data => {
        tickets = data;
        render(data);
    })
    .catch(err => console.log("LOAD ERROR:", err));
}

/* ================= RENDER ================= */
function render(data) {

    let html = "";

    data.forEach(t => {

        const id = t.id;

        let statusColor =
            t.status === "Closed" ? "#10b981" :
            t.status === "Active" ? "#3b82f6" :
            "#f59e0b";

        /* 🔥 FIX: assigned user name */
let assignedTo = t.assignedToName
    ? t.assignedToName
    : "Not assigned yet";
        let actions = "";

        /* 👤 USER (READ ONLY) */
        if (CURRENT_ROLE === "user") {

            actions = `
                <button onclick="openModal(${id})"
                    style="background:#3b82f6;color:white;border:none;padding:5px;">
                    View
                </button>

                <button onclick="printTicket(${id})"
                    style="background:#000;color:white;border:none;padding:5px;">
                    🖨️ Print
                </button>
            `;

        /* 🛠 ADMIN / SUPER ADMIN */
        } else {

            actions = `
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
            `;
        }

        html += `
        <tr>
            <td>${t.serialNumber || "-"}</td>
            <td>${t.tagNumber || "-"}</td>
            <td>${t.pcModel || "-"}</td>
            <td>${t.hardwareType || "-"}</td>
            <td>${t.branch || "-"}</td>
            <td>${t.problem || "-"}</td>

            <td>
                <span style="background:${statusColor};color:white;padding:4px 8px;border-radius:20px;">
                    ${t.status || "Pending"}
                </span>
            </td>

            <td>${t.createdAt || "-"}</td>
            <td>${t.returnedAt || "-"}</td>

            <!-- 🔥 ASSIGNED USER -->
            <td>${assignedTo}</td>

            <td style="display:flex;gap:5px;flex-wrap:wrap">
                ${actions}
            </td>
        </tr>
        `;
    });

    document.getElementById("ticketsBody").innerHTML = html;
}

/* ================= SEARCH ================= */
document.getElementById("search").addEventListener("input", function() {

    const v = this.value.toLowerCase();

    const filtered = tickets.filter(t =>
        Object.values(t).join(" ").toLowerCase().includes(v)
    );

    render(filtered);
});

/* ================= STATUS ================= */
function setStatus(id, status) {

    fetch("/projects/PC_STATUS/backend/api/tickets/update-full.php", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: new URLSearchParams({ id, status })
    })
    .then(res => res.json())
    .then(() => loadTickets());
}

/* ================= ASSIGN ================= */
function assignTicket(ticketId) {

    fetch("/projects/PC_STATUS/backend/api/users/options.php")
    .then(res => res.json())
    .then(data => {

        let options = data.data.map(u =>
            `<option value="${u.id}">${u.full_name} (${u.role})</option>`
        ).join("");

        const modal = `
        <div id="assignBox" style="
            position:fixed;top:0;left:0;width:100%;height:100%;
            background:rgba(0,0,0,0.5);display:flex;
            justify-content:center;align-items:center;
        ">
        <div style="background:white;padding:20px;width:350px;border-radius:10px;">

            <h3>Assign Ticket</h3>

            <select id="userSelect" style="width:100%;padding:10px;margin:10px 0;">
                ${options}
            </select>

            <button onclick="confirmAssign(${ticketId})"
                style="width:100%;background:#95298e;color:white;padding:10px;border:none;">
                Assign
            </button>

            <button onclick="document.getElementById('assignBox').remove()"
                style="width:100%;margin-top:10px;background:#999;color:white;padding:10px;border:none;">
                Cancel
            </button>

        </div>
        </div>`;

        document.body.insertAdjacentHTML("beforeend", modal);
    });
}

/* CONFIRM ASSIGN */
function confirmAssign(ticketId) {

    const userId = document.getElementById("userSelect").value;

    fetch("/projects/PC_STATUS/backend/api/tickets/update-full.php", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: new URLSearchParams({
            id: ticketId,
            assignedTo: userId,
            status: "Pending"
        })
    })
    .then(res => res.json())
    .then(() => {
        document.getElementById("assignBox").remove();
        loadTickets();
    });
}

/* ================= PRINT ================= */
function printTicket(id) {
    const t = tickets.find(x => x.id == id);
    const w = window.open("", "_blank");

    w.document.write(`
        <h2>Ticket</h2>
        <p>${t.serialNumber}</p>
        <p>${t.problem}</p>
    `);

    w.document.close();
}

/* ================= MODAL ================= */
function openModal(id) {
    const t = tickets.find(x => x.id == id);

    document.getElementById("modal").style.display = "flex";

    document.getElementById("modalContent").innerHTML = `
        <p>${t.problem}</p>
    `;
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

loadTickets();

</script>

<?php include "../includes/footer.php"; ?>