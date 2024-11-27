<?php
/**
 * This script handles adding new users to the system.
 * It ensures the data is sanitized and validated before inserting into the database.
 * The role can be 'admin', 'dj', or 'client'.
 */

require('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);

    if ($username && $password && in_array($role, ['admin', 'dj', 'client'])) {
        // Hash the password for secure storage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['message'] = "User added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to add user.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Invalid input.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to the manage users page
    header("Location: ../frontend/manage_users.php");
    exit;
}
?>

