<?php
header("Content-Type: application/json");

require_once "../../config/db.php";
require_once "../../middleware/auth.php";
require_once "../../middleware/role.php";

requireRole(["super_admin"]);

$data = json_decode(file_get_contents("php://input"), true);

$hash = password_hash($data["password"], PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users(full_name,email,password,role) VALUES (?,?,?,?)");
$stmt->bind_param("ssss",
    $data["full_name"],
    $data["email"],
    $hash,
    $data["role"]
);

$stmt->execute();

echo json_encode(["success" => true]);
?>