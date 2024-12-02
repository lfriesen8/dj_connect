<?php
require('connect.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/gallery.php");
    exit;
}

// Handle the file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);

    $image = $_FILES['image'];
    $uploadDir = '../images/gallery/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    // Validate file type and size
    if (!in_array($image['type'], $allowedTypes)) {
        die("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
    }
    if ($image['size'] > 2 * 1024 * 1024) { // Limit to 2MB
        die("File size exceeds 2MB.");
    }

    // Generate a unique file name and move the file
    $fileName = uniqid() . '-' . basename($image['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($image['tmp_name'], $targetFile)) {
        // Save to database
        $query = "INSERT INTO gallery_images (image_path, title, caption, category, uploaded_at) 
                  VALUES (:image_path, :title, :caption, :category, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':image_path' => htmlspecialchars($fileName),
            ':title' => htmlspecialchars($title),
            ':caption' => htmlspecialchars($caption),
            ':category' => htmlspecialchars($category)
        ]);

        header("Location: ../frontend/gallery.php");
        exit;
    } else {
        die("Failed to upload the file.");
    }
}
?>
