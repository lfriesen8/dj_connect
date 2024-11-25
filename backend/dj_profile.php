<?php
require('connect.php');
session_start();

// Validate if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the DJ's ID from the URL
$dj_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$dj_id) {
    die('Invalid DJ ID.');
}

// Fetch DJ information
$query_dj = "SELECT * FROM users WHERE id = :id AND role = 'dj'";
$stmt_dj = $db->prepare($query_dj);
$stmt_dj->bindValue(':id', $dj_id, PDO::PARAM_INT);
$stmt_dj->execute();
$dj = $stmt_dj->fetch();

if (!$dj) {
    die('DJ not found.');
}

// Fetch reviews and ratings for the DJ
$query_reviews = "SELECT rr.*, u.username 
                  FROM ratings_reviews rr
                  JOIN users u ON rr.user_id = u.id
                  WHERE rr.dj_id = :dj_id ORDER BY rr.created_at DESC";
$stmt_reviews = $db->prepare($query_reviews);
$stmt_reviews->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
$stmt_reviews->execute();
$reviews = $stmt_reviews->fetchAll();

// Calculate average rating
$query_avg_rating = "SELECT AVG(rating) AS avg_rating FROM ratings_reviews WHERE dj_id = :dj_id";
$stmt_avg_rating = $db->prepare($query_avg_rating);
$stmt_avg_rating->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
$stmt_avg_rating->execute();
$avg_rating = $stmt_avg_rating->fetchColumn() ?? 0;
?>

