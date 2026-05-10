<?php
session_start();
session_destroy();

header("Location: /projects/PC_STATUS/frontend/login.php");
exit;
?>