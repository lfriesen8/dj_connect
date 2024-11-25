<?php
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

// Fetch all posts along with their authors
$query = "SELECT p.id, p.title, p.content, p.created_at, u.username AS author 
          FROM posts p
          JOIN users u ON u.id = p.author_id";
$stmt = $db->prepare($query);
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <script src="https://cdn.tiny.cloud/1/z32poujg8jny9f8k2hhapiykufgwq3c04yeoptqsp38a8dwb/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#new_post_content',
            plugins: 'link image lists',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
            branding: false
        });
    </script>
    <title>Posts</title>
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
    <main class="posts-container">
        <h1>Latest Posts</h1>

        <!-- Admin-Only Section for Creating Posts -->
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <section class="create-post">
                <h2>Create a New Post</h2>
                <form action="../backend/process_create_post.php" method="POST">
                    <label for="new_post_title">Title:</label>
                    <input type="text" id="new_post_title" name="title" required>

                    <label for="new_post_content">Content:</label>
                    <textarea id="new_post_content" name="content" rows="10"></textarea>

                    <button type="submit">Publish Post</button>
                </form>
            </section>
        <?php endif; ?>

        <!-- Display Existing Posts -->
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <h2><?= htmlspecialchars($post['title']); ?></h2>
                    <p><strong>By:</strong> <?= htmlspecialchars($post['author']); ?></p>
                    <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                    <small>Posted on <?= htmlspecialchars($post['created_at']); ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts available at the moment.</p>
        <?php endif; ?>
    </main>
</body>
</html>
