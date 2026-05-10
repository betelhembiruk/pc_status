<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>

<div class="content" style="margin-left:220px; padding:20px;">

<h2>Create User</h2>

<input id="name" placeholder="Full Name"><br><br>
<input id="email" placeholder="Email"><br><br>
<input id="password" placeholder="Password"><br><br>

<select id="role">
    <option>user</option>
    <option>admin</option>
    <option>super_admin</option>
</select><br><br>

<button onclick="createUser()">Create User</button>

<p id="msg"></p>

</div>

<script>
function createUser(){

    fetch("/projects/PC_STATUS/backend/api/users/create.php", {
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body: JSON.stringify({
            full_name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            role: document.getElementById("role").value
        })
    })
    .then(res=>res.json())
    .then(data=>{
        document.getElementById("msg").innerText =
            data.success ? "User Created" : data.message;
    });

}
</script>

<?php include "../includes/footer.php"; ?>