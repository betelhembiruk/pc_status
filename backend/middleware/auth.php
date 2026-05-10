<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized (not logged in)"
    ]);
    exit;
}