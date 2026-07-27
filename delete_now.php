<?php
require_once "db.php";

$id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM memories WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: upcoming.php");
?>