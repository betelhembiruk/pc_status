<?php
header("Content-Type: application/json");

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$full_name = trim($data["full_name"] ?? "");
$password = trim($data["password"] ?? "");
$role = $data["role"] ?? "user";

if ($full_name === "" || $password === "") {
    echo json_encode(["success"=>false,"message"=>"Missing fields"]);
    exit;
}

/* NEW USERS MUST CHANGE PASSWORD */
$must_change_password = 1;

$stmt = $conn->prepare("
    INSERT INTO users (full_name, password, role, must_change_password)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("sssi", $full_name, $password, $role, $must_change_password);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "User created successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => $stmt->error
    ]);
}