<?php
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($check && $check->num_rows > 0) {
        $error = "Email already exists";
    } else {

        $sql = "INSERT INTO users (name, email, password)
                VALUES ('$name', '$email', '$password')";

        if ($conn->query($sql)) {
            $success = "Account created successfully";
        } else {
            $error = "Something went wrong";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - MemoFade</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI, sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f5f7fb;
}

.box{
    width:380px;
    padding:40px;
    background:#fff;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
    text-align:center;
    animation: fadeIn 0.6s ease;
}

h2{
    margin-bottom:20px;
    color:#111827;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:1px solid #e5e7eb;
    border-radius:10px;
    outline:none;
    transition:0.3s;
}

input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,0.15);
}

button{
    width:100%;
    padding:12px;
    margin-top:10px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:#fff;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(37,99,235,0.2);
}

a{
    display:block;
    margin-top:15px;
    color:#2563eb;
    text-decoration:none;
    font-size:14px;
}

.error{
    color:red;
    font-size:13px;
    margin-bottom:10px;
}

.success{
    color:green;
    font-size:13px;
    margin-bottom:10px;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}
</style>

</head>
<body>

<div class="box">

    <h2>Register</h2>

    <?php if($error != ""): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success != ""): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create Account</button>
    </form>

    <a href="login.php">Already have an account?</a>

</div>

</body>
</html>