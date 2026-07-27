<?php
require_once "db.php";

// upcoming status set করা (optional)
$stmt = $conn->prepare("
    UPDATE memories 
    SET status = 'upcoming'
    WHERE delete_date IS NOT NULL
    AND delete_date = CURDATE() + INTERVAL 1 DAY
");

$stmt->execute();


?>