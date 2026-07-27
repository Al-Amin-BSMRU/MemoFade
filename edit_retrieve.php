<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM memories WHERE id=$id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit & Retrieve</title>

<style>
body {
    background: #f1f5f9;
    font-family: 'Segoe UI', sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* CARD BOX */
.box {
    width: 420px;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    animation: fadeIn 0.5s ease;
}

/* TITLE */
h2 {
    text-align:center;
    margin-bottom:20px;
    color:#333;
}

/* SELECT */
select {
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
    margin-bottom:20px;
    outline:none;
    transition:0.3s;
}

select:focus {
    border-color:#6366f1;
    box-shadow:0 0 5px rgba(99,102,241,0.4);
}

/* BUTTON */
button {
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background: linear-gradient(135deg, #6366f1, #3b82f6);
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover {
    transform: translateY(-2px);
    box-shadow:0 8px 20px rgba(99,102,241,0.3);
}

/* BACK LINK */
.back {
    display:block;
    text-align:center;
    margin-top:15px;
    color:#666;
    text-decoration:none;
    font-size:14px;
}

.back:hover {
    color:#000;
}

/* ANIMATION */
@keyframes fadeIn {
    from {opacity:0; transform:translateY(20px);}
    to {opacity:1; transform:translateY(0);}
}
</style>

</head>

<body>

<div class="box">

    <h2>🔄 Retrieve Memory</h2>

    <form method="POST" action="update_retrieve.php">

        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <select name="priority" required>
            <option value="Low">Low (15 Days)</option>
            <option value="Medium">Medium (3 Months)</option>
            <option value="High">High (Forever)</option>
        </select>

        <button type="submit">Save & Retrieve</button>

    </form>

    <a class="back" href="upcoming.php">← Back to Upcoming</a>

</div>

</body>
</html>