<?php
require('connect.php');

// Start the session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    if ($post_id) {
        // Delete the post
        $query = "DELETE FROM posts WHERE id = :post_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Post deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to delete post.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Invalid post ID.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: ../frontend/admin_dashboard.php");
    exit;
} else {
    header("Location: ../frontend/admin_dashboard.php");
    exit;
}
