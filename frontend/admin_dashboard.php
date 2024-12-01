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

// Fetch posts for management
$query_posts = "SELECT p.id, p.title, p.content, p.created_at, p.updated_at, u.username AS author 
                FROM posts p 
                JOIN users u ON p.author_id = u.id
                ORDER BY p.created_at DESC";
$stmt_posts = $db->prepare($query_posts);
$stmt_posts->execute();
$posts = $stmt_posts->fetchAll();

// Fetch DJs and their genres
$query_djs = "SELECT id, username, genres FROM users WHERE role = 'dj'";
$stmt_djs = $db->prepare($query_djs);
$stmt_djs->execute();
$djs = $stmt_djs->fetchAll();

// Fetch available genres from the categories table
$query_categories = "SELECT * FROM categories";
$stmt_categories = $db->prepare($query_categories);
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll();

// Handle booking status updates or deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if ($booking_id) {
        if ($action === 'delete') {
            $query_action = "DELETE FROM bookings WHERE id = :id";
        } elseif ($action === 'approve') {
            $query_action = "UPDATE bookings SET status = 'approved' WHERE id = :id";
        } elseif ($action === 'decline') {
            $query_action = "DELETE FROM bookings WHERE id = :id"; // Decline and delete
        } else {
            header("Location: admin_dashboard.php?message=Invalid action.");
            exit;
        }

        $stmt_action = $db->prepare($query_action);
        $stmt_action->bindValue(':id', $booking_id, PDO::PARAM_INT);
        if ($stmt_action->execute()) {
            $message = $action === 'delete' ? "Booking deleted successfully." : "Booking $action successfully.";
            header("Location: admin_dashboard.php?message=$message");
            exit;
        }
    }
    header("Location: admin_dashboard.php?message=Failed to process booking.");
    exit;
}

// Handle genre updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dj_id'], $_POST['genre_id'])) {
    $dj_id = filter_input(INPUT_POST, 'dj_id', FILTER_VALIDATE_INT);
    $genre_id = filter_input(INPUT_POST, 'genre_id', FILTER_VALIDATE_INT);

    if ($dj_id && $genre_id) {
        $query_update_genre = "UPDATE users SET genres = (SELECT name FROM categories WHERE id = :genre_id) WHERE id = :dj_id";
        $stmt_update_genre = $db->prepare($query_update_genre);
        $stmt_update_genre->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);
        $stmt_update_genre->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
        if ($stmt_update_genre->execute()) {
            header("Location: admin_dashboard.php?message=Genre updated successfully.");
            exit;
        }
    }
    header("Location: admin_dashboard.php?message=Failed to update genre.");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tiny.cloud/1/z32poujg8jny9f8k2hhapiykufgwq3c04yeoptqsp38a8dwb/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'lists link image preview',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link image | preview',
            menubar: false,
            height: 300
        });
    </script>
</head>
<body>
    <header>
        <div class="navbar">
            <span>Admin Dashboard</span>
            <a href="../frontend/index.php">Home</a>
            <a href="../frontend/manage_users.php">Manage Site Users</a>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>

        <!-- Display Notifications -->
        <?php if (isset($_GET['message'])): ?>
            <div class="feedback success">
                <?= htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- To-Do List Section -->
        <section class="todo-section">
            <h2>Your To-Do List</h2>
            <ul>
                <li>Approve or decline incoming bookings.</li>
                <li>Create and manage announcements for the website.</li>
                <li>Moderate DJ profiles and reviews.</li>
                <li>Manage site users (Add, update, or delete).</li>
            </ul>
        </section>

        <!-- Manage Bookings Section -->
        <section class="booking-management-section">
            <h2>Incoming Bookings</h2>
            <?php if (!empty($bookings)): ?>
                <table>
                    <thead>
                        <tr>
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
                                <td><?= htmlspecialchars($booking['client_name']); ?></td>
                                <td><?= htmlspecialchars($booking['dj_name']); ?></td>
                                <td><?= htmlspecialchars($booking['event_date']); ?></td>
                                <td><?= ucfirst(htmlspecialchars($booking['status'])); ?></td>
                                <td>
                                    <?php if ($booking['status'] === 'pending'): ?>
                                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button class="edit-button">Approve</button>
                                        </form>
                                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                            <input type="hidden" name="action" value="decline">
                                            <button class="cancel-button">Decline</button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="admin_dashboard.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button class="cancel-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No bookings at the moment.</p>
            <?php endif; ?>
        </section>

        <!-- Manage DJ Genres Section -->
        <section>
            <h2>Manage DJ Genres</h2>
            <table>
                <thead>
                    <tr>
                        <th>DJ</th>
                        <th>Current Genre</th>
                        <th>Update Genre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($djs as $dj): ?>
                        <tr>
                            <td><?= htmlspecialchars($dj['username']); ?></td>
                            <td><?= htmlspecialchars($dj['genres']); ?></td>
                            <td>
                            <form action="../backend/update_dj_genre.php" method="POST">
                                    <input type="hidden" name="dj_id" value="<?= $dj['id']; ?>">
                                    <select name="genre_id" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id']; ?>" <?= $dj['genres'] === $category['name'] ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="edit-button">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Manage Posts Section -->
        <section class="post-management-section">
            <h2>Manage Announcements</h2>
            <form action="../backend/process_posts.php" method="POST" class="create-post-form">
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
                                    <form action="../backend/process_posts.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $post['id']; ?>">
                                        <input type="hidden" name="command" value="Delete">
                                        <button type="submit" class="cancel-button">Delete</button>
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
    </main>
</body>
</html>





