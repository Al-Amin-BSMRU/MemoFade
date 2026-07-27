<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

/* =======================
   INPUTS
======================= */
$mode = $_GET["mode"] ?? "all";
$search = $_GET["search"] ?? "";
$priority = $_GET["priority"] ?? "";

/* =======================
   AUTO DELETE (SAFE)
======================= */
$delete_sql = "DELETE FROM memories 
WHERE user_id = ?
AND (
    (priority='Low' AND memory_date < DATE_SUB(CURDATE(), INTERVAL 15 DAY))
    OR
    (priority='Medium' AND memory_date < DATE_SUB(CURDATE(), INTERVAL 3 MONTH))
)";

$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

/* =======================
   MAIN QUERY
======================= */
$sql = "";
$params = [];

if ($mode == "upcoming") {

    $sql = "SELECT * FROM memories 
            WHERE user_id = ?
            AND delete_date IS NOT NULL
            AND delete_date >= CURDATE()
            ORDER BY delete_date ASC";

    $params[] = $user_id;

} elseif ($mode == "delete_list") {

    $sql = "SELECT * FROM memories 
            WHERE user_id = ?
            AND delete_date IS NOT NULL
            ORDER BY delete_date ASC";

    $params[] = $user_id;

} else {

    $sql = "SELECT * FROM memories WHERE user_id = ?";
    $params[] = $user_id;

    if (!empty($search)) {
        $sql .= " AND (title LIKE ? OR tags LIKE ? OR priority LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if (!empty($priority)) {
        $sql .= " AND priority = ?";
        $params[] = $priority;
    }

    $sql .= " ORDER BY memory_date DESC, id DESC";
}

/* =======================
   EXECUTE
======================= */
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare Error: " . $conn->error);
}

/* dynamic bind */
if (count($params) > 0) {
    $types = "";
    foreach ($params as $p) {
        $types .= is_int($p) ? "i" : "s";
    }

    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>MemoFade</title>

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

.topbar{
    padding:18px 25px;
    background:#fff;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    display:flex;
    justify-content:space-between;
}

.logo{
    font-size:20px;
    font-weight:700;
    color:#2563eb;
}

.back{
    text-decoration:none;
    color:#2563eb;
}

.container{
    padding:30px;
}

.title{
    font-size:22px;
    margin-bottom:15px;
}

.search-box input{
    padding:10px;
    width:260px;
    border:1px solid #ddd;
    border-radius:8px;
}

.search-box button{
    padding:10px 15px;
    border:none;
    background:#111;
    color:#fff;
    border-radius:8px;
}

.filters a{
    padding:6px 10px;
    background:#eee;
    border-radius:20px;
    margin-right:5px;
    text-decoration:none;
    font-size:12px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:20px;
}

.card{
    background:#fff;
    padding:15px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.h1{
    font-size:16px;
    font-weight:600;
}

.desc{
    font-size:13px;
    color:#666;
    margin:8px 0;
}

.meta{
    font-size:12px;
    display:flex;
    justify-content:space-between;
}

.priority{
    padding:3px 8px;
    border-radius:20px;
}

.priority.high{background:#fee2e2;color:#dc2626;}
.priority.medium{background:#fef9c3;color:#ca8a04;}
.priority.low{background:#dcfce7;color:#16a34a;}

.actions{
    margin-top:10px;
}

.actions a{
    text-decoration:none;
    padding:5px 8px;
    border-radius:5px;
    font-size:12px;
    color:white;
    margin-right:5px;
    display:inline-block;
}

.view{background:#16a34a;}
.edit{background:#f59e0b;}
.delete{background:#dc2626;}

.empty{
    text-align:center;
    padding:40px;
    color:#999;
}
</style>
</head>

<body>

<div class="topbar">
    <div class="logo">MemoFade</div>
    <a class="back" href="dashboard.php">← Dashboard</a>
</div>

<div class="container">

<div class="title">Your Memories</div>

<form class="search-box" method="GET">
    <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
    <button>Search</button>
</form>

<div class="filters">
    <a href="memories.php">All</a>
    <a href="memories.php?priority=High">High</a>
    <a href="memories.php?priority=Medium">Medium</a>
    <a href="memories.php?priority=Low">Low</a>
    <a href="timeline.php">View Timeline</a>
</div>

<br>

<?php if ($result->num_rows > 0): ?>

<div class="grid">

<?php while ($row = $result->fetch_assoc()): ?>

<div class="card">

    <div class="h1"><?php echo htmlspecialchars($row["title"]); ?></div>

    <div class="desc">
        <?php echo htmlspecialchars(substr($row["description"], 0, 120)); ?>
    </div>

    <div class="meta">
        <span><?php echo htmlspecialchars($row["mood"]); ?></span>
        <span class="priority <?php echo strtolower($row["priority"]); ?>">
            <?php echo htmlspecialchars($row["priority"]); ?>
        </span>
    </div>

    <div class="meta">
        <span><?php echo date("Y-m-d", strtotime($row["memory_date"])); ?></span>
    </div>

    <!-- ✅ ACTIONS ADDED -->
    <div class="actions">
        <a class="view" href="view_memory.php?id=<?php echo $row['id']; ?>">View</a>
        <a class="edit" href="edit_memory.php?id=<?php echo $row['id']; ?>">Edit</a>
        <a class="delete" href="delete_memory.php?id=<?php echo $row['id']; ?>" 
           onclick="return confirm('Are you sure?')">Delete</a>
    </div>

</div>

<?php endwhile; ?>

</div>

<?php else: ?>

<div class="empty">No memories found 😢</div>

<?php endif; ?>

</div>

</body>
</html>