<?php include "../includes/header.php"; ?>

<div style="padding:20px">

<h2>Tickets</h2>

<table border="1" width="100%" cellpadding="8">
    <thead>
        <tr>
            <th>Serial</th>
            <th>Tag</th>
            <th>Model</th>
            <th>Type</th>
            <th>Branch</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody id="ticketsBody"></tbody>
</table>

</div>

<script>
function loadTickets() {
    fetch("/projects/PC_STATUS/backend/api/tickets/list.php")
        .then(res => res.json())
        .then(data => {

            let html = "";

            data.forEach(t => {
                html += `
                    <tr onclick="viewTicket(${t.id})" style="cursor:pointer">

                        <td>${t.serialNumber || "-"}</td>
                        <td>${t.tagNumber || "-"}</td>
                        <td>${t.pcModel || "-"}</td>
                        <td>${t.hardwareType || "-"}</td>
                        <td>${t.branch || "-"}</td>

                        <td>
                            <span style="
                                padding:4px 8px;
                                border-radius:6px;
                                color:white;
                                background:${
                                    t.status === "Closed" ? "green" :
                                    t.status === "Active" ? "blue" : "orange"
                                }
                            ">
                                ${t.status || "Pending"}
                            </span>
                        </td>

                        <td onclick="event.stopPropagation()">

                            <button onclick="updateStatus(${t.id}, 'Pending')">Pending</button>
                            <button onclick="updateStatus(${t.id}, 'Active')">Assign</button>
                            <button onclick="updateStatus(${t.id}, 'Closed')">Close</button>

                        </td>

                    </tr>
                `;
            });

            document.getElementById("ticketsBody").innerHTML = html;
        });
}

function updateStatus(id, status) {
    fetch("/projects/PC_STATUS/backend/api/tickets/update.php", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({ id, status })
    })
    .then(res => res.json())
    .then(() => loadTickets());
}

function viewTicket(id) {
    window.location.href =
        `/projects/PC_STATUS/frontend/pages/ticket-view.php?id=${id}`;
}

loadTickets();
setInterval(loadTickets, 5000);
</script>

<?php include "../includes/footer.php"; ?>