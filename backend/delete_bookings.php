<?php
require('connect.php');
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_SANITIZE_NUMBER_INT);

    if ($booking_id) {
        $query = "DELETE FROM bookings WHERE id = :booking_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Set a session message to notify admin
            $_SESSION['success_message'] = "Booking successfully deleted!";
            header("Location: ../frontend/admin_dashboard.php");
            exit;
        } else {
            die("Failed to delete booking.");
        }
    } else {
        die("Invalid booking ID.");
    }
}
?>
