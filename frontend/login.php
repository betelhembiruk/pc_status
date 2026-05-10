<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>

<body style="display:flex;justify-content:center;align-items:center;height:100vh;background:#f4f4f7;">

<div style="background:white;padding:30px;border-radius:10px;width:320px;">

    <h2 style="text-align:center;color:#95298e;">Login</h2>

    <input id="full_name" placeholder="Full Name"
        style="width:100%;padding:10px;margin-bottom:10px;">

    <input id="password" type="password" placeholder="Password"
        style="width:100%;padding:10px;margin-bottom:10px;">

    <button onclick="login()"
        style="width:100%;padding:10px;background:#95298e;color:white;border:none;">
        Login
    </button>

</div>

<script>
function login() {

    fetch("/projects/PC_STATUS/backend/api/auth/login.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            full_name: document.getElementById("full_name").value,
            password: document.getElementById("password").value
        })
    })
    .then(res => res.json())
    .then(data => {

        if (!data.success) {
            alert(data.message);
            return;
        }

        // force password change
        if (data.must_change_password) {
            window.location.href =
                "/projects/PC_STATUS/frontend/pages/change-password.php?id=" + data.user.id;
            return;
        }

        // ✅ IMPORTANT FIX
        window.location.href = data.dashboard;
    })
    .catch(err => console.log(err));
}
</script>

</body>
</html>