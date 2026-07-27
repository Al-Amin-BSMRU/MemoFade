<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id=$user_id LIMIT 1";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$countSql = "SELECT COUNT(*) as total FROM memories WHERE user_id=$user_id";
$countResult = $conn->query($countSql);
$total = $countResult->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI, sans-serif;
}

body{
    background:#f5f7fb;
}

.container{
    max-width:900px;
    margin:50px auto;
    background:#fff;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
    overflow:hidden;
    animation:fadeUp 0.5s ease;
}

.cover{
    height:160px;
    background:#0165E1;
}

.profile-content{
    padding:30px;
    text-align:center;
}

.avatar{
    width:110px;
    height:110px;
    border-radius:50%;
    background:#e5e7eb;
    margin:-70px auto 15px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:38px;
    font-weight:700;
    color:#111827;
    border:5px solid #fff;
}

.name{
    font-size:24px;
    font-weight:700;
    color:#111827;
}

.email{
    font-size:14px;
    color:#6b7280;
    margin-top:5px;
}

.info-box{
    display:flex;
    justify-content:center;
    gap:15px;
    margin-top:25px;
    flex-wrap:wrap;
}

.card{
    background:#f9fafb;
    padding:15px 20px;
    border-radius:12px;
    min-width:150px;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 20px rgba(0,0,0,0.06);
}

.card h3{
    color:black;
    font-size:18px;
}

.card p{
    color:#6b7280;
    font-size:12px;
}

.details{
    margin-top:25px;
    text-align:left;
    background:#f9fafb;
    padding:15px;
    border-radius:12px;
}

.details p{
    font-size:14px;
    margin:8px 0;
    color:#374151;
}

.label{
    font-weight:600;
    color:#111827;
}

.btns{
    margin-top:25px;
}

.btn{
    display:inline-block;
    padding:10px 18px;
    margin:5px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.home{
    background:#0165E1;
    color:#fff;
}

.logout{
    background:#ef4444;
    color:#fff;
}

.btn:hover{
    transform:translateY(-2px);
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}
</style>

</head>
<body>

<div class="container">

    <div class="cover"></div>

    <div class="profile-content">

        <div class="avatar">
            <?php echo strtoupper(substr($user["name"],0,1)); ?>
        </div>

        <div class="name"><?php echo $user["name"]; ?></div>
        <div class="email"><?php echo $user["email"]; ?></div>

        <div class="info-box">

            <div class="card">
                <h3><?php echo $total; ?></h3>
                <p>Total Memories</p>
            </div>

            <div class="card">
                <h3>Active</h3>
                <p>Status</p>
            </div>

            <div class="card">
                <h3>Free</h3>
                <p>Account Type</p>
            </div>

        </div>

        <div class="details">
            <p><span class="label">Email:</span> <?php echo $user["email"]; ?></p>
            <p><span class="label">Member ID:</span> #<?php echo $user["id"]; ?></p>
            <p><span class="label">Joined:</span> Recently</p>
        </div>

        <div class="btns">
            <a href="dashboard.php" class="btn home">Dashboard</a>
            <a href="logout.php" class="btn logout">Logout</a>
        </div>

    </div>

</div>

</body>
</html>

