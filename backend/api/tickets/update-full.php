<?php
header("Content-Type: application/json");

require_once $_SERVER['DOCUMENT_ROOT'] . "/projects/PC_STATUS/config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "Missing ticket ID"
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| SAFE INPUTS
|--------------------------------------------------------------------------
*/
$serialNumber = $_POST['serialNumber'] ?? '';
$tagNumber = $_POST['tagNumber'] ?? '';
$pcModel = $_POST['pcModel'] ?? '';
$branch = $_POST['branch'] ?? '';
$issue = $_POST['problem'] ?? '';
$phone = $_POST['phone'] ?? '';
$broughtBy = $_POST['broughtBy'] ?? '';
$status = $_POST['status'] ?? 'Pending';

$returnedBy = $_POST['returnedBy'] ?? '';
$returnedPerson = $_POST['returnedPerson'] ?? '';

$maintenanceType = $_POST['maintenanceType'] ?? '';
$maintenanceNotes = $_POST['maintenanceNotes'] ?? '';
$maintenanceReasonNotDone = $_POST['maintenanceReasonNotDone'] ?? '';

/*
|--------------------------------------------------------------------------
| UPDATE QUERY
|--------------------------------------------------------------------------
*/
$sql = "UPDATE tickets SET
    serialNumber=?,
    tagNumber=?,
    pcModel=?,
    branch=?,
    issue=?,
    phone=?,
    broughtBy=?,
    status=?,
    returnedBy=?,
    returnedPerson=?,
    maintenanceType=?,
    maintenanceNotes=?,
    maintenanceReasonNotDone=?
WHERE id=?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "sssssssssssssi",
    $serialNumber,
    $tagNumber,
    $pcModel,
    $branch,
    $issue,
    $phone,
    $broughtBy,
    $status,
    $returnedBy,
    $returnedPerson,
    $maintenanceType,
    $maintenanceNotes,
    $maintenanceReasonNotDone,
    $id
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Ticket updated successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Update failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>