<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$id = $_GET["id"];

$sql = "SELECT * FROM memories WHERE id=$id AND user_id=$user_id";
$result = $conn->query($sql);

if($result->num_rows == 0){
    echo "Memory not found";
    exit();
}

if(isset($_POST["confirm"])){
    $delete = "DELETE FROM memories WHERE id=$id AND user_id=$user_id";
    $conn->query($delete);
    header("Location: memories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delete Memory</title>

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

.card{
    width:400px;
    background:#fff;
    padding:40px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
    text-align:center;
    animation:fadeUp 0.4s ease;
}

h2{
    color:#111827;
    margin-bottom:10px;
}

p{
    color:#6b7280;
    margin-bottom:25px;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

.delete{
    background:#ef4444;
    color:#fff;
    margin-bottom:10px;
}

.delete:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(239,68,68,0.2);
}

.cancel{
    display:block;
    padding:12px;
    text-decoration:none;
    border-radius:10px;
    background:#e5e7eb;
    color:#111827;
    font-weight:600;
    transition:0.3s;
}

.cancel:hover{
    transform:translateY(-2px);
    background:#d1d5db;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}
</style>

</head>
<body>

<div class="card">

    <h2>Delete Memory</h2>
    <p>Are you sure you want to delete this memory? This action cannot be undone.</p>

    <form method="POST">
        <button class="delete" type="submit" name="confirm">Yes, Delete</button>
    </form>

    <a class="cancel" href="memories.php">Cancel</a>

</div>

</body>
</html>