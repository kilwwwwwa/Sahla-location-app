<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sahla_db";

$conn = new mysqli($host, $user, $pass, $dbname);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Create a $conn variable too so login/register.php don't break
    $conn = new mysqli($host, $user, $pass, $dbname); 
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>