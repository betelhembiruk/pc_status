<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>
<?php include "../includes/topbar.php"; ?>

<div class="content" style="margin-left:260px;padding:20px;">

<h2>Tickets</h2>

<table border="1" width="100%" style="background:white;">
    <thead>
        <tr>
            <th>Serial</th>
            <th>Tag</th>
            <th>Model</th>
            <th>Type</th>
            <th>Branch</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody id="ticketsBody"></tbody>
</table>

</div>

<script src="../assets/js/realtime.js"></script>

<?php include "../includes/footer.php"; ?>