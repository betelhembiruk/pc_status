<?php
include "../includes/header.php";
include "../includes/sidebar.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM tickets WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$ticket = $result->fetch_assoc();
?>

<div style="margin-left:220px; padding:20px; background:#f3f4f6; min-height:100vh;">

<h2>Ticket View / Edit</h2>

<form id="ticketForm">

<input type="hidden" name="id" value="<?= $ticket['id'] ?>">

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:15px;">

<!-- ================= BASIC INFO ================= -->
<div style="background:white;padding:15px;border-radius:10px;">
<h3>Basic Information</h3>

<input name="serialNumber" placeholder="Serial Number"
value="<?= $ticket['serialNumber'] ?>"><br><br>

<input name="tagNumber" placeholder="Tag Number"
value="<?= $ticket['tagNumber'] ?>"><br><br>

<input name="pcModel" placeholder="PC Model"
value="<?= $ticket['pcModel'] ?>"><br><br>

<input name="branch" placeholder="Branch"
value="<?= $ticket['branch'] ?>"><br><br>

<input name="problem" placeholder="Problem"
value="<?= $ticket['issue'] ?>"><br><br>

<input name="phone" placeholder="Phone"
value="<?= $ticket['phone'] ?>"><br><br>

<input name="broughtBy" placeholder="Brought By"
value="<?= $ticket['broughtBy'] ?>"><br><br>

</div>

<!-- ================= RETURN DETAILS ================= -->
<div style="background:white;padding:15px;border-radius:10px;">
<h3>Return Details</h3>

<select name="status">
    <option value="Pending" <?= $ticket['status']=="Pending"?"selected":"" ?>>Pending</option>
    <option value="Active" <?= $ticket['status']=="Active"?"selected":"" ?>>Active</option>
    <option value="Closed" <?= $ticket['status']=="Closed"?"selected":"" ?>>Closed</option>
</select><br><br>

<input name="returnedBy" placeholder="Returned By"><br><br>
<input name="returnedPerson" placeholder="Returned Person"><br><br>

</div>

<!-- ================= MAINTENANCE ================= -->
<div style="background:white;padding:15px;border-radius:10px;">
<h3>Maintenance</h3>

<label>
    <input type="checkbox" name="maintenanceDone">
    Maintenance Done
</label><br><br>

<input name="maintenanceType" placeholder="What was fixed?"><br><br>

<textarea name="maintenanceNotes" placeholder="Maintenance Notes"></textarea><br><br>

<textarea name="maintenanceReasonNotDone" placeholder="Why not maintained?"></textarea><br><br>

</div>

</div>

<br>

<button type="submit" style="
    background:#95298e;
    color:white;
    padding:12px;
    border:none;
    border-radius:8px;
    width:100%;
    font-weight:bold;
">
Save Changes
</button>

</form>

</div>

<script>
document.getElementById("ticketForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("/projects/PC_STATUS/backend/api/tickets/update-full.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            alert("Ticket updated successfully!");

            window.location.href =
                "/projects/PC_STATUS/frontend/pages/tickets.php";
        } else {
            alert(data.message || "Update failed");
        }

    })
    .catch(err => {
        console.log(err);
        alert("Server error");
    });
});
</script>

<?php include "../includes/footer.php"; ?>