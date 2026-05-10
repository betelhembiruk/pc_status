<?php
function requireRole($roles) {
    if (!in_array($_SESSION['user']['role'], $roles)) {
        http_response_code(403);
        echo json_encode(["error" => "Forbidden"]);
        exit;
    }
}
?>