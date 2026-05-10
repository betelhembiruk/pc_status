<?php
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

/* =========================
   AUTH CHECK
========================= */
if (!isset($_SESSION["user"]["id"])) {
    echo json_encode([
        "success" => false,
        "message" => "You must login first."
    ]);
    exit;
}

/* =========================
   READ INPUT
========================= */
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON",
        "raw" => $raw
    ]);
    exit;
}

/* =========================
   FIELDS (MATCH FRONTEND)
========================= */
$serialNumber = trim($data["serialNumber"] ?? "");
$tagNumber    = trim($data["tagNumber"] ?? "");
$pcModel      = trim($data["pcModel"] ?? "");
$branch       = trim($data["branch"] ?? "");
$problem      = trim($data["problem"] ?? "");
$phone        = trim($data["phone"] ?? "");
$broughtBy    = trim($data["broughtBy"] ?? "");
$hardwareType = trim($data["hardwareType"] ?? "PC");

$createdBy = (int) $_SESSION["user"]["id"];

/* =========================
   VALIDATION
========================= */
if ($serialNumber === "" || $branch === "" || $problem === "") {
    echo json_encode([
        "success" => false,
        "message" => "Serial Number, Branch and Problem are required"
    ]);
    exit;
}

/* =========================
   STATUS LOGIC (IMPORTANT)
   - NEW TICKET = Active
========================= */
$status = "Active";

/* =========================
   INSERT QUERY
========================= */
$sql = "INSERT INTO tickets (
    serialNumber,
    tagNumber,
    pcModel,
    branch,
    issue,
    phone,
    broughtBy,
    hardwareType,
    status,
    created_by
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "SQL Error: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "sssssssssi",
    $serialNumber,
    $tagNumber,
    $pcModel,
    $branch,
    $problem,
    $phone,
    $broughtBy,
    $hardwareType,
    $status,
    $createdBy
);

/* =========================
   EXECUTE
========================= */
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Ticket created successfully",
        "ticket_id" => $conn->insert_id,
        "status" => $status
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Insert failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>