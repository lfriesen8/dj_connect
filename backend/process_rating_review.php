<?php
require('connect.php');
session_start();

// Ensure the user is logged in as a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dj_id = filter_input(INPUT_POST, 'dj_id', FILTER_SANITIZE_NUMBER_INT);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

    if ($dj_id && $rating && $rating >= 1 && $rating <= 5) {
        // Check if the user has already rated this DJ
        $query_check = "SELECT COUNT(*) FROM ratings_reviews WHERE dj_id = :dj_id AND user_id = :user_id";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
        $stmt_check->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() > 0) {
            header("Location: ../frontend/dj_profile.php?id=$dj_id&error=You have already rated this DJ.");
            exit;
        }

        // Insert the rating and comment
        $query = "INSERT INTO ratings_reviews (dj_id, user_id, rating, comment) 
                  VALUES (:dj_id, :user_id, :rating, :comment)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindValue(':comment', $comment);
        
        if ($stmt->execute()) {
            header("Location: ../frontend/dj_profile.php?id=$dj_id&success=Thank you for your feedback!");
            exit;
        } else {
            die("Failed to submit your feedback.");
        }
    } else {
        header("Location: ../frontend/dj_profile.php?id=$dj_id&error=Invalid rating or comment.");
        exit;
    }
}
