<?php
header("Content-Type: application/json");

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"] ?? null;
$status = $data["status"] ?? null;

if (!$id || !$status) {
    echo json_encode(["success" => false]);
    exit;
}

$stmt = $conn->prepare("UPDATE tickets SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);

echo json_encode([
    "success" => $stmt->execute()
]);
?>