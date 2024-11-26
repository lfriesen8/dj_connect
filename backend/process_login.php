<?php
require('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Fetch user details from the database
    $query = "SELECT id, role, password FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    // Verify credentials
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $username;

        // Redirect based on user role
        if ($user['role'] === 'dj') {
            header("Location: ../frontend/dj_dashboard.php");
            exit;
        } elseif ($user['role'] === 'admin') {
            header("Location: ../frontend/admin_dashboard.php");
            exit;
        } elseif ($user['role'] === 'client') {
            header("Location: ../frontend/index.php");
            exit;
        }
    } else {
        // Redirect back to login with an error message
        $error_message = "Invalid username or password.";
        header("Location: ../frontend/login.php?error=" . urlencode($error_message));
        exit;
    }
}
?>
