<?php
session_start();
header("Content-Type: application/json");

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$user = $_SESSION['user'] ?? null;

if (!$user) {
    echo json_encode([]);
    exit;
}

$role = $user['role'];
$userId = $user['id'];

/* ================= ALL ROLES ================= */
$sql = "
    SELECT 
        t.id,
        t.serialNumber,
        t.tagNumber,
        t.pcModel,
        t.branch,
        t.issue,
        t.status,
        t.created_at,
        t.returned_at,
        t.assigned_to,

        u.full_name AS assignedToName

    FROM tickets t
    LEFT JOIN users u ON t.assigned_to = u.id
    ORDER BY t.id DESC
";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {

    $data[] = [
        "id" => $row["id"],
        "serialNumber" => $row["serialNumber"],
        "tagNumber" => $row["tagNumber"],
        "pcModel" => $row["pcModel"],
        "branch" => $row["branch"],
        "problem" => $row["issue"],

        "status" => $row["status"],
        "createdAt" => $row["created_at"],
        "returnedAt" => $row["returned_at"],

        /* 🔥 IMPORTANT FIX */
        "assigned_to" => $row["assigned_to"],
        "assignedToName" => $row["assignedToName"] ?? null
    ];
}

echo json_encode($data);
?>