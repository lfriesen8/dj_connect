<?php
    require('../backend/connect.php');
    require('../backend/authenticate.php');
    if ($_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Create New Post</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1>Create a New Post</h1>
        <form action="../backend/process_post.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>
            
            <button type="submit" name="command" value="Create">Submit</button>
        </form>
    </main>
</body>
</html>
