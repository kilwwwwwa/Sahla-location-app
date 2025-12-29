<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to post.");
    }

    $user_id     = $_SESSION['user_id'];
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $price       = $_POST['price'];
    $period      = $_POST['period'];
    $phone       = $_POST['phone'];
    $address     = $_POST['address'];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

    // 1. Handle Main Image
    $main_image = time() . '_' . $_FILES['main_image']['name'];

    if (move_uploaded_file($_FILES['main_image']['tmp_name'], $target_dir . $main_image)) {
        
        // 2. Insert into publications
        $sql = "INSERT INTO publications (user_id, title, description, price, period, phone, address, main_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $title, $description, $price, $period, $phone, $address, $main_image]);
        
        // --- FIX 1: Capture the new Publication ID ---
        $pub_id = $pdo->lastInsertId(); 

        // 3. Handle Gallery Images (Multiple)
        if (!empty($_FILES['gallery']['name'][0])) {
            foreach ($_FILES['gallery']['tmp_name'] as $key => $tmp_name) {
                $original_name = $_FILES['gallery']['name'][$key];
                $extension = pathinfo($original_name, PATHINFO_EXTENSION);
                $new_filename = "gallery_" . uniqid() . "_" . $key . "." . $extension;
                
                if (move_uploaded_file($tmp_name, $target_dir . $new_filename)) {
                    // --- FIX 2: Using the now-defined $pub_id ---
                    $sql_gallery = "INSERT INTO gallery_images (pub_id, image_path) VALUES (?, ?)";
                    $stmt_gallery = $pdo->prepare($sql_gallery);
                    $stmt_gallery->execute([$pub_id, $new_filename]);
                } else {
                    error_log("Failed to move gallery image: " . $original_name);
                }
            }
        }
        
        // --- FIX 3: Redirect only AFTER everything is done ---
        header("Location: dashboard.php?success=1");
        exit(); 

    } else {
        echo "Main image upload failed. Check folder permissions for 'uploads/'";
    }
}
?>