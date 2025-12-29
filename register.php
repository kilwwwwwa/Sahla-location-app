<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['register-fullname'];
    $email = $_POST['register-email'];
    $password = $_POST['register-password'];

    // Server-side validation (Security best practice)
    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters.";
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "success";
    } else {
        // Handle duplicate email error
        if ($conn->errno === 1062) {
            echo "This email is already registered.";
        } else {
            echo "An error occurred. Please try again.";
        }
    }
    $stmt->close();
}
?>