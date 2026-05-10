fetch("../../backend/api/tickets/list.php")
.then(res => res.json())
.then(data => {

    let html = "";

    data.forEach(t => {
        html += `
        <tr>
            <td>${t.serialNumber}</td>
            <td>${t.tagNumber}</td>
            <td>${t.pcModel}</td>
            <td>${t.hardwareType}</td>
            <td>${t.branch}</td>
            <td>${t.status}</td>
        </tr>
        `;
    });

    document.getElementById("ticketsBody").innerHTML = html;
});