function loadTickets() {
    fetch("/projects/PC_STATUS/backend/api/tickets/list.php")
        .then(res => res.json())
        .then(data => {

            let html = "";

            data.forEach(t => {
                html += `
                    <tr>
                        <td>${t.serialNumber || "-"}</td>
                        <td>${t.tagNumber || "-"}</td>
                        <td>${t.pcModel || "-"}</td>
                        <td>${t.hardwareType || "-"}</td>
                        <td>${t.branch || "-"}</td>
                        <td>
                            <span style="
                                padding:4px 8px;
                                border-radius:6px;
                                background:${t.status === 'Closed' ? '#28a745' : '#ff9800'};
                                color:white;
                                font-size:12px;
                            ">
                                ${t.status || "Pending"}
                            </span>
                        </td>
                    </tr>
                `;
            });

            document.getElementById("ticketsBody").innerHTML = html;
        });
}

// initial load
loadTickets();

// auto refresh every 5 seconds
setInterval(loadTickets, 5000);