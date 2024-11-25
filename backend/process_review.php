<?php
require('connect.php');

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the form was submitted
if ($_POST && !empty($_POST['user_id']) && !empty($_POST['dj_id']) && !empty($_POST['rating'])) {
    // Sanitize inputs
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $dj_id = filter_input(INPUT_POST, 'dj_id', FILTER_SANITIZE_NUMBER_INT);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);
    $comment = isset($_POST['comment']) ? filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        $_SESSION['message'] = "Rating must be between 1 and 5.";
        header("Location: ../frontend/dj_profile.php?id={$dj_id}");
        exit;
    }

    // Insert into database
    $query = "INSERT INTO ratings_reviews (user_id, dj_id, rating, comment) 
              VALUES (:user_id, :dj_id, :rating, :comment)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
    $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindValue(':comment', $comment);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Review submitted successfully.";
        header("Location: ../frontend/dj_profile.php?id={$dj_id}");
        exit;
    } else {
        $_SESSION['message'] = "Failed to submit review.";
        header("Location: ../frontend/dj_profile.php?id={$dj_id}");
        exit;
    }
} else {
    $_SESSION['message'] = "All fields are required.";
    header("Location: ../frontend/dj_profile.php?id=" . ($_POST['dj_id'] ?? ''));
    exit;
}
