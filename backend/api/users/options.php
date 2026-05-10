<?php
header("Content-Type: application/json");

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$result = $conn->query("
    SELECT id, full_name, role
    FROM users
    WHERE status = 'active'
    ORDER BY full_name ASC
");

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $users
]);
?>