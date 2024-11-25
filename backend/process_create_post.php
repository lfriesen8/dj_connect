<?php
// processing informative posts from the Admin(s)

require('connect.php');

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author_id = $_SESSION['user_id']; // Use the logged-in admin's ID

    // Validate required inputs
    if ($title && $content) {
        // Insert post into the database
        $query = "INSERT INTO posts (title, content, author_id, created_at, updated_at) 
                  VALUES (:title, :content, :author_id, NOW(), NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':author_id', $author_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirect back to the admin dashboard with a success message
            $author = $_SESSION['username']; // Pass the admin username for confirmation
            header("Location: ../frontend/admin_dashboard.php?message=post_created&author=$author");
            exit;
        } else {
            die("Failed to create post.");
        }
    } else {
        // Redirect with an error message if validation fails
        header("Location: ../frontend/admin_dashboard.php?error=Invalid input.");
        exit;
    }
} else {
    // Redirect if accessed without a POST request
    header("Location: ../frontend/admin_dashboard.php");
    exit;
}

