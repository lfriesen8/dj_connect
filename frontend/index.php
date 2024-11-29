<?php
// ma<?php
// mainpage
require('../backend/connect.php');

// Ensure session is started only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch all DJs along with their average ratings
$query = "SELECT u.id, u.username, u.bio, u.genres, 
                 (SELECT AVG(rating) FROM ratings_reviews WHERE dj_id = u.id) AS avg_rating
          FROM users u
          WHERE u.role = 'dj'";
$stmt = $db->prepare($query);
$stmt->execute();
$djs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Welcome to DJ Connect</title>
</head>
<body>
    <div id="dj_connect_logo">
        <a href="index.php">DJ CONNECT</a>
    </div>
    <header>
        <div class="navbar">
            <a href="posts.php">Posts</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php endif; ?>
            <a href="../backend/logout.php">Logout</a>
        </div>
        <span id="welcome-message">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
    </header>
    <main>
        <p class="intro-paragraph">
            At DJ Connect, we make it simple for you to browse talented DJs, check out their average ratings and reviews, 
            checkout updates from our admins, and easily book your favorite for your next event. <br><br>
            Keeping it simple, painless, and affordable... is what we do best!
        </p>
        <!-- Display Feedback Message -->
        <?php if (isset($_GET['message']) && $_GET['message'] === 'booking_success'): ?>
            <p class="feedback success">Booking request submitted successfully!</p>
        <?php endif; ?>
        <h1>Discover DJs</h1>
        <?php if (!empty($djs)): ?>
            <?php foreach ($djs as $dj): ?>
                <div class="dj-card">
                    <h2><?= htmlspecialchars($dj['username']); ?></h2>
                    <p><strong>Bio:</strong> <?= htmlspecialchars_decode($dj['bio']); ?></p>
                    <p><strong>Genres:</strong> <?= htmlspecialchars_decode($dj['genres']); ?></p>
                    <p><strong>Average Rating:</strong> <?= number_format($dj['avg_rating'] ?? 0, 1); ?> / 5</p>
                    <a href="dj_profile.php?id=<?= $dj['id']; ?>">View Profile & Book</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No DJs available at the moment. Check back later!</p>
        <?php endif; ?>
    </main>
</body>
</html>


