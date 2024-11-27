<?php
require('connect.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in as a DJ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dj') {
    header("Location: ../frontend/login.php");
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dj_id = $_SESSION['user_id']; // Get the DJ's ID from the session

    // Fetch the current profile image path from the database
    $query = "SELECT profile_image FROM users WHERE id = :dj_id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['profile_image']) {
        $image_path = "../uploads/dj_profiles/" . $result['profile_image'];

        // Attempt to delete the file from the uploads directory
        if (file_exists($image_path) && unlink($image_path)) {
            // Remove the image reference from the database
            $query = "UPDATE users SET profile_image = NULL WHERE id = :dj_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Profile image removed successfully!";
                header("Location: ../frontend/dj_dashboard.php");
                exit;
            } else {
                $_SESSION['message'] = "Failed to update the database.";
                header("Location: ../frontend/dj_dashboard.php");
                exit;
            }
        } else {
            $_SESSION['message'] = "Failed to delete the profile image from the server.";
            header("Location: ../frontend/dj_dashboard.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "No profile image found to remove.";
        header("Location: ../frontend/dj_dashboard.php");
        exit;
    }
} else {
    // Redirect if the request is not a POST request
    $_SESSION['message'] = "Invalid request method.";
    header("Location: ../frontend/dj_dashboard.php");
    exit;
}
?>
