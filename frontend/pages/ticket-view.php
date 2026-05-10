<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

/* ================= GET USER ================= */
$user = $_SESSION['user'] ?? null;

if (!$user) {
    header("Location: /projects/PC_STATUS/frontend/login.php");
    exit;
}

$role = $user['role'];
$userId = $user['id'];

$id = $_GET['id'] ?? 0;

/* ================= GET TICKET ================= */
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

/* ================= SECURITY CHECK ================= */
if (!$ticket) {
    echo "Ticket not found";
    exit;
}

/* 👇 USERS CAN ONLY OPEN THEIR ASSIGNED TICKETS */
if ($role === "user" && $ticket['assigned_to'] != $userId) {
    echo "🚫 You are not allowed to access this ticket";
    exit;
}
?>

<div style="margin-left:220px; padding:20px; background:#f3f4f6; min-height:100vh;">

<h2 style="color:#95298e;">Ticket View / Edit</h2>

<form id="ticketForm">

<input type="hidden" name="id" value="<?= $ticket['id'] ?>">

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:15px;">

<!-- ================= BASIC INFO ================= -->
<div style="background:white;padding:15px;border-radius:10px;">
<h3>Basic Information</h3>

<input name="serialNumber" value="<?= $ticket['serialNumber'] ?>"><br><br>
<input name="tagNumber" value="<?= $ticket['tagNumber'] ?>"><br><br>
<input name="pcModel" value="<?= $ticket['pcModel'] ?>"><br><br>
<input name="branch" value="<?= $ticket['branch'] ?>"><br><br>
<input name="problem" value="<?= $ticket['issue'] ?>"><br><br>
<input name="phone" value="<?= $ticket['phone'] ?>"><br><br>
<input name="broughtBy" value="<?= $ticket['broughtBy'] ?>"><br><br>

</div>

<!-- ================= RETURN DETAILS ================= -->
<div style="background:white;padding:15px;border-radius:10px;">
<h3>Return Details</h3>

<select name="status">
    <option value="Pending" <?= $ticket['status']=="Pending"?"selected":"" ?>>Pending</option>
    <option value="Active" <?= $ticket['status']=="Active"?"selected":"" ?>>Active</option>
    <option value="Closed" <?= $ticket['status']=="Closed"?"selected":"" ?>>Closed</option>
</select><br><br>

<input name="returnedBy" value="<?= $ticket['returnedBy'] ?? '' ?>"><br><br>
<input name="returnedPerson" value="<?= $ticket['returnedPerson'] ?? '' ?>"><br><br>

</div>

<!-- ================= MAINTENANCE ================= -->
<div style="background:white;padding:15px;border-radius:10px;">
<h3>Maintenance</h3>

<label>
    <input type="checkbox" name="maintenanceDone"
    <?= !empty($ticket['maintenanceType']) ? "checked" : "" ?>>
    Maintenance Done
</label><br><br>

<input name="maintenanceType" value="<?= $ticket['maintenanceType'] ?? '' ?>"><br><br>

<textarea name="maintenanceNotes"><?= $ticket['maintenanceNotes'] ?? '' ?></textarea><br><br>

<textarea name="maintenanceReasonNotDone"><?= $ticket['maintenanceReasonNotDone'] ?? '' ?></textarea>

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

    formData.set(
        "maintenanceDone",
        document.querySelector("input[name='maintenanceDone']").checked ? 1 : 0
    );

    fetch("/projects/PC_STATUS/backend/api/tickets/update-full.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            window.location.href =
                "/projects/PC_STATUS/frontend/pages/tickets.php";
        } else {
            alert(data.message || "Update failed");
        }

    });
});
</script>

<?php include "../includes/footer.php"; ?>