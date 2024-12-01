<?php
// Main Page - Displays DJ listings with average ratings
require('../backend/connect.php');

// Ensure session is started only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect users who are not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch all genres for the filter dropdown
$query_genres = "SELECT * FROM categories";
$stmt_genres = $db->prepare($query_genres);
$stmt_genres->execute();
$genres = $stmt_genres->fetchAll();

// Get the selected genre filter if any
$selected_genre = filter_input(INPUT_GET, 'genre', FILTER_VALIDATE_INT);

// Fetch all DJs along with their average ratings
$query_djs = "SELECT u.id, u.username, u.bio, u.genres, 
                     (SELECT AVG(rating) FROM ratings_reviews WHERE dj_id = u.id) AS avg_rating,
                     c.name AS primary_genre
              FROM users u
              LEFT JOIN categories c ON u.primary_genre_id = c.id
              WHERE u.role = 'dj'";
if ($selected_genre) {
    $query_djs .= " AND u.primary_genre_id = :selected_genre";
}
$stmt_djs = $db->prepare($query_djs);
if ($selected_genre) {
    $stmt_djs->bindValue(':selected_genre', $selected_genre, PDO::PARAM_INT);
}
$stmt_djs->execute();
$djs = $stmt_djs->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Welcome to DJ Connect</title>
</head>
<body id="index-page"> <!-- Unique ID for the index page -->
    <!-- DJ Connect Logo -->
    <div id="dj_connect_logo">
        <a href="index.php">DJ CONNECT</a>
    </div>

    <!-- Navigation Bar -->
    <header>
        <div class="navbar">
            <!-- Left-aligned navigation links -->
            <div class="nav-left">
                <a href="posts.php">Posts</a>
                <a href="gallery.php">Past Events</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin_dashboard.php">Admin Dashboard</a>
                <?php endif; ?>
            </div>

            <!-- Right-aligned logout link -->
            <div class="nav-right">
                <a href="../backend/logout.php">Logout</a>
            </div>
        </div>
        <span id="welcome-message">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
    </header>

    <!-- Introduction Section -->
    <main>
        <p class="intro-paragraph">
            At DJ Connect, we make it simple for you to browse talented DJs, check out their average ratings and reviews, 
            checkout updates from our admins, and easily book your favorite for your next event. <br><br>
            Keeping it simple, painless, and affordable... is what we do best!
        </p>

        <!-- Feedback Message -->
        <?php if (isset($_GET['message']) && $_GET['message'] === 'booking_success'): ?>
            <p class="feedback success">Booking request submitted successfully!</p>
        <?php endif; ?>

        <!-- Genre Filter Section -->
        <form method="GET" action="index.php" class="genre-filter">
            <label for="genre">Filter by Genre:</label>
            <select name="genre" id="genre" onchange="this.form.submit()">
                <option value="">All Genres</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id']; ?>" <?= $selected_genre == $genre['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($genre['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- DJ Listings Section -->
        <h1>Discover DJs</h1>
        <div class="dj-cards-container"> <!-- Updated container for card layout -->
            <?php if (!empty($djs)): ?>
                <?php foreach ($djs as $dj): ?>
                    <div class="dj-card">
                        <h2><?= htmlspecialchars($dj['username']); ?></h2>
                        <p><strong>Bio:</strong> <?= htmlspecialchars_decode($dj['bio']); ?></p>
                        <p><strong>Primary Genre:</strong> <?= htmlspecialchars($dj['primary_genre']); ?></p>
                        <p><strong>Genres:</strong> <?= htmlspecialchars_decode($dj['genres']); ?></p>
                        <p><strong>Average Rating:</strong> <?= number_format($dj['avg_rating'] ?? 0, 1); ?> / 5</p>
                        <a href="dj_profile.php?id=<?= $dj['id']; ?>">View Profile & Book</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No DJs available for the selected genre. Check back later!</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>






