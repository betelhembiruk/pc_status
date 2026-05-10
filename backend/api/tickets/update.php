<?php
header("Content-Type: application/json");

require_once "../../config/db.php";
require_once "../../middleware/auth.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("
    UPDATE tickets 
    SET status=?, resolution=?
    WHERE id=?
");

$stmt->bind_param(
    "ssi",
    $data["status"],
    $data["resolution"],
    $data["id"]
);

$stmt->execute();

echo json_encode(["success" => true]);
?>