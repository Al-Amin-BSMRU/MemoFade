<?php
require_once "db.php";

// আজ যেগুলো delete হওয়ার কথা → delete
$stmt = $conn->prepare("
    DELETE FROM memories 
    WHERE delete_date IS NOT NULL
    AND delete_date = CURDATE()
");

$stmt->execute();


?>