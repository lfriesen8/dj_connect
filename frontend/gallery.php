<?php
require('../backend/connect.php');
//Gallery for Admins to upload from previous events..
// Ensure session is started only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch gallery images
$query = "SELECT * FROM gallery_images ORDER BY uploaded_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Past Events</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Home</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php endif; ?>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1>Past Events</h1>
        <div class="gallery-container">
            <?php if ($images): ?>
                <?php foreach ($images as $image): ?>
                    <div class="gallery-item">
                        <img src="../uploads/gallery/<?= htmlspecialchars($image['image_path']); ?>" alt="Gallery Image">
                        <p><?= htmlspecialchars($image['caption']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-images-message">No images in the gallery yet. Check back later!</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>






