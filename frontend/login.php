<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>PC STATUS Login</title>

    <!-- CSS -->
<link rel="stylesheet" href="assets/css/style.css"></head>

<body>

<div class="login-container">

    <h2>Login</h2>

    <input type="email" id="email" placeholder="Email">
    <input type="password" id="password" placeholder="Password">

    <button onclick="login()">Login</button>

    <p id="msg"></p>

</div>

<script>
async function login() {

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    try {
        const res = await fetch("/projects/PC_STATUS/backend/api/auth/login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        });

        const text = await res.text();
        console.log("RAW RESPONSE:", text);

        const data = JSON.parse(text);

        if (data.success) {
            window.location.href = "/projects/PC_STATUS/frontend/pages/dashboard.php";
        } else {
            document.getElementById("msg").innerText = data.message;
        }

    } catch (error) {
        console.error(error);
        alert("Login failed - check backend");
    }
}
</script>

</body>
</html>