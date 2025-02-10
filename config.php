<?php
$servername = "localhost";
$username = "root"; // Change if you have a MySQL password
$password = "";
$dbname = "resume_guidance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
