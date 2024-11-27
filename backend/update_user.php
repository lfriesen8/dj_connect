<?php
/**
 * This script handles updating a user's role and username in the system.
 * It ensures inputs are sanitized and validates user-provided data.
 */

require('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);

    if ($user_id && $username && in_array($role, ['admin', 'dj', 'client'])) {
        // Update the user's role and username in the database
        $query = "UPDATE users SET username = :username, role = :role WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "User updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update user.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Invalid input. Ensure all fields are filled out correctly.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to the manage users page
    header("Location: ../frontend/manage_users.php");
    exit;
}
?>
