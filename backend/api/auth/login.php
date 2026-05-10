<?php
session_start();
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    echo json_encode(["success"=>false,"message"=>"Invalid JSON"]);
    exit;
}

$full_name = trim($data["full_name"] ?? "");
$password  = trim($data["password"] ?? "");

if ($full_name === "" || $password === "") {
    echo json_encode(["success"=>false,"message"=>"Missing fields"]);
    exit;
}

/* ================= FIND USER ================= */
$stmt = $conn->prepare("SELECT * FROM users WHERE full_name=?");

if (!$stmt) {
    echo json_encode(["success"=>false,"message"=>$conn->error]);
    exit;
}

$stmt->bind_param("s", $full_name);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode(["success"=>false,"message"=>"User not found"]);
    exit;
}

/* ================= PASSWORD CHECK ================= */
if ($password !== $user["password"]) {
    echo json_encode(["success"=>false,"message"=>"Wrong password"]);
    exit;
}

/* ================= SESSION ================= */
$_SESSION["user"] = $user;

/* ================= LAST LOGIN ================= */
$conn->query("UPDATE users SET last_login = NOW() WHERE id={$user['id']}");

/* ================= ROLE LOGIC ================= */
$forceChange =
    ($user["role"] !== "super_admin") &&
    ($user["must_change_password"] == 1);

/* ================= RESPONSE ================= */
echo json_encode([
    "success" => true,
    "must_change_password" => $forceChange,
    "user" => [
        "id" => $user["id"],
        "full_name" => $user["full_name"],
        "role" => $user["role"]
    ]
]);