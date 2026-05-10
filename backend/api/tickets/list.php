<?php
header("Content-Type: application/json");
ini_set('display_errors', 0);
error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$sql = "SELECT * FROM tickets ORDER BY id DESC";
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
        "phone" => $row["phone"],
        "broughtBy" => $row["broughtBy"],
        "hardwareType" => $row["hardwareType"] ?? "PC",
        "status" => $row["status"] ?? "Pending",
        "createdAt" => $row["created_at"] ?? null,
        "returnedAt" => $row["returned_at"] ?? null
    ];
}

echo json_encode($data);