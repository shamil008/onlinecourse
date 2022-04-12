<?php
    session_start();
    if(!isset($_SESSION['user_id'])) {
        exit('No access');
    }
    require_once "../vars/conn.php";
    $user_id = $_SESSION['user_id'];
    $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users where id = $user_id"));
    if($user_data['role'] !== 'admin') {
        exit('You cant see this page');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Simple Sidebar - Start Bootstrap Template</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body>