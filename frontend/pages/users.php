<?php
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div style="margin-left:240px; padding:20px; background:#f4f4f7; min-height:100vh;">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
        <h2 style="color:#95298e;">Users Management</h2>

        <button onclick="openModal()"
            style="background:#95298e;color:white;padding:10px 15px;border:none;border-radius:6px;cursor:pointer;">
            + Create User
        </button>
    </div>

    <!-- SEARCH -->
    <input id="search" placeholder="Search users..."
        style="padding:8px;width:250px;border-radius:6px;border:1px solid #ccc;margin-bottom:15px;">

    <!-- TABLE -->
    <div style="background:white;padding:15px;border-radius:10px;overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="text-align:left;border-bottom:1px solid #ddd;">
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Total</th>
                    <th>Active</th>
                    <th>Pending</th>
                    <th>Closed</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersBody"></tbody>
        </table>
    </div>
</div>

<!-- CREATE USER MODAL -->
<div id="userModal"
     style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;
            background:rgba(0,0,0,0.5);justify-content:center;align-items:center;z-index:999;">

    <div style="background:white;padding:25px;width:420px;border-radius:10px;">
        <h3 style="margin-bottom:15px;">Create User</h3>

        <input id="full_name" placeholder="Full Name"
            style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ccc;border-radius:5px;">

        <input id="password" type="password" placeholder="Password"
            style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ccc;border-radius:5px;">

        <select id="role"
            style="width:100%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:5px;">
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="super_admin">Super Admin</option>
        </select>

        <div style="display:flex;gap:10px;">
            <button onclick="createUser()"
                style="flex:1;background:#95298e;color:white;padding:10px;border:none;border-radius:5px;">
                Create
            </button>

            <button onclick="closeModal()"
                style="flex:1;background:#6b7280;color:white;padding:10px;border:none;border-radius:5px;">
                Close
            </button>
        </div>
    </div>
</div>

<script>
let users = [];

/* LOAD USERS */
function loadUsers() {
    fetch("/projects/PC_STATUS/backend/api/users/activity.php")
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.message || "Failed to load users");
                return;
            }

            users = data.data;
            render(users);
        })
        .catch(err => console.error(err));
}

/* RENDER USERS */
function render(data) {
    let html = "";

    data.forEach(u => {
        const statusColor = u.status === "disabled" ? "#ef4444" : "#10b981";

        html += `
        <tr style="border-bottom:1px solid #eee;">
            <td>${u.id}</td>
            <td>${u.full_name}</td>

            <td>
                <select onchange="changeRole(${u.id}, this.value)"
                        style="padding:5px;">
                    <option value="user" ${u.role === 'user' ? 'selected' : ''}>User</option>
                    <option value="admin" ${u.role === 'admin' ? 'selected' : ''}>Admin</option>
                    <option value="super_admin" ${u.role === 'super_admin' ? 'selected' : ''}>Super Admin</option>
                </select>
            </td>

            <td>
                <span style="
                    background:${statusColor};
                    color:white;
                    padding:4px 8px;
                    border-radius:20px;
                    font-size:12px;">
                    ${u.status || 'active'}
                </span>
            </td>

            <td>${u.last_login || '-'}</td>
            <td>${u.total_tickets || 0}</td>
            <td>${u.active_tickets || 0}</td>
            <td>${u.pending_tickets || 0}</td>
            <td>${u.closed_tickets || 0}</td>
            <td>${u.created_at}</td>

            <td style="display:flex;gap:5px;flex-wrap:wrap;">
             <button onclick="toggleUser(${u.id})"
    style="background:#f59e0b;color:white;border:none;padding:5px 8px;border-radius:4px;">
    ${u.status === 'disabled' ? 'Activate' : 'Disable'}
</button>

                <button onclick="deleteUser(${u.id})"
                    style="background:#ef4444;color:white;border:none;padding:5px 8px;border-radius:4px;">
                    Delete
                </button>
            </td>
        </tr>
        `;
    });

    document.getElementById("usersBody").innerHTML = html;
}

/* SEARCH */
document.getElementById("search").addEventListener("input", function() {
    const value = this.value.toLowerCase();

    const filtered = users.filter(u =>
        JSON.stringify(u).toLowerCase().includes(value)
    );

    render(filtered);
});

/* MODAL */
function openModal() {
    document.getElementById("userModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("userModal").style.display = "none";
}

/* CREATE USER */
function createUser() {
    const full_name = document.getElementById("full_name").value.trim();
    const password = document.getElementById("password").value.trim();
    const role = document.getElementById("role").value;

    if (!full_name || !password) {
        alert("Please fill all fields.");
        return;
    }

    fetch("/projects/PC_STATUS/backend/api/users/create.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            full_name,
            password,
            role
        })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || "Failed to create user");
            return;
        }

        alert("User created successfully.");

        document.getElementById("full_name").value = "";
        document.getElementById("password").value = "";
        document.getElementById("role").value = "user";

        closeModal();
        loadUsers();
    })
    .catch(err => console.error(err));
}

/* CHANGE ROLE */
function changeRole(id, role) {
    fetch("/projects/PC_STATUS/backend/api/users/update-role.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ id, role })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || "Failed to update role");
            return;
        }

        loadUsers();
    });
}

/* TOGGLE STATUS */
function toggleUser(id) {

    fetch("/projects/PC_STATUS/backend/api/users/toggle-status.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            id: id
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);

        if (!data.success) {
            alert(data.message);
            return;
        }

        loadUsers();
    })
    .catch(err => {
        console.error(err);
        alert("Error updating user status.");
    });
}

/* DELETE USER */
function deleteUser(id) {
    if (!confirm("Are you sure you want to delete this user?")) return;

    fetch("/projects/PC_STATUS/backend/api/users/delete.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ id })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || "Failed to delete user");
            return;
        }

        loadUsers();
    });
}

/* INIT */
loadUsers();
</script>

<?php include "../includes/footer.php"; ?>