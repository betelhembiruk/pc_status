<?php
header("Content-Type: application/json");

ini_set('display_errors', 0);
error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success"=>false,"message"=>"Invalid request"]);
    exit;
}

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id) {
    echo json_encode(["success"=>false,"message"=>"Missing ID"]);
    exit;
}

/* ===================================================
   1. STATUS UPDATE ONLY
=================================================== */
if ($status) {

    // 🔴 CLOSED → set returned time
    if ($status === "Closed") {

        $stmt = $conn->prepare("
            UPDATE tickets 
            SET status=?, returned_at=NOW() 
            WHERE id=?
        ");

        $stmt->bind_param("si", $status, $id);
    }

    // 🟢 ACTIVE or 🟡 PENDING → CLEAR returned time
    else {

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
   2. FULL UPDATE (ticket-view.php)
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