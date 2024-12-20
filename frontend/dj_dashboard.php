<?php
require('../backend/connect.php');

// Check if the logged-in user is a DJ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dj') {
    header("Location: ../frontend/login.php");
    exit;
}

// Fetch bookings for the logged-in DJ
$dj_id = $_SESSION['user_id'];
$query = "SELECT b.id, u.username AS client_name, b.event_date, b.status
          FROM bookings b
          JOIN users u ON b.client_id = u.id
          WHERE b.dj_id = :dj_id";
$stmt = $db->prepare($query);
$stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
$stmt->execute();
$bookings = $stmt->fetchAll();

// Fetch the DJ's profile information
$query_profile = "SELECT bio, genres, profile_image, primary_genre_id FROM users WHERE id = :dj_id";
$stmt_profile = $db->prepare($query_profile);
$stmt_profile->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
$stmt_profile->execute();
$dj_profile = $stmt_profile->fetch();

// Fetch all genres from the categories table
$query_genres = "SELECT * FROM categories";
$stmt_genres = $db->prepare($query_genres);
$stmt_genres->execute();
$genres = $stmt_genres->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>DJ Dashboard</title>
    <script src="https://cdn.tiny.cloud/1/z32poujg8jny9f8k2hhapiykufgwq3c04yeoptqsp38a8dwb/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'lists link image code table',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code',
            menubar: false,
            height: 300
        });
    </script>
</head>
<body>
    <header>
        <div class="navbar">
            <span class="hub-title">DJ CONNECT Employee Hub</span>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1>Your Dashboard</h1>
       
         <!-- Display Feedback -->
         <?php if (isset($_GET['message']) && $_GET['message'] === 'profile_updated'): ?>
            <p class="feedback success">Profile updated successfully!</p>
        <?php endif; ?>

        <!-- Update Bio and Genres -->
        <section class="update-section">
            <h2>Update Profile</h2>
            <form action="../backend/update_profile.php" method="POST">
                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio" rows="3"><?= $dj_profile['bio'] ?? ''; ?></textarea>

                <label for="genres">Genres:</label>
                <textarea name="genres" id="genres" rows="2"><?= $dj_profile['genres'] ?? ''; ?></textarea>
                <form action="../backend/update_dj_genre.php" method="POST">
                <label for="primary_genre">Primary Genre:</label>
                <select name="primary_genre" id="primary_genre" required>
                    <option value="">Select Primary Genre</option>
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?= $genre['id']; ?>" <?= $dj_profile['primary_genre_id'] == $genre['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($genre['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Update Profile</button>
            </form>
        </section>

        <!-- Upload and Remove Profile Image -->
        <section class="upload-image-form">
            <h2>Profile Picture</h2>
            <?php 
            $image_path = "../uploads/dj_profiles/" . htmlspecialchars($dj_profile['profile_image']); 
            ?>
            <?php if (!empty($dj_profile['profile_image']) && file_exists($image_path)): ?>
                <div class="current-image">
                    <img src="<?= $image_path; ?>" alt="Profile Picture" />
                </div>
                <form action="../backend/remove_profile_image.php" method="POST">
                    <button type="submit">Remove Profile Picture</button>
                </form>
            <?php else: ?>
                <p>No profile picture uploaded.</p>
            <?php endif; ?>
            <form action="../backend/upload_profile_image.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="dj_id" value="<?= $dj_id; ?>">
                <label for="profile_image">Upload a New Profile Picture:</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" required>
                <button type="submit">Upload</button>
            </form>
        </section>

        <!-- Booking Table -->
        <h2>Your Bookings</h2>
        <?php if (!empty($bookings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Event Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['id']); ?></td>
                            <td><?= htmlspecialchars($booking['client_name']); ?></td>
                            <td><?= htmlspecialchars($booking['event_date']); ?></td>
                            <td>
                                <?= ucfirst(htmlspecialchars($booking['status'])); ?>
                                <?php if ($booking['status'] === 'pending'): ?>
                                    <form action="../backend/process_booking_status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit">Accept</button>
                                    </form>
                                    <form action="../backend/process_booking_status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit">Decline</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $actions = [
                                    "Call Client",
                                    "Rent PK 15 Speaker System",
                                    "Call Boss Man LKF",
                                    "Buy coffee for the boss"
                                ];
                                echo $actions[array_rand($actions)];
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings yet...</p>
        <?php endif; ?>
    </main>
</body>
</html>

