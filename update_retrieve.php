<?php
require_once "db.php";

$id = $_POST['id'];
$priority = $_POST['priority'];

if($priority == "Low"){
    $delete_date = date("Y-m-d", strtotime("+15 days"));
}
else if($priority == "Medium"){
    $delete_date = date("Y-m-d", strtotime("+3 months"));
}
else{
    $delete_date = NULL;
}

$stmt = $conn->prepare("
    UPDATE memories 
    SET delete_date = ?, priority = ?
    WHERE id = ?
");

$stmt->bind_param("ssi", $delete_date, $priority, $id);
$stmt->execute();

header("Location: dashboard.php");
?>