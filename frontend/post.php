<?php
    require('../backend/connect.php');
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
    <title><?= htmlspecialchars($post['title']); ?></title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Home</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="edit.php?id=<?= $post['id']; ?>">Edit</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1><?= htmlspecialchars($post['title']); ?></h1>
        <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>
    </main>
</body>
</html>
