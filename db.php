<?php
$host = "localhost";
$user = "root";
$password = "1234";
$dbname = "live_app";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);
?>
