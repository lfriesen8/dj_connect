<?php
require('../backend/connect.php');


// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch incoming bookings
$query_bookings = "SELECT b.id, u.username AS client_name, dj.username AS dj_name, b.event_date, b.status 
                   FROM bookings b
                   JOIN users u ON b.client_id = u.id
                   JOIN users dj ON b.dj_id = dj.id
                   ORDER BY b.event_date ASC";
$stmt_bookings = $db->prepare($query_bookings);
$stmt_bookings->execute();
$bookings = $stmt_bookings->fetchAll();

// Fetch DJ profiles
$query_djs = "SELECT id, username, bio, genres FROM users WHERE role = 'dj'";
$stmt_djs = $db->prepare($query_djs);
$stmt_djs->execute();
$djs = $stmt_djs->fetchAll();

// Fetch posts for management
$query_posts = "SELECT p.id, p.title, p.content, p.created_at, p.updated_at, u.username AS author 
                FROM posts p 
                JOIN users u ON p.author_id = u.id
                ORDER BY p.created_at DESC";
$stmt_posts = $db->prepare($query_posts);
$stmt_posts->execute();
$posts = $stmt_posts->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <header>
        <div class="navbar">
            <span>Admin Dashboard</span>
            <a href="../frontend/index.php">Home</a>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main>
        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="feedback success"><?= htmlspecialchars($_SESSION['success_message']); ?></p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>

        <!-- To-Do List Section -->
        <section class="todo-section">
            <h2>Your To-Do List</h2>
            <ul>
                <li>Review incoming bookings</li>
                <li>Send notifications to DJs about bookings</li>
                <li>Moderate reviews on DJ profiles</li>
                <li>Create or manage announcements</li>
            </ul>
        </section>

        <!-- Post Management Section -->
        <section class="post-management-section">
            <h2>Manage Announcements</h2>
            <form action="../backend/process_create_post.php" method="POST" class="create-post-form">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>

                <label for="content">Content:</label>
                <textarea name="content" id="content" rows="4" required></textarea>

                <button type="submit">Create Post</button>
            </form>
            <h3>Existing Announcements</h3>
            <?php if (!empty($posts)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= htmlspecialchars($post['title']); ?></td>
                                <td><?= htmlspecialchars($post['author']); ?></td>
                                <td><?= htmlspecialchars($post['created_at']); ?></td>
                                <td>
                                    <form action="../backend/process_delete_post.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                                        <button type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No announcements yet.</p>
            <?php endif; ?>
        </section>

        <!-- Incoming Bookings -->
        <section class="bookings-section">
            <h2>Incoming Bookings</h2>
            <?php if (!empty($bookings)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>DJ</th>
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
                                <td><?= htmlspecialchars($booking['dj_name']); ?></td>
                                <td><?= htmlspecialchars($booking['event_date']); ?></td>
                                <td><?= htmlspecialchars($booking['status']); ?></td>
                                <td>
                                    <form action="../backend/delete_bookings.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                        <button type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </section>

        <!-- DJ Profiles -->
        <section class="dj-profiles-section">
            <h2>DJ Profiles</h2>
            <?php if (!empty($djs)): ?>
                <?php foreach ($djs as $dj): ?>
                    <div class="dj-card">
                        <h3><?= htmlspecialchars($dj['username']); ?></h3>
                        <p><strong>Bio:</strong> <?= htmlspecialchars($dj['bio']); ?></p>
                        <p><strong>Genres:</strong> <?= htmlspecialchars($dj['genres']); ?></p>
                        <a href="../frontend/dj_profile.php?id=<?= $dj['id']; ?>">View Profile</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No DJs found.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
