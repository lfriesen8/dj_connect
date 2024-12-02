<?php
require('../backend/connect.php');
// Gallery for Admins to upload images from previous events

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
    <link rel="stylesheet" href="../styles/gallery.css">
    <title>Past Events</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php endif; ?>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main>
        <div class="gallery-container">
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image): ?>
                    <div class="gallery-item" onclick="enlargeImage('<?= htmlspecialchars($image['image_path']); ?>')">
                        <img src="../images/gallery/<?= htmlspecialchars($image['image_path']); ?>" alt="<?= htmlspecialchars($image['title']) ?>" loading="lazy">
                        <h3 class="image-title"><?= htmlspecialchars($image['title']) ?></h3>
                        <p class="image-caption"><?= htmlspecialchars($image['caption']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-images-message">No images in the gallery yet. Check back later!</p>
            <?php endif; ?>
        </div>
        <!-- Enlarged Image Modal -->
        <div id="image-modal" class="modal" onclick="closeModal()">
            <span class="close-btn" onclick="closeModal()">x</span>
            <img id="enlarged-image" src="" alt="Enlarged Image">
        </div>
    </main>
    <script>
        function enlargeImage(imagePath) {
            const modal = document.getElementById("image-modal");
            const enlargedImage = document.getElementById("enlarged-image");
            modal.style.display = "flex";
            enlargedImage.src = "../images/gallery/" + imagePath;
        }

        function closeModal() {
            const modal = document.getElementById("image-modal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>







