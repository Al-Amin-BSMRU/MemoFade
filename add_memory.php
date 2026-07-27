<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // POST DATA আগে নিতে হবে
    $title = $_POST["title"];
    $description = $_POST["description"];
    $date = $_POST["date"];
    $mood = $_POST["mood"];
    // $tags = $_POST["tags"];
    $priority = $_POST["priority"];

    // delete_date logic (এখন ঠিক জায়গায়)
    $delete_date = NULL;

    if($priority == "Low"){
        $delete_date = date("Y-m-d", strtotime("+1 days"));
    }
    else if($priority == "Medium"){
        $delete_date = date("Y-m-d", strtotime("+3 months"));
    }
    else{
        $delete_date = NULL; // High priority → no auto delete
    }

    // SAFE INSERT (recommended)
    $stmt = $conn->prepare("
        INSERT INTO memories 
        (user_id, title, description, memory_date, mood,  priority, delete_date)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "issssss",
        $user_id,
        $title,
        $description,
        $date,
        $mood,
        $priority,
        $delete_date
    );

    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Memory - MemoFade</title>

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
    width:420px;
    background:#fff;
    padding:40px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
    animation: fadeUp 0.5s ease;
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#111827;
}

input, textarea, select{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:1px solid #e5e7eb;
    border-radius:10px;
    outline:none;
    transition:0.3s;
    background:#fff;
}

input:focus, textarea:focus, select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,0.15);
}

textarea{
    resize:none;
    height:100px;
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

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:#6b7280;
    font-size:14px;
}

.back:hover{
    color:#2563eb;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}
</style>

</head>
<body>

<div class="card">

    <h2>Add New Memory </h2>

    <form method="POST">

        <input type="text" name="title" placeholder="Memory Title" required>

        <textarea name="description" placeholder="Write your memory..." required></textarea>
        
        <input type="date" name="date" required>

        <select name="mood" required>
            <option value="">Select Mood</option>
            <option value="Happy">Happy</option>
            <option value="Sad">Sad</option>
            <option value="Excited">Excited </option>
            <option value="Angry">Angry </option>
            <option value="Neutral">Neutral </option>
        </select>

        <!-- TAGS -->
        <!-- <input type="text" name="tags" placeholder="Enter tags (e.g. travel, friends, exam)"> -->

        <!-- PRIORITY (CORRECT POSITION) -->
        <select name="priority" required>
            <option value="" selected>Select Priority</option>
            <option value="High">High </option>
            <option value="Medium" >Medium </option>
            <option value="Low">Low </option>
        </select>
        
        <button type="submit">Save Memory</button>

    </form>

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>