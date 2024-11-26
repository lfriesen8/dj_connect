
<?php
require('../backend/connect.php');
session_start(); // Ensure the session is started

$error = ""; // Initialize the error variable

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password']; // Password is hashed, so no need for additional sanitization here.

    // Validate inputs
    if (!empty($username) && !empty($password)) {
        // Query to check the user in the database
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        // Check if the user exists and verify the password
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Role: 'admin', 'dj', or 'client'

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: ../frontend/admin_dashboard.php");
                } elseif ($user['role'] === 'dj') {
                    header("Location: ../frontend/dj_dashboard.php");
                } else {
                    header("Location: ../frontend/index.php");
                }
                exit;
            }
        }
    }
    // Set the same error message for any invalid input
    $error = "Incorrect input. Need valid account to proceed.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/login.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <!-- Display error message -->
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
