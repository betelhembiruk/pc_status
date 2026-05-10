<?php
header("Content-Type: application/json");

require_once "../../config/db.php";
require_once "../../middleware/auth.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("
    INSERT INTO tickets 
    (serialNumber, tagNumber, pcModel, hardwareType, branch, issue, priority, created_by)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssssi",
    $data["serialNumber"],
    $data["tagNumber"],
    $data["pcModel"],
    $data["hardwareType"],
    $data["branch"],
    $data["issue"],
    $data["priority"],
    $_SESSION["user"]["id"]
);

$stmt->execute();

echo json_encode([
    "success" => true,
    "ticket_id" => $conn->insert_id
]);
?>