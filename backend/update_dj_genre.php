<?php
require('../backend/connect.php');

// Ensure the session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Allow both DJs and Admins to access this script
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'dj'])) {
    header("Location: ../frontend/login.php");
    exit;
}

// Debugging: Output session and POST data
var_dump($_SESSION);
var_dump($_POST);

if (isset($_POST['dj_id'], $_POST['genre_id'])) {
    $dj_id = filter_input(INPUT_POST, 'dj_id', FILTER_VALIDATE_INT);
    $genre_id = filter_input(INPUT_POST, 'genre_id', FILTER_VALIDATE_INT);

    if ($dj_id && $genre_id) {
        // Fetch the genre name
        $query_genre = "SELECT name FROM categories WHERE id = :genre_id";
        $stmt_genre = $db->prepare($query_genre);
        $stmt_genre->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt_genre->execute();
        $genre_name = $stmt_genre->fetchColumn();

        if (!$genre_name) {
            echo "Genre not found for ID: $genre_id";
            exit;
        }

        // Validate that the user has permission to update this DJ
        if ($_SESSION['role'] === 'dj' && $_SESSION['id'] != $dj_id) {
            echo "DJs can only update their own genre.";
            exit;
        }

        // Update the primary_genre_id and genres
        $query_update = "UPDATE users SET primary_genre_id = :genre_id, genres = :genre_name WHERE id = :dj_id";
        $stmt_update = $db->prepare($query_update);
        $stmt_update->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt_update->bindValue(':genre_name', $genre_name, PDO::PARAM_STR);
        $stmt_update->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);

        if (!$stmt_update->execute()) {
            echo "Error updating primary_genre_id and genres:";
            print_r($stmt_update->errorInfo());
            exit;
        } else {
            $redirect = ($_SESSION['role'] === 'admin') ? "../frontend/admin_dashboard.php" : "../frontend/dj_dashboard.php";
            header("Location: $redirect?message=Genre updated successfully.");
            exit;
        }
    } else {
        echo "Invalid dj_id or genre_id.";
        exit;
    }
} else {
    echo "Missing dj_id or genre_id.";
    exit;
}

