<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: /projects/PC_STATUS/frontend/login.php");
    exit;
}
?>