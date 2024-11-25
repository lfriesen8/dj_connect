<?php 
require('connect.php');
session_start();

// Ensure the user is logged in as a client
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dj_id = filter_input(INPUT_POST, 'dj_id', FILTER_SANITIZE_NUMBER_INT);
    $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);

    if ($dj_id && $event_date) {
        // Check if the DJ is already booked for the given date
        $query_check = "SELECT COUNT(*) FROM bookings WHERE dj_id = :dj_id AND event_date = :event_date";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
        $stmt_check->bindValue(':event_date', $event_date);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() > 0) {
            // Redirect back to the DJ profile with an error message
            header("Location: ../frontend/dj_profile.php?id=$dj_id&error=booked");
            exit;
        }

        // Proceed with booking
        $query = "INSERT INTO bookings (dj_id, client_id, event_date, status) 
                  VALUES (:dj_id, :client_id, :event_date, 'pending')";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);
        $stmt->bindValue(':client_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':event_date', $event_date);

        if ($stmt->execute()) {
            header("Location: ../frontend/index.php?message=booking_success");
            exit;
        } else {
            die("Failed to create booking.");
        }
    } else {
        die("Invalid booking request.");
    }
}

