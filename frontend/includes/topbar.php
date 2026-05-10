<?php $user = $_SESSION["user"]; ?>

<div style="
height:60px;
background:white;
border-bottom:1px solid #ddd;
margin-left:260px;
display:flex;
justify-content:space-between;
align-items:center;
padding:0 20px;
">

    <div><b>IT Helpdesk System</b></div>

    <div>
        🔔 <span id="notifCount" style="background:red;color:white;padding:3px 7px;border-radius:50%;">0</span>
        &nbsp;&nbsp;
        <?= $user["full_name"] ?> (<?= $user["role"] ?>)
    </div>

</div>