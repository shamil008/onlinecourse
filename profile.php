<?php
    session_start();
    if(!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        return;
    }
    require_once "vars/conn.php";
    $user_id = $_SESSION['user_id'];
    $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users where id = $user_id"));
?>

Welcome our website <?= $user_data['name'] ?>

<a href="logout.php">Logout</a>