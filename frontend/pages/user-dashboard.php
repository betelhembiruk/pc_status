<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header("Location: /projects/PC_STATUS/frontend/login.php");
    exit;
}

$userId = $user['id'];
?>

<div style="margin-left:220px; padding:20px; background:#f4f4f7; min-height:100vh;">

<h2 style="color:#95298e;">My Dashboard</h2>

<?php
/* ================= FETCH USER TICKETS ================= */
$stmt = $conn->prepare("
    SELECT * FROM tickets
    WHERE assigned_to = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}

/* ================= STATS ================= */
$total = count($tickets);
$closed = 0;
$active = 0;
$overdue = 0;

foreach ($tickets as $t) {

    if ($t['status'] === 'Closed') {
        $closed++;
    } else {
        $active++;
    }

    $created = strtotime($t['created_at']);
    $days = (time() - $created) / (60 * 60 * 24);

    if ($days > 7 && $t['status'] !== 'Closed') {
        $overdue++;
    }
}
?>

<!-- ================= STATS CARDS ================= -->
<div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:20px;">

<div style="background:#3b82f6;color:white;padding:20px;border-radius:10px;flex:1;">
<h3>Total</h3>
<h2><?= $total ?></h2>
</div>

<div style="background:#f59e0b;color:white;padding:20px;border-radius:10px;flex:1;">
<h3>Active</h3>
<h2><?= $active ?></h2>
</div>

<div style="background:#10b981;color:white;padding:20px;border-radius:10px;flex:1;">
<h3>Closed</h3>
<h2><?= $closed ?></h2>
</div>

<div style="background:#ef4444;color:white;padding:20px;border-radius:10px;flex:1;">
<h3>Overdue</h3>
<h2><?= $overdue ?></h2>
</div>

</div>

<!-- ================= TABLE ================= -->
<div style="background:white;padding:15px;border-radius:10px;">

<h3>My Assigned Tickets</h3>

<table style="width:100%;border-collapse:collapse;">
<thead>
<tr style="text-align:left;border-bottom:1px solid #ddd;">
    <th>Tag</th>
    <th>Model</th>
    <th>Branch</th>
    <th>Status</th>
    <th>Created</th>
    <th>Returned At</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php foreach ($tickets as $t): ?>

<?php
$created = strtotime($t['created_at']);
$days = (time() - $created) / (60 * 60 * 24);
$isOverdue = ($days > 7 && $t['status'] !== 'Closed');
?>

<tr style="<?= $isOverdue ? 'background:#ffe4e6;' : '' ?>">

    <td><?= $t['tagNumber'] ?></td>
    <td><?= $t['pcModel'] ?></td>
    <td><?= $t['branch'] ?></td>

    <td>
        <span style="
            padding:5px 10px;
            border-radius:20px;
            color:white;
            background:
                <?= $t['status']=='Closed'?'#10b981':($t['status']=='Active'?'#3b82f6':'#f59e0b') ?>
        ">
            <?= $t['status'] ?>
        </span>
    </td>

    <td><?= date("Y-m-d", strtotime($t['created_at'])) ?></td>

    <!-- ✅ ADDED RETURNED AT -->
    <td>
        <?= !empty($t['returned_at'])
            ? date("Y-m-d", strtotime($t['returned_at']))
            : "Not returned yet"
        ?>
    </td>

    <td>
        <a href="/projects/PC_STATUS/frontend/pages/ticket-view.php?id=<?= $t['id'] ?>"
           style="background:#95298e;color:white;padding:5px 10px;border-radius:5px;text-decoration:none;">
           View / Edit
        </a>
    </td>

</tr>

<?php endforeach; ?>

</tbody>
</table>

</div>

</div>

<?php include "../includes/footer.php"; ?>