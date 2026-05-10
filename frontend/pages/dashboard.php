<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";

$role = $_SESSION['user']['role'] ?? 'user';
$userId = $_SESSION['user']['id'] ?? null;

/* 🔐 PROTECT DASHBOARD */
if ($role !== "admin" && $role !== "super_admin") {
    echo "<h2 style='color:red;text-align:center;margin-top:50px;'>Access Denied</h2>";
    exit;
}
?>

<div style="margin-left:240px; padding:20px; background:#f4f4f7; min-height:100vh;">

<h2 style="color:#95298e; margin-bottom:20px;">
    Dashboard Overview
</h2>

<!-- STATS CARDS -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:30px;">

    <div style="background:#f59e0b;color:white;padding:20px;border-radius:10px;">
        <h2 id="pending">0</h2>
        <p>Pending</p>
    </div>

    <div style="background:#3b82f6;color:white;padding:20px;border-radius:10px;">
        <h2 id="active">0</h2>
        <p>Active</p>
    </div>

    <div style="background:#10b981;color:white;padding:20px;border-radius:10px;">
        <h2 id="closed">0</h2>
        <p>Closed</p>
    </div>

</div>

<!-- 🔥 MY ASSIGNED TICKETS (NEW SECTION) -->
<div style="background:white;padding:20px;border-radius:10px;margin-bottom:20px;">
    <h3 style="color:#95298e;">📌 My Assigned Tickets</h3>

    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid #ddd;">
                <th>Model</th>
                <th>Branch</th>
                <th>Problem</th>
                <th>Status</th>
                <th>Created</th>
                <th>Returned At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="myAssigned"></tbody>
    </table>
</div>

<!-- OVERDUE SECTION -->
<div style="background:white;padding:20px;border-radius:10px;margin-bottom:20px;">
    <h3>⚠ Overdue Tickets (7+ days)</h3>
    <div id="overdue"></div>
</div>

<!-- RECENT TICKETS -->
<div style="background:white;padding:20px;border-radius:10px;">
    <h3>Recent Tickets</h3>

    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid #ddd;">
                <th>Serial</th>
                <th>Branch</th>
                <th>Status</th>
                <th>Hardware</th>
                <th>Assigned To</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody id="recent"></tbody>
    </table>
</div>

</div>

<script>

let tickets = [];

/* ================= LOAD DATA ================= */
function loadDashboard() {

    fetch("/projects/PC_STATUS/backend/api/tickets/list.php")
    .then(res => res.json())
    .then(data => {

        tickets = data;

        renderStats(data);
        renderAssigned(data);
        renderOverdue(data);
        renderRecent(data);
    });
}

/* ================= STATS ================= */
function renderStats(data) {

    let pending = 0, active = 0, closed = 0;

    data.forEach(t => {
        if (t.status === "Pending") pending++;
        else if (t.status === "Active") active++;
        else if (t.status === "Closed") closed++;
    });

    document.getElementById("pending").innerText = pending;
    document.getElementById("active").innerText = active;
    document.getElementById("closed").innerText = closed;
}

/* ================= 🔥 MY ASSIGNED ================= */
function renderAssigned(data) {

    const userId = Number(<?= $userId ?>);

    const myTickets = data.filter(t =>
        Number(t.assigned_to) === userId
    );

    let html = "";

    if (myTickets.length === 0) {
        html = `<tr><td colspan="7" style="text-align:center;">No tickets assigned to you</td></tr>`;
    } else {

        myTickets.forEach(t => {

            // support both naming formats
            const createdRaw = t.created_at || t.createdAt;
            const returnedRaw = t.returned_at || t.returnedAt;

            const created = createdRaw ? new Date(createdRaw) : null;
            const returned = returnedRaw ? new Date(returnedRaw) : null;

            const diffDays = created ? (new Date() - created) / (1000 * 60 * 60 * 24) : 0;
            const isOverdue = diffDays > 7 && t.status !== "Closed";

            html += `
                <tr style="${isOverdue ? 'background:#ffe4e6;' : ''}">

                    <td>${t.pcModel || '-'}</td>
                    <td>${t.branch || '-'}</td>
                    <td>${t.issue || t.problem || '-'}</td>

                    <td>
                        <span style="
                            padding:4px 8px;
                            border-radius:12px;
                            color:white;
                            font-size:12px;
                            background:${
                                t.status === 'Closed'
                                    ? '#10b981'
                                    : (t.status === 'Active' ? '#3b82f6' : '#f59e0b')
                            }
                        ">
                            ${t.status || 'Pending'}
                        </span>
                    </td>

                    <td>
                        ${created ? created.toLocaleString() : '-'}
                    </td>

                    <td>
                        ${returned ? returned.toLocaleString() : 'Not returned yet'}
                    </td>

                    <td>
                        <a href="/projects/PC_STATUS/frontend/pages/ticket-view.php?id=${t.id}"
                           style="
                                background:#95298e;
                                color:white;
                                padding:5px 10px;
                                border-radius:5px;
                                text-decoration:none;
                                font-size:12px;
                           ">
                           View / Edit
                        </a>
                    </td>

                </tr>
            `;
        });
    }

    document.getElementById("myAssigned").innerHTML = html;
}
/* ================= OVERDUE ================= */
function renderOverdue(data) {

    const overdue = data.filter(t => {

        if (!t.createdAt) return false;

        const created = new Date(t.createdAt);
        const diff = (new Date() - created) / (1000 * 60 * 60 * 24);

        return diff > 7 && t.status !== "Closed";
    });

    let html = "";

    if (overdue.length === 0) {
        html = "<p>No overdue tickets 🎉</p>";
    } else {
        overdue.forEach(t => {
            html += `
                <div style="padding:8px;border-bottom:1px solid #eee;">
                    <b>${t.pcModel}</b> - ${t.branch} - 
                    <span style="color:red;">${t.status}</span>
                </div>
            `;
        });
    }

    document.getElementById("overdue").innerHTML = html;
}

/* ================= RECENT ================= */
function renderRecent(data) {

    let html = "";

    data.slice(0, 5).forEach(t => {

        html += `
        <tr>
            <td>${t.serialNumber || "-"}</td>
            <td>${t.branch || "-"}</td>
            <td>${t.status || "-"}</td>
            <td>${t.hardwareType || "PC"}</td>

            <td>
                ${t.assignedToName ? t.assignedToName : "<span style='color:gray'>Not assigned yet</span>"}
            </td>

            <td>
                ${t.createdAt ? new Date(t.createdAt).toLocaleDateString() : "-"}
            </td>
        </tr>
        `;
    });

    document.getElementById("recent").innerHTML = html;
}

/* ================= INIT ================= */
loadDashboard();

</script>

<?php include "../includes/footer.php"; ?>