<?php
header("Content-Type: application/json");

require_once "../../config/db.php";
require_once "../../middleware/auth.php";

$ticket_id = $_GET["ticket_id"];

$stmt = $conn->prepare("
    SELECT * FROM attachments WHERE ticket_id=?
");

$stmt->bind_param("i", $ticket_id);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>