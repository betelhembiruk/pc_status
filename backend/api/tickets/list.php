<?php
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$sql = "SELECT * FROM tickets ORDER BY id DESC";
$result = $conn->query($sql);

$tickets = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}

echo json_encode($tickets);
?>