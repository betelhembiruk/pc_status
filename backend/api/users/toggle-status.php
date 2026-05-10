<?php
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

/* Only allow POST requests */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
    exit;
}

/* Get user ID */
$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode([
        "success" => false,
        "message" => "Missing user ID"
    ]);
    exit;
}

$id = (int)$id;

/* Get current status */
$stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
    exit;
}

/* Determine new status */
$currentStatus = $user['status'] ?? 'active';
$newStatus = ($currentStatus === 'active') ? 'disabled' : 'active';

/* Update status */
$update = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
if (!$update) {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
    exit;
}

$update->bind_param("si", $newStatus, $id);

if ($update->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "User status updated successfully",
        "id" => $id,
        "old_status" => $currentStatus,
        "new_status" => $newStatus
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => $update->error
    ]);
}

$update->close();
$stmt->close();
$conn->close();
?>