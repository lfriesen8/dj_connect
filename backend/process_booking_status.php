<?php
//Handles option from DJ
require('connect.php');
session_start();

// Ensure the user is logged in as a DJ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dj') {
    header("Location: ../frontend/login.php");
    exit;
}

// Handle the status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_SANITIZE_NUMBER_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if ($booking_id && in_array($action, ['accept', 'reject'])) {
        $new_status = $action === 'accept' ? 'accepted' : 'rejected';
        
        $query = "UPDATE bookings SET status = :new_status WHERE id = :booking_id AND dj_id = :dj_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':new_status', $new_status);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->bindValue(':dj_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../frontend/dj_dashboard.php?message=booking_status_updated");
            exit;
        } else {
            die("Failed to update booking status.");
        }
    } else {
        die("Invalid booking request.");
    }
}
?>
