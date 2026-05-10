<?php
header("Content-Type: application/json");

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../middleware/auth.php";

$data = json_decode(file_get_contents("php://input"), true);

if ($_SESSION["user"]["role"] !== "super_admin") {
    echo json_encode(["success"=>false,"message"=>"No permission"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO users(full_name,email,password,role)
    VALUES (?,?,?,?)
");

$stmt->bind_param(
    "ssss",
    $data["full_name"],
    $data["email"],
    $data["password"],
    $data["role"]
);

echo json_encode(["success"=>$stmt->execute()]);
?>