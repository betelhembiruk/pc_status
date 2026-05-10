
fetch("/projects/PC_STATUS/backend/api/dashboard/stats.php")
.then(res => res.json())
.then(data => {

    document.getElementById("total").innerText = data.total;
    document.getElementById("open").innerText = data.open;
    document.getElementById("progress").innerText = data.progress;
    document.getElementById("resolved").innerText = data.resolved;


    

    const ctx = document.getElementById("chart").getContext("2d");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["Open", "In Progress", "Resolved"],
            datasets: [{
                label: "Tickets",
                data: [data.open, data.progress, data.resolved]
            }]
        }
    });
});