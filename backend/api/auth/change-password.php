<?php
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    echo json_encode(["success"=>false,"message"=>"Invalid JSON"]);
    exit;
}

$user_id = $data["user_id"] ?? null;
$current_password = $data["current_password"] ?? "";
$new_password = $data["new_password"] ?? "";
$confirm_password = $data["confirm_password"] ?? "";

/* ================= VALIDATION ================= */
if (!$user_id || !$current_password || !$new_password || !$confirm_password) {
    echo json_encode(["success"=>false,"message"=>"All fields required"]);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(["success"=>false,"message"=>"Passwords do not match"]);
    exit;
}

/* ================= GET USER ================= */
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode(["success"=>false,"message"=>"User not found"]);
    exit;
}

/* ================= CHECK CURRENT PASSWORD ================= */
if ($current_password !== $user["password"]) {
    echo json_encode(["success"=>false,"message"=>"Current password is wrong"]);
    exit;
}

/* ================= PREVENT SAME PASSWORD ================= */
if ($current_password === $new_password) {
    echo json_encode(["success"=>false,"message"=>"New password cannot be same as old password"]);
    exit;
}

/* ================= UPDATE PASSWORD ================= */
$stmt = $conn->prepare("
    UPDATE users 
    SET password=?, must_change_password=0 
    WHERE id=?
");

$stmt->bind_param("si", $new_password, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "success"=>true,
        "message"=>"Password updated successfully"
    ]);
} else {
    echo json_encode([
        "success"=>false,
        "message"=>$stmt->error
    ]);
}