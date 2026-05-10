<?php
header("Content-Type: application/json");

ini_set('display_errors', 0);
error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

session_start();

/* ================= SECURITY ================= */
$user = $_SESSION['user'] ?? null;

if (!$user) {
    echo json_encode(["success"=>false,"message"=>"Not logged in"]);
    exit;
}

$role = $user['role'];

/* 👤 USERS CANNOT UPDATE */
if ($role === "user") {
    echo json_encode([
        "success"=>false,
        "message"=>"Permission denied"
    ]);
    exit;
}

/* ================= REQUEST ================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success"=>false,"message"=>"Invalid request"]);
    exit;
}

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;
$assignedTo = $_POST['assignedTo'] ?? null;

if (!$id) {
    echo json_encode(["success"=>false,"message"=>"Missing ID"]);
    exit;
}

/* ===================================================
   1. ASSIGN TICKET (FIXED FIRST)
=================================================== */
if ($assignedTo !== null && $assignedTo !== '') {

    $stmt = $conn->prepare("
        UPDATE tickets 
        SET assigned_to=?, status='Pending'
        WHERE id=?
    ");

    $stmt->bind_param("ii", $assignedTo, $id);

    if ($stmt->execute()) {
        echo json_encode([
            "success"=>true,
            "message"=>"Ticket assigned",
            "assigned_to"=>$assignedTo
        ]);
    } else {
        echo json_encode([
            "success"=>false,
            "message"=>$stmt->error
        ]);
    }

    exit;
}

/* ===================================================
   2. STATUS UPDATE
=================================================== */
if ($status) {

    if ($status === "Closed") {

        $stmt = $conn->prepare("
            UPDATE tickets 
            SET status=?, returned_at=NOW() 
            WHERE id=?
        ");

        $stmt->bind_param("si", $status, $id);

    } else {

        $stmt = $conn->prepare("
            UPDATE tickets 
            SET status=?, returned_at=NULL 
            WHERE id=?
        ");

        $stmt->bind_param("si", $status, $id);
    }

    if ($stmt->execute()) {
        echo json_encode([
            "success"=>true,
            "message"=>"Status updated",
            "status"=>$status
        ]);
    } else {
        echo json_encode([
            "success"=>false,
            "message"=>$stmt->error
        ]);
    }

    exit;
}

/* ===================================================
   3. FULL UPDATE (EDIT TICKET)
=================================================== */

$serialNumber = $_POST['serialNumber'] ?? '';
$tagNumber = $_POST['tagNumber'] ?? '';
$pcModel = $_POST['pcModel'] ?? '';
$branch = $_POST['branch'] ?? '';
$issue = $_POST['problem'] ?? '';
$phone = $_POST['phone'] ?? '';
$broughtBy = $_POST['broughtBy'] ?? '';

$returnedBy = $_POST['returnedBy'] ?? '';
$returnedPerson = $_POST['returnedPerson'] ?? '';

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
returnedBy=?,
returnedPerson=?,
maintenanceType=?,
maintenanceNotes=?,
maintenanceReasonNotDone=?
WHERE id=?
");

$stmt->bind_param(
"ssssssssssssi",
$serialNumber,
$tagNumber,
$pcModel,
$branch,
$issue,
$phone,
$broughtBy,
$returnedBy,
$returnedPerson,
$maintenanceType,
$maintenanceNotes,
$maintenanceReasonNotDone,
$id
);

if ($stmt->execute()) {
    echo json_encode([
        "success"=>true,
        "message"=>"Ticket updated successfully"
    ]);
} else {
    echo json_encode([
        "success"=>false,
        "message"=>$stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>