<?php
require_once __DIR__ . "/../../config/session.php";

$user = $_SESSION["user"];

// get current page for active state
$current = basename($_SERVER['PHP_SELF']);
?>

<style>
body {
    margin: 0;
    font-family: Arial;
}

/* SIDEBAR WRAPPER */
.sidebar {
    width: 260px;
    height: 100vh;
    background: #0f172a;
    position: fixed;
    top: 0;
    left: 0;
    color: white;
    transition: 0.3s;
    overflow-y: auto;
}

/* COLLAPSED STATE */
.sidebar.collapsed {
    width: 70px;
}

.sidebar h2 {
    text-align: center;
    padding: 15px;
    font-size: 16px;
}

/* USER BOX */
.user-box {
    text-align: center;
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 15px;
}

/* LINKS */
.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 18px;
    color: white;
    text-decoration: none;
    transition: 0.2s;
    border-left: 3px solid transparent;
}

/* HOVER */
.sidebar a:hover {
    background: #1e293b;
}

/* ACTIVE LINK */
.sidebar a.active {
    background: #1e293b;
    border-left: 3px solid #38bdf8;
}

/* ICON PLACEHOLDER */
.icon {
    width: 18px;
    text-align: center;
}

/* COLLAPSE BUTTON */
.toggle-btn {
    position: absolute;
    top: 10px;
    right: -12px;
    background: #38bdf8;
    color: black;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<div class="sidebar" id="sidebar">
<span id="openCount" style="float:right;color:#38bdf8"></span>
    <div class="toggle-btn" onclick="toggleSidebar()">≡</div>

    <h2>PC STATUS</h2>

    <div class="user-box">
        <?= $user["full_name"] ?><br>
        <small><?= $user["role"] ?></small>
    </div>

    <a href="/projects/PC_STATUS/frontend/pages/dashboard.php"
       class="<?= $current == 'dashboard.php' ? 'active' : '' ?>">
        <span class="icon">📊</span> Dashboard
    </a>

    <a href="/projects/PC_STATUS/frontend/pages/tickets.php"
       class="<?= $current == 'tickets.php' ? 'active' : '' ?>">
        <span class="icon">📄</span> Tickets
    </a>

    <a href="/projects/PC_STATUS/frontend/pages/board.php"
       class="<?= $current == 'board.php' ? 'active' : '' ?>">
        <span class="icon">📌</span> Board
    </a>

    <a href="/projects/PC_STATUS/frontend/pages/create-tickets.php"
       class="<?= $current == 'create_ticket.php' ? 'active' : '' ?>">
        <span class="icon">➕</span> Create Ticket
    </a>

    <?php if ($user["role"] !== "user"): ?>
        <a href="/projects/PC_STATUS/frontend/pages/users.php"
           class="<?= $current == 'users.php' ? 'active' : '' ?>">
            <span class="icon">👥</span> Users
        </a>
    <?php endif; ?>

    <a href="/projects/PC_STATUS/frontend/logout.php">
        <span class="icon">🚪</span> Logout
    </a>

</div>

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
}
</script>