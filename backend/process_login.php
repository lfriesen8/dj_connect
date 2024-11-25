<?php
require('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $query = "SELECT id, role, password FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $username;

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
        header("Location: ../frontend/login.php?message=invalid_credentials");
        exit;
    }
}
?>

