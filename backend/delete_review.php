<?php
require('connect.php');
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = filter_input(INPUT_POST, 'review_id', FILTER_VALIDATE_INT);

    if ($review_id) {
        // Delete the review
        $query = "DELETE FROM ratings_reviews WHERE id = :review_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':review_id', $review_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../frontend/admin_dashboard.php?message=review_deleted");
            exit;
        } else {
            header("Location: ../frontend/admin_dashboard.php?message=delete_failed");
            exit;
        }
    } else {
        header("Location: ../frontend/admin_dashboard.php?message=invalid_review");
        exit;
    }
} else {
    header("Location: ../frontend/admin_dashboard.php");
    exit;
}
