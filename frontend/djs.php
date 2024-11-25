<?php
require('../backend/connect.php');
session_start();

// Fetch all DJs
$query = "SELECT id, username, bio, genres FROM users WHERE role = 'dj'";
$stmt = $db->prepare($query);
$stmt->execute();
$djs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Browse DJs</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1>Available DJs</h1>
        <?php if (!empty($djs)): ?>
            <ul>
                <?php foreach ($djs as $dj): ?>
                    <li>
                        <h2><?= htmlspecialchars($dj['username']) ?></h2>
                        <p><strong>Bio:</strong> <?= htmlspecialchars($dj['bio']) ?></p>
                        <p><strong>Genres:</strong> <?= htmlspecialchars($dj['genres']) ?></p>
                        <a href="dj_profile.php?id=<?= $dj['id'] ?>">View Profile & Book</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No DJs available at the moment.</p>
        <?php endif; ?>
    </main>
</body>
</html>
