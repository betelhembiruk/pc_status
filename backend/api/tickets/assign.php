<?php
header("Content-Type: application/json");
require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$ticket_id = $_POST['ticket_id'];
$user_id = $_POST['user_id'];

$stmt = $conn->prepare("
    UPDATE tickets 
    SET assigned_to=? 
    WHERE id=?
");

$stmt->bind_param("ii", $user_id, $ticket_id);

echo json_encode(["success"=>$stmt->execute()]);