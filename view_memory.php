<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$id = $_GET["id"];
$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM memories WHERE id=$id AND user_id=$user_id";
$result = $conn->query($sql);

if($result->num_rows == 0){
    echo "Memory not found";
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Memory</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI, sans-serif;
}

body{
    background:#f5f7fb;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    width:500px;
    background:#fff;
    padding:40px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
    animation:fadeUp 0.5s ease;
}

.title{
    font-size:26px;
    font-weight:700;
    color:#111827;
    margin-bottom:10px;
}

.meta{
    display:flex;
    justify-content:space-between;
    font-size:13px;
    color:#6b7280;
    margin-bottom:20px;
}

.desc{
    font-size:15px;
    color:#374151;
    line-height:1.6;
    margin-bottom:30px;
}

.badge{
    display:inline-block;
    padding:6px 12px;
    background:#2563eb;
    color:#fff;
    border-radius:20px;
    font-size:12px;
}

.back{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#2563eb;
    font-weight:500;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}
</style>

</head>
<body>

<div class="card">

    <div class="title"><?php echo $row["title"]; ?></div>

    <div class="meta">
        <span><?php echo $row["memory_date"]; ?></span>
        <span class="badge"><?php echo $row["mood"]; ?></span>
        
    </div>

    <div class="desc">
        <?php echo $row["description"]; ?>
    </div>

    <a class="back" href="memories.php">← Back to Memories</a>

</div>

</body>
</html>