<?php
require('connect.php');
session_start();

// Ensure the user is logged in and is a DJ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dj') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $genres = filter_input(INPUT_POST, 'genres', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $primary_genre_id = filter_input(INPUT_POST, 'primary_genre', FILTER_VALIDATE_INT);
    $dj_id = $_SESSION['user_id'];

    if ($bio && $genres && $primary_genre_id) {
        // Fetch the genre name for the selected primary genre
        $query_genre = "SELECT name FROM categories WHERE id = :primary_genre_id";
        $stmt_genre = $db->prepare($query_genre);
        $stmt_genre->bindValue(':primary_genre_id', $primary_genre_id, PDO::PARAM_INT);
        $stmt_genre->execute();
        $primary_genre = $stmt_genre->fetchColumn();

        if (!$primary_genre) {
            header("Location: ../frontend/dj_dashboard.php?message=invalid_genre");
            exit;
        }

        // Update the user's profile
        $query = "UPDATE users SET bio = :bio, genres = :genres, primary_genre_id = :primary_genre_id WHERE id = :dj_id AND role = 'dj'";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':genres', $genres);
        $stmt->bindValue(':primary_genre_id', $primary_genre_id, PDO::PARAM_INT);
        $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../frontend/dj_dashboard.php?message=profile_updated");
            exit;
        } else {
            header("Location: ../frontend/dj_dashboard.php?message=update_failed");
            exit;
        }
    } else {
        header("Location: ../frontend/dj_dashboard.php?message=invalid_input");
        exit;
    }
} else {
    header("Location: ../frontend/dj_dashboard.php");
    exit;
}

