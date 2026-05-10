<?php
header("Content-Type: application/json");
require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$id = $_POST['id'];

$conn->query("DELETE FROM users WHERE id=$id");

echo json_encode(["success"=>true]);