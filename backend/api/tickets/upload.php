<?php
require_once "../../config/db.php";
require_once "../../middleware/auth.php";

$ticket_id = $_POST["ticket_id"];

$file = $_FILES["file"];

$targetDir = "../../uploads/";
$fileName = time() . "_" . basename($file["name"]);
$targetFile = $targetDir . $fileName;

move_uploaded_file($file["tmp_name"], $targetFile);

$stmt = $conn->prepare("
    INSERT INTO attachments (ticket_id, file_name)
    VALUES (?, ?)
");

$stmt->bind_param("is", $ticket_id, $fileName);
$stmt->execute();

echo json_encode(["success" => true]);
?>