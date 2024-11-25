<?php
    require('../backend/connect.php');
    require('../backend/authenticate.php');
    if ($_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit;
    }
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        header("Location: index.php");
        exit;
    }
    $post = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Edit Post</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1>Edit Post</h1>
        <form action="../backend/process_post.php" method="POST">
            <input type="hidden" name="id" value="<?= $post['id']; ?>">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']); ?>" required>
            
            <label for="content">Content:</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($post['content']); ?></textarea>
            
            <button type="submit" name="command" value="Update">Update</button>
            <button type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
        </form>
    </main>
</body>
</html>
