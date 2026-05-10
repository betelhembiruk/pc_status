<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../../config/db.php";

$total = $conn->query("SELECT COUNT(*) as c FROM tickets")->fetch_assoc()['c'];
$open = $conn->query("SELECT COUNT(*) as c FROM tickets WHERE status='open'")->fetch_assoc()['c'];
$progress = $conn->query("SELECT COUNT(*) as c FROM tickets WHERE status='in_progress'")->fetch_assoc()['c'];
$resolved = $conn->query("SELECT COUNT(*) as c FROM tickets WHERE status='resolved'")->fetch_assoc()['c'];

echo json_encode([
    "total" => $total,
    "open" => $open,
    "progress" => $progress,
    "resolved" => $resolved
]);
?>