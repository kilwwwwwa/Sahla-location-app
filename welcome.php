<?php
session_start();
include 'db.php';

// 1. Security Check: If no session exists, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_register_test_javascript.html");
    exit();
}

// 2. Fetch User Data to display on the dashboard
$user_id = $_SESSION['user_id'];
$query = "SELECT fullname FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAHLA - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_login_register_test_javascript.css">
    <style>
        .dashboard-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #158915;
        }
        .welcome-text { color: #158915; font-weight: 700; }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <header class="mb-5">
                    <img src="./Assets/logo-sahla-webapp-text-bold.png" alt="sahla" height="100">
                </header>
                
                <div class="dashboard-card">
                    <h1 class="welcome-text">Welcome back, <?php echo htmlspecialchars($user['fullname']); ?>!</h1>
                    <p class="text-muted">You are now securely logged into your SAHLA account.</p>
                    
                    <hr class="my-4">
                    
                    <div class="d-grid gap-2 d-md-block">
                        <a href="dashboard.php" class="btn btn-outline-success px-4 rounded-pill">Dashboard</button>
                        <a href="logout.php" class="btn btn-danger px-4 rounded-pill">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>