<?php
require('connect.php');
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized action.");
}

// Check if the request method is POST and required inputs are present
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if ($booking_id && in_array($action, ['approve', 'decline'])) {
        if ($action === 'approve') {
            // Approve booking
            $new_status = 'accepted';
            $query = "UPDATE bookings SET status = :status WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':status', $new_status, PDO::PARAM_STR);
            $stmt->bindValue(':id', $booking_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: ../frontend/admin_dashboard.php?message=Booking approved successfully.");
                exit;
            } else {
                header("Location: ../frontend/admin_dashboard.php?message=Failed to approve booking.");
                exit;
            }
        } elseif ($action === 'decline') {
            // Delete booking
            $query = "DELETE FROM bookings WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $booking_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: ../frontend/admin_dashboard.php?message=Booking declined and removed successfully.");
                exit;
            } else {
                header("Location: ../frontend/admin_dashboard.php?message=Failed to decline booking.");
                exit;
            }
        }
    }
}

// If invalid request
header("Location: ../frontend/admin_dashboard.php?message=Invalid request.");
exit;
?>
