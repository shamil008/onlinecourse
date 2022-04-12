<?php


$conn = mysqli_connect("localhost", "root", "12345678", "online_course");
if(!$conn) {
    exit(mysqli_connect_error($conn));
} 
