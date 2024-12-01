<?php
require('../backend/connect.php');

// Debugging: Confirm POST data
if (isset($_POST)) {
    var_dump($_POST);
}

// Check if the necessary data is present
if (isset($_POST['dj_id'], $_POST['genre_id'])) {
    $dj_id = filter_input(INPUT_POST, 'dj_id', FILTER_VALIDATE_INT);
    $genre_id = filter_input(INPUT_POST, 'genre_id', FILTER_VALIDATE_INT);

    if ($dj_id && $genre_id) {
        // Fetch the genre name from the categories table
        $query_genre = "SELECT name FROM categories WHERE id = :genre_id";
        $stmt_genre = $db->prepare($query_genre);
        $stmt_genre->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt_genre->execute();
        $genre = $stmt_genre->fetchColumn();

        if (!$genre) {
            die("Genre not found for ID: $genre_id");
        }

        // Update the DJ's genre in the users table
        $query_update = "UPDATE users SET genres = :genre, primary_genre_id = :genre_id WHERE id = :dj_id";
        $stmt_update = $db->prepare($query_update);
        $stmt_update->bindValue(':genre', $genre, PDO::PARAM_STR);
        $stmt_update->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt_update->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);

        if (!$stmt_update->execute()) {
            var_dump($stmt_update->errorInfo());
            die("Failed to update the genre.");
        } else {
            header("Location: ../frontend/admin_dashboard.php?message=Genre updated successfully.");
            exit;
        }
    } else {
        header("Location: ../frontend/admin_dashboard.php?message=Invalid data provided.");
        exit;
    }
} else {
    header("Location: ../frontend/admin_dashboard.php?message=Missing data.");
    exit;
}
