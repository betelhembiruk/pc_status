<?php
header("Content-Type: application/json");

require_once "../../config/db.php";
require_once "../../middleware/auth.php";

$user = $_SESSION["user"];

if ($user["role"] === "user") {
    $stmt = $conn->prepare("
        SELECT * FROM tickets 
        WHERE created_by=? OR assigned_to=?
        ORDER BY id DESC
    ");
    $stmt->bind_param("ii", $user["id"], $user["id"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM tickets ORDER BY id DESC");
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>