<?php
header("Content-Type: application/json");

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$total = $conn->query("SELECT COUNT(*) as c FROM tickets")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) as c FROM tickets WHERE status='Pending'")->fetch_assoc()['c'];
$active = $conn->query("SELECT COUNT(*) as c FROM tickets WHERE status='Active'")->fetch_assoc()['c'];
$closed = $conn->query("SELECT COUNT(*) as c FROM tickets WHERE status='Closed'")->fetch_assoc()['c'];

echo json_encode([
    "total" => $total,
    "pending" => $pending,
    "active" => $active,
    "closed" => $closed
]);
?>