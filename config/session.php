<?php
session_start();

// if not logged in → redirect
if (!isset($_SESSION["user"])) {
    header("Location: /projects/PC_STATUS/frontend/login.php");
    exit;
}
?>