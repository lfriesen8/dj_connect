<?php
require('connect.php');
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);

    if ($comment_id) {
        $query = "DELETE FROM ratings_reviews WHERE id = :comment_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Set a session message for success
            $_SESSION['success_message'] = "Comment successfully deleted!";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            die("Failed to delete comment.");
        }
    } else {
        die("Invalid comment ID.");
    }
}
?>

