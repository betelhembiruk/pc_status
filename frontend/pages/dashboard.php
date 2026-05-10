<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>

<div class="content">

    <h2>Dashboard</h2>

    <div class="grid">

        <div class="card">
            <h3>Total Tickets</h3>
            <p id="total">0</p>
        </div>

        <div class="card">
            <h3>Open</h3>
            <p id="open">0</p>
        </div>

        <div class="card">
            <h3>In Progress</h3>
            <p id="progress">0</p>
        </div>

        <div class="card">
            <h3>Resolved</h3>
            <p id="resolved">0</p>
        </div>

    </div>

    <canvas id="chart" width="400" height="150"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/dashboard.js"></script>

<?php include "../includes/footer.php"; ?>