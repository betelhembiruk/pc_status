<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>

<div class="content">

    <h2>Tickets</h2>

    <table>
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

<script src="../assets/js/tickets.js"></script>

<?php include "../includes/footer.php"; ?>