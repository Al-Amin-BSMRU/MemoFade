<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT * FROM memories 
    WHERE user_id = ? 
    AND delete_date IS NOT NULL
    AND delete_date = CURDATE() + INTERVAL 1 DAY
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Upcoming Delete</title>
<style>
body {
    background: #f1f5f9;
    min-height:100vh;
    font-family: 'Segoe UI', sans-serif;
}

/* MAIN WHITE BOX */
.container {
    max-width: 900px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* HEADER */
.header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.title {
    font-size:26px;
    font-weight:bold;
    color:#333;
}

.back-btn {
    background:#e2e8f0;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}
.back-btn:hover {
    background:#cbd5e1;
}

/* CARD */
.card {
    background:#f8fafc;
    padding:18px;
    margin-bottom:15px;
    border-radius:12px;
    border-left:5px solid #6366f1;
    transition:0.3s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.card h3 {
    margin-bottom:8px;
    color:#111;
}

.card p {
    color:#555;
}

/* BUTTONS */
.actions {
    margin-top:12px;
}

.btn {
    padding:7px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    margin-right:6px;
    transition:0.3s;
    font-size:14px;
}

.retrieve {
    background:#22c55e;
    color:white;
}
.retrieve:hover {
    background:#16a34a;
}

.delete {
    background:#ef4444;
    color:white;
}
.delete:hover {
    background:#dc2626;
}

/* EMPTY */
.empty {
    text-align:center;
    margin-top:60px;
    color:#666;
}
</style>

</head>

<body>

<div class="container">

<div class="header">
    <div class="title">⚠️ Upcoming Deletes</div>
    <a href="dashboard.php">
        <button class="back-btn">⬅ Back</button>
    </a>
</div>

<?php if($result->num_rows > 0): ?>

    <?php while($row = $result->fetch_assoc()): ?>

        <div class="card">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <small>Delete Date: <?php echo $row['delete_date']; ?></small>

            <div class="actions">

                <!-- Retrieve -->
                <form method="GET" action="edit_retrieve.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button class="btn retrieve">Retrieve</button>
                </form>

                <!-- Delete -->
                <form method="POST" action="delete_now.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button class="btn delete">Delete Now</button>
                </form>

            </div>
        </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="empty">
        <h2>No Upcoming Deletions 🎉</h2>
        <p>Everything is safe for now!</p>
    </div>

<?php endif; ?>

</div>

</body>
</html>