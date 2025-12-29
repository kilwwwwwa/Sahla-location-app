<?php
$host = "localhost";
$user = "root";      // Default for XAMPP/WAMP
$pass = "";          // Default for XAMPP/WAMP (leave empty)
$dbname = "sahla_db";

// Establish connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>