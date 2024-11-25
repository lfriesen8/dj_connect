<?php
require('connect.php');
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dj_id = filter_input(INPUT_POST, 'dj_id', FILTER_SANITIZE_NUMBER_INT);

    if ($dj_id) {
        $query = "INSERT INTO notifications (dj_id, message, is_read) VALUES (:dj_id, 'Please check your bookings!', 0)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../frontend/admin_dashboard.php?message=notification_sent");
            exit;
        } else {
            header("Location: ../frontend/admin_dashboard.php?message=notification_failed");
            exit;
        }
    } else {
        header("Location: ../frontend/admin_dashboard.php?message=invalid_dj_id");
        exit;
    }
}
?>
