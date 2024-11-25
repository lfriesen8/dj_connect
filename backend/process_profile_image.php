<?php
require('connect.php');
session_start();

// Ensure the user is logged in as a DJ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dj') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];

    // Validate file type and size
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types) || $file['size'] > 2 * 1024 * 1024) {
        die("Invalid file type or size. Please upload an image under 2MB.");
    }

    // Generate a unique filename
    $filename = uniqid() . "_" . basename($file['name']);
    $upload_dir = '../uploads/';
    $upload_path = $upload_dir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Update the user's profile image in the database
        $query = "UPDATE users SET profile_image = :profile_image WHERE id = :dj_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':profile_image', $filename);
        $stmt->bindValue(':dj_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../frontend/dj_profile.php?message=upload_success");
            exit;
        } else {
            die("Error updating profile image in the database.");
        }
    } else {
        die("Error uploading file.");
    }
}
