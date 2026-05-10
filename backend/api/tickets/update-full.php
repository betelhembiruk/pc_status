<?php
header("Content-Type: application/json");

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

$user = $_SESSION['user'] ?? null;

if (!$user) {
    echo json_encode(["success"=>false,"message"=>"Not logged in"]);
    exit;
}

$role = $user['role'];
$userId = $user['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success"=>false,"message"=>"Invalid request"]);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(["success"=>false,"message"=>"Missing ID"]);
    exit;
}

/* ================= GET TICKET ================= */
$stmt = $conn->prepare("SELECT assigned_to FROM tickets WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if (!$ticket) {
    echo json_encode(["success"=>false,"message"=>"Ticket not found"]);
    exit;
}

/* ================= USER RESTRICTION ================= */
if ($role === "user") {
    if ($ticket['assigned_to'] != $userId) {
        echo json_encode([
            "success"=>false,
            "message"=>"You can only edit tickets assigned to you"
        ]);
        exit;
    }
}

/* ===================================================
   1. ASSIGN (RUN FIRST 🔥 FIX)
=================================================== */
$assignedTo = $_POST['assignedTo'] ?? null;

if ($assignedTo !== null) {

    if ($role === "user") {
        echo json_encode(["success"=>false,"message"=>"Not allowed"]);
        exit;
    }

    $stmt = $conn->prepare("
        UPDATE tickets 
        SET assigned_to=?, status='Pending'
        WHERE id=?
    ");

    $stmt->bind_param("ii", $assignedTo, $id);
    $stmt->execute();

    echo json_encode([
        "success"=>true,
        "message"=>"Ticket assigned"
    ]);
    exit;
}

/* ===================================================
   2. STATUS UPDATE
=================================================== */
$status = $_POST['status'] ?? null;

if ($status) {

    if ($status === "Closed") {
        $stmt = $conn->prepare("
            UPDATE tickets 
            SET status=?, returned_at=NOW() 
            WHERE id=?
        ");
    } else {
        $stmt = $conn->prepare("
            UPDATE tickets 
            SET status=?, returned_at=NULL 
            WHERE id=?
        ");
    }

    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    echo json_encode(["success"=>true,"message"=>"Status updated"]);
    exit;
}

/* ===================================================
   3. FULL EDIT
=================================================== */
$serialNumber = $_POST['serialNumber'] ?? '';
$tagNumber = $_POST['tagNumber'] ?? '';
$pcModel = $_POST['pcModel'] ?? '';
$branch = $_POST['branch'] ?? '';
$issue = $_POST['problem'] ?? '';
$phone = $_POST['phone'] ?? '';
$broughtBy = $_POST['broughtBy'] ?? '';

$maintenanceType = $_POST['maintenanceType'] ?? '';
$maintenanceNotes = $_POST['maintenanceNotes'] ?? '';
$maintenanceReasonNotDone = $_POST['maintenanceReasonNotDone'] ?? '';

$stmt = $conn->prepare("
UPDATE tickets SET
serialNumber=?,
tagNumber=?,
pcModel=?,
branch=?,
issue=?,
phone=?,
broughtBy=?,
maintenanceType=?,
maintenanceNotes=?,
maintenanceReasonNotDone=?
WHERE id=?
");

$stmt->bind_param(
"ssssssssssi",
$serialNumber,
$tagNumber,
$pcModel,
$branch,
$issue,
$phone,
$broughtBy,
$maintenanceType,
$maintenanceNotes,
$maintenanceReasonNotDone,
$id
);

if ($stmt->execute()) {
    echo json_encode(["success"=>true,"message"=>"Updated successfully"]);
} else {
    echo json_encode(["success"=>false,"message"=>$stmt->error]);
}

$stmt->close();
$conn->close();
?>