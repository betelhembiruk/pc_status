<?php
header("Content-Type: application/json");

// Show PHP errors while debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
| IMPORTANT:
| This uses an absolute path to avoid relative path issues.
*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION["user"]["id"])) {
    echo json_encode([
        "success" => false,
        "message" => "You must login first."
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Read JSON Input
|--------------------------------------------------------------------------
*/
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON data received.",
        "raw" => $raw
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Form Fields
|--------------------------------------------------------------------------
| Same field names used in your frontend form.
*/
$serialNumber = trim($data["serialNumber"] ?? "");
$tagNumber    = trim($data["tagNumber"] ?? "");
$pcModel      = trim($data["pcModel"] ?? "");
$branch       = trim($data["branch"] ?? "");
$problem      = trim($data["problem"] ?? "");
$phone        = trim($data["phone"] ?? "");
$broughtBy    = trim($data["broughtBy"] ?? "");
$hardwareType = trim($data["hardwareType"] ?? "PC");

/*
|--------------------------------------------------------------------------
| Default Values
|--------------------------------------------------------------------------
*/
$status   = "Pending";
$priority = "Medium";
$slaDays  = 3;

$createdBy = (int) $_SESSION["user"]["id"];

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/
if ($serialNumber === "" || $branch === "" || $problem === "") {
    echo json_encode([
        "success" => false,
        "message" => "Required fields missing: Serial Number, Branch, and Problem."
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| SQL Insert
|--------------------------------------------------------------------------
*/
$sql = "
    INSERT INTO tickets (
        serialNumber,
        tagNumber,
        pcModel,
        branch,
        issue,
        phone,
        broughtBy,
        hardwareType,
        status,
        priority,
        slaDays,
        created_by
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Bind Parameters
|--------------------------------------------------------------------------
*/
$stmt->bind_param(
    "ssssssssssii",
    $serialNumber,
    $tagNumber,
    $pcModel,
    $branch,
    $problem,
    $phone,
    $broughtBy,
    $hardwareType,
    $status,
    $priority,
    $slaDays,
    $createdBy
);

/*
|--------------------------------------------------------------------------
| Execute Query
|--------------------------------------------------------------------------
*/
if ($stmt->execute()) {
    echo json_encode([
        "success"   => true,
        "message"   => "Ticket created successfully!",
        "ticket_id" => $conn->insert_id
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Execute failed: " . $stmt->error
    ]);
}

/*
|--------------------------------------------------------------------------
| Cleanup
|--------------------------------------------------------------------------
*/
$stmt->close();
$conn->close();
?>