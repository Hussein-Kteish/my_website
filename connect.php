<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myweb";

$conn = new mysqli($servername, $username, $password, $dbname);// Create connection

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
