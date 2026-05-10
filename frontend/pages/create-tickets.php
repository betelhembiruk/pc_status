<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>

<div class="content">

<h2>Create Ticket</h2>

<form id="ticketForm">

<input name="serialNumber" placeholder="Serial Number">
<input name="tagNumber" placeholder="Tag Number">
<input name="pcModel" placeholder="PC Model">
<input name="hardwareType" placeholder="Hardware Type">
<input name="branch" placeholder="Branch">
<textarea name="issue" placeholder="Issue"></textarea>

<select name="priority">
    <option>Low</option>
    <option>Medium</option>
    <option>High</option>
    <option>Critical</option>
</select>

<button type="submit">Create</button>

</form>

</div>

<script>
document.getElementById("ticketForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(e.target));

    const res = await fetch("../../backend/api/tickets/create.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(formData)
    });

    const data = await res.json();

    alert("Ticket Created ID: " + data.ticket_id);
});
</script>

<?php include "../includes/footer.php"; ?>