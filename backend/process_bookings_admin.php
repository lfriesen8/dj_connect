<?php
require('connect.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized action.");
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $booking_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($booking_id) {
        // Determine action
        if ($action === 'approve') {
            $query = "UPDATE bookings SET status = 'approved' WHERE id = :id";
        } elseif ($action === 'decline') {
            $query = "UPDATE bookings SET status = 'declined' WHERE id = :id";
        } else {
            die("Invalid action.");
        }

        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $booking_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../frontend/admin_dashboard.php?message=booking_updated");
            exit;
        } else {
            die("Error updating booking.");
        }
    }
}
?>
