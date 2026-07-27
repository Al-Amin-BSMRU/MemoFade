<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM memories 
        WHERE user_id = $user_id 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$memories_by_date = [];

while($row = $result->fetch_assoc()) {
    $date = date("Y-m-d", strtotime($row['created_at']));
    $memories_by_date[$date][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MemoFade Timeline</title>

    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background:#f0f2f5;
        }

        /* NAVBAR */
        .navbar{
            background:#1877f2;
            color:white;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:12px 20px;
            position:sticky;
            top:0;
        }

        .logo{
            font-size:20px;
            font-weight:bold;
        }

        .nav-right a{
            margin-left:10px;
            padding:8px 12px;
            background:white;
            color:#1877f2;
            text-decoration:none;
            border-radius:5px;
            font-weight:bold;
        }

        .container{
            width:60%;
            margin:auto;
            padding-top:20px;
        }

        .date-header{
            text-align:center;
            font-weight:bold;
            margin:25px 0;
            color:#555;
        }

        /* CARD */
        .timeline-item{
            background:white;
            padding:15px;
            margin-bottom:12px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
            border-left:4px solid #1877f2;
        }

        .timeline-item h4{
            margin:0;
            color:#1877f2;
        }

        .timeline-item p{
            margin:5px 0;
        }

        .actions{
            margin-top:10px;
        }

        .actions a{
            text-decoration:none;
            margin-right:10px;
            padding:6px 10px;
            border-radius:5px;
            font-size:12px;
            color:white;
        }

        .view{ background:#28a745; }
        .edit{ background:#ffc107; color:black; }
        .delete{ background:#dc3545; }

        .empty{
            text-align:center;
            margin-top:50px;
            color:#777;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">📘 MemoFade</div>

    <div class="nav-right">
        <a href="dashboard.php">Dashboard</a>
        <a href="javascript:history.back()">⬅ Back</a>
    </div>
</div>

<!-- TIMELINE -->
<div class="container">

<?php if(empty($memories_by_date)): ?>

    <div class="empty">No memories found 😢</div>

<?php else: ?>

    <?php foreach($memories_by_date as $date => $memories): ?>

        <div class="date-header">
            <?= date("d M, Y", strtotime($date)) ?>
        </div>

        <?php foreach($memories as $memory): ?>

            <div class="timeline-item">
                
                <h4><?= htmlspecialchars($memory['title']) ?></h4>
                <p><?= htmlspecialchars($memory['description']) ?></p>
                <small><?= $memory['created_at'] ?></small>

                <!-- ACTION BUTTONS RESTORED -->
                <div class="actions">
                    <a class="view" href="view_memory.php?id=<?= $memory['id'] ?>">View</a>
                    <a class="edit" href="edit_memory.php?id=<?= $memory['id'] ?>">Edit</a>
                    <a class="delete" href="delete_memory.php?id=<?= $memory['id'] ?>" 
                       onclick="return confirm('Are you sure to delete?')">Delete</a>
                </div>

            </div>

        <?php endforeach; ?>

    <?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>