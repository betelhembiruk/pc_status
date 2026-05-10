<?php
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$result = $conn->query("
SELECT 
    u.id,
    u.full_name,
    u.role,
    u.status,
    u.created_at,
    u.last_login,

    COUNT(t.id) AS total_tickets,
    SUM(t.status = 'Active') AS active_tickets,
    SUM(t.status = 'Pending') AS pending_tickets,
    SUM(t.status = 'Closed') AS closed_tickets

FROM users u
LEFT JOIN tickets t ON t.assigned_to = u.id
GROUP BY u.id
ORDER BY u.id DESC
");

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $row['status'] = $row['status'] ?? 'active';
    $row['total_tickets'] = (int)($row['total_tickets'] ?? 0);
    $row['active_tickets'] = (int)($row['active_tickets'] ?? 0);
    $row['pending_tickets'] = (int)($row['pending_tickets'] ?? 0);
    $row['closed_tickets'] = (int)($row['closed_tickets'] ?? 0);

    $data[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $data
]);
?>