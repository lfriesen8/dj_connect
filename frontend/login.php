<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Login</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </div>
    </header>
    <main>
        <h1>Login</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <p class="feedback"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>
        <form action="../backend/process_login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
