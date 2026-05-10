<?php
session_start();
header("Content-Type: application/json");

require_once __DIR__ . "/../../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"] ?? "";
$password = $data["password"] ?? "";

if (!$email || !$password) {
    echo json_encode(["success"=>false,"message"=>"Missing fields"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if ($user && $password === $user["password"]) {

    $_SESSION["user"] = $user;

    echo json_encode(["success"=>true,"user"=>$user]);

} else {
    echo json_encode(["success"=>false,"message"=>"Invalid email or password"]);
}
?>