<?php
header("Content-Type: application/json");

require_once "../../config/db.php";
require_once "../../middleware/auth.php";

$id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM tickets WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

echo json_encode($result->fetch_assoc());
?>