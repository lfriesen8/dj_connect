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
        // Check if the comment exists
        $query_check = "SELECT id FROM ratings_reviews WHERE id = :comment_id";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt_check->execute();

        if ($stmt_check->rowCount() === 0) {
            header("Location: ../frontend/admin_dashboard.php?message=comment_not_found");
            exit;
        }

        // Proceed to delete the comment
        $query = "DELETE FROM ratings_reviews WHERE id = :comment_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../frontend/admin_dashboard.php?message=comment_deleted");
            exit;
        } else {
            header("Location: ../frontend/admin_dashboard.php?message=delete_failed");
            exit;
        }
    } else {
        header("Location: ../frontend/admin_dashboard.php?message=invalid_comment_id");
        exit;
    }
}
?>
