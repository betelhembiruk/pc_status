<?php
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div style="margin-left:240px;padding:20px;background:#f4f4f7;min-height:100vh;">

<div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Users</h2>

    <button onclick="openModal()"
    style="background:#95298e;color:white;padding:10px;border:none;">
        + Create User
    </button>
</div>

<br>

<table style="width:100%;background:white;padding:10px;border-radius:10px;">
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Role</th>
<th>Status</th>
<th>Created</th>
<th>Last Login</th>
</tr>
</thead>

<tbody id="usersBody"></tbody>
</table>

</div>

<!-- MODAL -->
<div id="userModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.5);justify-content:center;align-items:center;">

<div style="background:white;padding:20px;width:400px;border-radius:10px;position:relative;">

<button onclick="closeModal()"
style="position:absolute;top:10px;right:10px;background:red;color:white;border:none;padding:5px;">
X
</button>

<h3>Create User</h3>

<input id="full_name" placeholder="Full Name"
style="width:100%;padding:8px;margin-bottom:10px;">

<input id="password" placeholder="Password"
style="width:100%;padding:8px;margin-bottom:10px;">

<select id="role" style="width:100%;padding:8px;margin-bottom:10px;">
    <option value="user">User</option>
    <option value="admin">Admin</option>
    <option value="super_admin">Super Admin</option>
</select>

<button onclick="createUser()"
style="width:100%;background:#95298e;color:white;padding:10px;border:none;">
Create
</button>

</div>
</div>

<script>

let users = [];

/* LOAD USERS */
function loadUsers() {

    fetch("/projects/PC_STATUS/backend/api/users/list.php")
    .then(res => res.text())
    .then(text => {

        console.log("RAW:", text);

        try {
            const data = JSON.parse(text);
            users = data;
            render(data);
        } catch (e) {
            console.log("JSON ERROR:", text);
        }

    })
    .catch(err => console.log("ERROR:", err));
}

/* RENDER USERS */
function render(data) {

    let html = "";

    data.forEach(u => {

        html += `
        <tr>
            <td>${u.id}</td>
            <td>${u.full_name}</td>
            <td>${u.role}</td>
            <td>${u.status ?? "active"}</td>
            <td>${u.created_at}</td>
            <td>${u.last_login ?? "-"}</td>
        </tr>
        `;
    });

    document.getElementById("usersBody").innerHTML = html;
}

/* OPEN MODAL */
function openModal() {
    document.getElementById("userModal").style.display = "flex";
}

/* CLOSE MODAL */
function closeModal() {
    document.getElementById("userModal").style.display = "none";
}

/* CREATE USER */
function createUser() {

    const full_name = document.getElementById("full_name").value;
    const password = document.getElementById("password").value;
    const role = document.getElementById("role").value;

    fetch("/projects/PC_STATUS/backend/api/users/create.php", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({
            full_name,
            password,
            role
        })
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            alert("User created");
            closeModal();
            loadUsers();
        } else {
            alert(data.message);
        }

    })
    .catch(err => {
        console.log(err);
        alert("Server error");
    });
}

loadUsers();

</script>

<?php include "../includes/footer.php"; ?>