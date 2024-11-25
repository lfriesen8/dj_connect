<?php
//DJ info update page.

require('connect.php');
session_start();

// Ensure the user is logged in and is a DJ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dj') {
    header("Location: ../frontend/login.php");
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $genres = filter_input(INPUT_POST, 'genres', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dj_id = $_SESSION['user_id'];

    // Validate the input
    if ($bio && $genres) {
        $query = "UPDATE users SET bio = :bio, genres = :genres WHERE id = :dj_id AND role = 'dj'";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':genres', $genres);
        $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);

        // Execute and redirect with success message
        if ($stmt->execute()) {
            header("Location: ../frontend/dj_dashboard.php?message=profile_updated");
            exit;
        } else {
            die("Failed to update profile.");
        }
    } else {
        die("Invalid input. Please provide both Bio and Genres.");
    }
} else {
    header("Location: ../frontend/dj_dashboard.php");
    exit;
}
?>
