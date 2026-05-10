<?php
header("Content-Type: application/json");

require_once "../../config/db.php";
require_once "../../middleware/auth.php";
require_once "../../middleware/role.php";

requireRole(["admin", "super_admin"]);

$id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM tickets WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(["success" => true]);
?>