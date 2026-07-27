<?php
session_start();
require_once "db.php";

// user login check
if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// POST check
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])){

    $id = $_POST['id'];

    // শুধু নিজের memory retrieve করতে পারবে (security)
    $stmt = $conn->prepare("
        UPDATE memories 
        SET delete_date = NULL
        WHERE id = ? AND user_id = ?
    ");

    $stmt->bind_param("ii", $id, $user_id);

    if($stmt->execute()){
        header("Location: upcoming.php?success=1");
        exit();
    } else {
        echo "Error retrieving memory!";
    }

    $stmt->close();
    $conn->close();
}
else{
    header("Location: upcoming.php");
    exit();
}
?>