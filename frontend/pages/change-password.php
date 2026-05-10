<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
</head>

<body style="display:flex;justify-content:center;align-items:center;height:100vh;background:#f4f4f7;">

<div style="background:white;padding:30px;border-radius:10px;width:350px;">

    <h2 style="text-align:center;color:#95298e;">Change Password</h2>

    <input id="current_password" type="password" placeholder="Current Password"
        style="width:100%;padding:10px;margin-bottom:10px;">

    <input id="new_password" type="password" placeholder="New Password"
        style="width:100%;padding:10px;margin-bottom:10px;">

    <input id="confirm_password" type="password" placeholder="Confirm Password"
        style="width:100%;padding:10px;margin-bottom:10px;">

    <button onclick="changePassword()"
        style="width:100%;padding:10px;background:#95298e;color:white;border:none;">
        Update Password
    </button>

</div>

<script>
function changePassword() {

    const userId = new URLSearchParams(window.location.search).get("id");

    fetch("/projects/PC_STATUS/backend/api/auth/change-password.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            user_id: userId,
            current_password: document.getElementById("current_password").value,
            new_password: document.getElementById("new_password").value,
            confirm_password: document.getElementById("confirm_password").value
        })
    })
    .then(res => res.json())
    .then(data => {

        if (!data.success) {
            alert(data.message);
            return;
        }

        alert("Password changed successfully!");

        window.location.href =
            "/projects/PC_STATUS/frontend/pages/dashboard.php";
    })
    .catch(err => console.log(err));
}
</script>

</body>
</html>