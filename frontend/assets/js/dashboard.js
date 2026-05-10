function loadStats() {
    fetch("/projects/PC_STATUS/backend/api/tickets/stats.php")
        .then(res => res.json())
        .then(data => {
            document.getElementById("total").innerText = data.total;
            document.getElementById("open").innerText = data.pending;
            document.getElementById("progress").innerText = data.active;
            document.getElementById("resolved").innerText = data.closed;
        });
}

loadStats();
setInterval(loadStats, 5000);