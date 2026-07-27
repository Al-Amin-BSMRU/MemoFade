<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$id = $_GET["id"];


$id = (int)$id;

$sql = "SELECT * FROM memories WHERE id=$id AND user_id=$user_id";
$result = $conn->query($sql);

if($result->num_rows == 0){
    echo "Memory not found";
    exit();
}

$row = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $title = $_POST["title"];
    $description = $_POST["description"];
    $date = $_POST["date"];
    $mood = $_POST["mood"];
    $tags = $_POST["tags"];
    $priority = $_POST["priority"];

    if($priority == "Low"){
        $delete_date = date("Y-m-d", strtotime("+15 days"));
    }
    else if($priority == "Medium"){
        $delete_date = date("Y-m-d", strtotime("+3 months"));
    }
    else{
        $delete_date = NULL; // High = remove from upcoming list
    }

$update = "UPDATE memories SET 
            title='$title',
            description='$description',
            memory_date='$date',
            mood='$mood',
            tags='$tags',
            priority='$priority',
            delete_date=" . ($delete_date ? "'$delete_date'" : "NULL") . "
            WHERE id=$id AND user_id=$user_id";

    $conn->query($update);

    header("Location: memories.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Memory</title>

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
    animation:fadeUp 0.4s ease;
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
}

input:focus, textarea:focus, select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,0.15);
}

textarea{
    height:100px;
    resize:none;
}

button{
    width:100%;
    padding:12px;
    margin-top:10px;
    border:none;
    border-radius:10px;
    background:#10b981;
    color:#fff;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(16,185,129,0.2);
}

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:#6b7280;
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

    <h2>Edit Memory </h2>

    <form method="POST">

        <input type="text" name="title" value="<?php echo $row['title']; ?>" required>

        <textarea name="description" required><?php echo $row['description']; ?></textarea>

        <input type="date" name="date" value="<?php echo $row['memory_date']; ?>" required>

        <select name="mood" required>
            <option value="Happy" <?php if($row['mood']=="Happy") echo "selected"; ?>>Happy </option>
            <option value="Sad" <?php if($row['mood']=="Sad") echo "selected"; ?>>Sad </option>
            <option value="Excited" <?php if($row['mood']=="Excited") echo "selected"; ?>>Excited </option>
            <option value="Angry" <?php if($row['mood']=="Angry") echo "selected"; ?>>Angry </option>
            <option value="Neutral" <?php if($row['mood']=="Neutral") echo "selected"; ?>>Neutral </option>
        </select>

        <select name="priority" required>
            <option value="High" <?php if($row['priority']=="High") echo "selected"; ?>>High </option>
            <option value="Medium" <?php if($row['priority']=="Medium") echo "selected"; ?>>Medium </option>
            <option value="Low" <?php if($row['priority']=="Low") echo "selected"; ?>>Low </option>
        </select>

        <button type="submit">Update Memory</button>

    </form>

    <a class="back" href="memories.php">← Back</a>

</div>

</body>
</html>