<?php
header("Content-Type: application/json");

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../middleware/auth.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid input"
    ]);
    exit;
}

$user = $_SESSION["user"];

// OPTIONAL SECURITY RULE (recommended)
if ($user["role"] === "user") {
    echo json_encode([
        "success" => false,
        "message" => "No permission"
    ]);
    exit;
}

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

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>