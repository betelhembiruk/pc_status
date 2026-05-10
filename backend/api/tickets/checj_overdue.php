<?php
require_once "../../config/db.php";

$conn->query("
    UPDATE tickets 
    SET status='Overdue'
    WHERE due_date < CURDATE() 
    AND status != 'Resolved'
    AND status != 'Closed'
");
?>