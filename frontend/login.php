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
            <div id="dj_connect_logo">
                <a href="login.php">DJ CONNECT</a>
            </div>
            <a href="register.php">Register</a>
        </div>
    </header>
    <main>
        <div class="login-container">
            <h1>Login:</h1>
            <!-- Login Form -->
            <form action="../backend/process_login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Login</button>
                
                <!-- Error Message Below Login Button -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-container">
                        <?= htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
            </form>
            <!-- Note Box -->
            <div class="note-box">
                <h3>Welcome!</h3>
                <p>Please sign in or register to access our platform. Explore talented DJs, browse profiles and past events, and book your favorites for events!</p>
            </div>
        </div>
    </main>
</body>
</html>
