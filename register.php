<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['register-fullname'];
    $email = $_POST['register-email'];
    $password = $_POST['register-password'];

    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters.";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt->execute()) {
        // --- THIS IS THE FIXED 2 PART ---
        session_start();
        
        // $conn->insert_id gets the ID created by the AUTO_INCREMENT in MySQL
        $_SESSION['user_id'] = $conn->insert_id; 
        $_SESSION['user_fullname'] = $fullname;
        
        echo "success";
        // --------------------------------
    } else {
        if ($conn->errno === 1062) {
            echo "This email is already registered.";
        } else {
            echo "An error occurred. Please try again.";
        }
    }
    $stmt->close();
}
?>