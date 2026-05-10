<?php
header("Content-Type: application/json");

require_once "../../config/db.php";
require_once "../../middleware/auth.php";
require_once "../../middleware/role.php";

requireRole(["admin", "super_admin"]);

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("
    UPDATE tickets 
    SET assigned_to=? 
    WHERE id=?
");

$stmt->bind_param("ii", $data["user_id"], $data["ticket_id"]);

$stmt->execute();

echo json_encode(["success" => true]);
?>