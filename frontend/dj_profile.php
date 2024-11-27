<?php
require('../backend/connect.php');

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/dj_profle.css">
    <title><?= htmlspecialchars($dj['username']); ?>'s Profile</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Back to DJs</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php endif; ?>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1><?= htmlspecialchars($dj['username']); ?>'s Profile</h1>

        <!-- DJ Bio and Image Section -->
<div class="dj-info-container">
    <div class="dj-info-card">
        <p><strong>Bio:</strong> <?= htmlspecialchars_decode($dj['bio']); ?></p>
        <p><strong>Genres:</strong> <?= htmlspecialchars_decode($dj['genres']); ?></p>
        <p><strong>Average Rating:</strong> <?= number_format($avg_rating, 1); ?> / 5</p>
    </div>
    <div class="dj-profile-image">
        <img src="../uploads/dj_profiles/<?= htmlspecialchars($dj['profile_image']); ?>" alt="">
    </div>
</div>


        <!-- Reviews Section -->
        <div class="reviews-section">
            <h2>Reviews</h2>
            <?php if ($reviews): ?>
                <div class="reviews-container">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <p><strong><?= htmlspecialchars($review['username']); ?></strong></p>
                            <p><?= htmlspecialchars($review['comment']); ?></p>
                            <p><strong>Rating:</strong> <?= $review['rating']; ?> / 5</p>
                            <p><small>Posted on <?= htmlspecialchars($review['created_at']); ?></small></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No reviews available.</p>
            <?php endif; ?>
        </div>

        <!-- Booking Section -->
        <div class="booking-form">
            <h2>Book <?= htmlspecialchars($dj['username']); ?></h2>
            <form action="../backend/process_booking.php" method="POST">
                <input type="hidden" name="client_id" value="<?= $_SESSION['user_id']; ?>">
                <input type="hidden" name="dj_id" value="<?= $dj_id; ?>">

                <label for="event_date">Event Date:</label>
                <input type="date" id="event_date" name="event_date" required>

                <button type="submit">Request Booking</button>
            </form>
        </div>

        <!-- Leave a Review Section -->
        <div class="review-form">
            <h2>Leave a Review</h2>
            <form action="../backend/process_review.php" method="POST">
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
                <input type="hidden" name="dj_id" value="<?= $dj_id; ?>">

                <label for="rating">Rating (1-5):</label>
                <input type="number" id="rating" name="rating" min="1" max="5" required>

                <label for="comment">Review:</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>

                <button type="submit">Submit Review</button>
            </form>
        </div>
    </main>
</body>
</html>

