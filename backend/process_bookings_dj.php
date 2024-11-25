<?php
require('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
        die("Unauthorized action.");
    }

    $client_id = $_SESSION['user_id'];
    $dj_id = filter_input(INPUT_POST, 'dj_id', FILTER_VALIDATE_INT);
    $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);

    if ($dj_id && $event_date) {
        $query = "INSERT INTO bookings (client_id, dj_id, event_date, status) 
                  VALUES (:client_id, :dj_id, :event_date, 'pending')";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);
        $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
        $stmt->bindValue(':event_date', $event_date);

        if ($stmt->execute()) {
            header("Location: ../frontend/index.php?message=booking_success");
            exit;
        } else {
            die("Error creating booking.");
        }
    } else {
        die("Missing required fields.");
    }
}
?>
