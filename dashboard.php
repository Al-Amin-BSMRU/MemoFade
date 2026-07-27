<?php
session_start();
include "auto_delete.php";
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION["user_name"];
$user_id = $_SESSION["user_id"];

/*
    শুধুমাত্র active memory দেখাবে
    (upcoming + deleted বাদ যাবে)
*/

$stmt = $conn->prepare("
    SELECT * FROM memories 
    WHERE user_id = ? AND status = 'active'
    ORDER BY id DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - MemoFade</title>

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

.nav{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 30px;
    background:#ffffff;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    position:sticky;
    top:0;
    animation: slideDown 0.5s ease;
}

.logo{
    font-size:22px;
    font-weight:700;
    color:#2563eb;
}

.nav a{
    text-decoration:none;
    margin-left:15px;
    color:#111827;
    font-weight:500;
}

.container{
    padding:30px;
}

.welcome{
    font-size:22px;
    margin-bottom:20px;
    color:#111827;
    animation: fadeIn 0.6s ease;
}

.add-btn{
    display:inline-block;
    padding:10px 18px;
    background:#2563eb;
    color:#fff;
    border-radius:10px;
    text-decoration:none;
    margin-bottom:20px;
    transition:0.3s;
}

.add-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(37,99,235,0.2);
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.card{
    background:#fff;
    padding:20px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
    transition:0.3s;
    animation: fadeUp 0.5s ease;
}

.card:hover{
    transform:translateY(-5px);
    box-shadow:0 15px 35px rgba(0,0,0,0.1);
}

.title{
    font-size:18px;
    font-weight:600;
    margin-bottom:8px;
    color:#111827;
}

.desc{
    font-size:14px;
    color:#6b7280;
    margin-bottom:10px;
}

.meta{
    font-size:12px;
    color:#9ca3af;
    display:flex;
    justify-content:space-between;
}

.actions a{
    font-size:12px;
    margin-right:10px;
    text-decoration:none;
    color:#2563eb;
}

.actions a.delete{
    color:red;
}

.mem-btn{
    display:inline-block;
    padding:10px 16px;
    background:#2563eb;
    color:#fff;
    border-radius:10px;
    text-decoration:none;
    font-weight:500;
    transition:0.3s;
}

.mem-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(37,99,235,0.2);
}

@keyframes fadeIn{
    from{opacity:0;}
    to{opacity:1;}
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

@keyframes slideDown{
    from{transform:translateY(-60px); opacity:0;}
    to{transform:translateY(0); opacity:1;}
}
</style>

</head>
<body>

<div class="nav">
    <div class="logo">MemoFade</div>
    <div>
        <a href="dashboard.php">Home</a>
        <a href="profile.php"><?php echo $user_name; ?></a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="welcome">Welcome, <?php echo $user_name; ?> </div>

    <a class="add-btn" href="add_memory.php">+ Add Memory</a>
    <a href="memories.php" class="mem-btn">My Memories</a>
    <a href="upcoming.php" class = "mem-btn">Upcoming Delete</a>
    <div class="grid">

        <?php while($row = $result->fetch_assoc()): ?>

        <div class="card">
            <div class="title"><?php echo $row["title"]; ?></div>
            <div class="desc"><?php echo substr($row["description"],0,100); ?>...</div>

            <div class="meta">
                <span><?php echo $row["memory_date"]; ?></span>
                <span><?php echo $row["mood"]; ?></span>
            </div>

            <div class="actions">
                <a href="view_memory.php?id=<?php echo $row["id"]; ?>">View</a>
                <a href="edit_memory.php?id=<?php echo $row["id"]; ?>">Edit</a>
                <a class="delete" href="delete_memory.php?id=<?php echo $row["id"]; ?>">Delete</a>
            </div>
        </div>

        <?php endwhile; ?>

    </div>

</div>

</body>
</html>