<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header("Location: /projects/PC_STATUS/frontend/login.php");
    exit;
}

$role = $user['role'];
?>

<style>
.sidebar {
    width: 220px;
    min-height: 100vh;
    background: #111;
    color: white;
    padding: 20px;
    position: fixed;
    top: 0;
    left: 0;
}

.sidebar h2 {
    margin-bottom: 20px;
}

.sidebar a {
    display: block;
    padding: 10px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    margin-bottom: 10px;
}

.sidebar a:hover {
    background: #333;
}

.logout-btn {
    width: 100%;
    padding: 10px;
    background: red;
    color: white;
    border: none;
    border-radius: 6px;
}
</style>

<div class="sidebar">

    <h2>IT Panel</h2>

    <!-- ================= ADMIN / SUPER ADMIN ================= -->
    <?php if ($role === "admin" || $role === "super_admin"): ?>

        <a href="/projects/PC_STATUS/frontend/pages/dashboard.php">
            Dashboard
        </a>

        <a href="/projects/PC_STATUS/frontend/pages/tickets.php">
            All Tickets
        </a>

        <a href="/projects/PC_STATUS/frontend/pages/create-tickets.php">
            New Ticket
        </a>

        <?php if ($role === "super_admin"): ?>
            <a href="/projects/PC_STATUS/frontend/pages/users.php">
                Users
            </a>
        <?php endif; ?>

    <!-- ================= USER ================= -->
    <?php else: ?>

        <a href="/projects/PC_STATUS/frontend/pages/user-dashboard.php">
            Dashboard
        </a>

        <a href="/projects/PC_STATUS/frontend/pages/tickets.php">
        Tickets
        </a>

        <a href="/projects/PC_STATUS/frontend/pages/create-tickets.php">
            New Ticket
        </a>

    <?php endif; ?>

    <br><br>

    <!-- LOGOUT -->
    <form method="POST" action="/projects/PC_STATUS/backend/api/auth/logout.php">
        <button class="logout-btn">Logout</button>
    </form>

</div>