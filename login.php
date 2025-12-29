<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['login-email'];
    $password = $_POST['login-password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            // Create session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_fullname'] = $user['fullname'];
            echo "success";
        } else {
            echo "invalid_password";
        }
    } else {
        echo "user_not_found";
    }
    $stmt->close();
}
?>