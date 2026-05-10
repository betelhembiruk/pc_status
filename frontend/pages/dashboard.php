<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>
<?php include "../includes/topbar.php"; ?>

<div class="content" style="margin-left:260px;padding:20px;background:#f3f4f6;min-height:100vh;">

<h2>Dashboard</h2>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">

    <div style="background:white;padding:20px;border-radius:10px;">
        <h4>Total</h4>
        <h2 id="total">0</h2>
    </div>

    <div style="background:white;padding:20px;border-radius:10px;">
        <h4>Open</h4>
        <h2 id="open">0</h2>
    </div>

    <div style="background:white;padding:20px;border-radius:10px;">
        <h4>In Progress</h4>
        <h2 id="progress">0</h2>
    </div>

    <div style="background:white;padding:20px;border-radius:10px;">
        <h4>Resolved</h4>
        <h2 id="resolved">0</h2>
    </div>

</div>

</div>

<script src="../assets/js/realtime.js"></script>

<?php include "../includes/footer.php"; ?>