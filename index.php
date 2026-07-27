<?php
session_start();
require_once "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>MemoFade</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Segoe UI', sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f5f7fb;
}

.container{
    width:420px;
    padding:45px;
    border-radius:16px;
    background:#ffffff;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
    text-align:center;
}

h1{
    font-size:32px;
    margin-bottom:10px;
    color:#111827;
}

h1 span{
    color:#2563eb;
}

p{
    font-size:14px;
    color:#6b7280;
    margin-bottom:30px;
}

.btn{
    display:block;
    padding:12px;
    margin:12px 0;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.login{
    background:#2563eb;
    color:#fff;
}

.register{
    background:#f3f4f6;
    color:#111827;
    border:1px solid #e5e7eb;
}

.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(0,0,0,0.08);
}

small{
    display:block;
    margin-top:18px;
    color:#9ca3af;
}
</style>

</head>
<body>

<div class="container">

    <h1>Memo<span>Fade</span></h1>
    <p>Save your memories beautifully and simply </p>

    <a href="login.php" class="btn login">Login</a>
    <a href="register.php" class="btn register">Create Account</a>

    <small>Simple • Clean • Modern</small>

</div>

</body>
</html>