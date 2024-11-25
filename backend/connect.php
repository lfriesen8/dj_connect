<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); }


    define('DB_DSN', 'mysql:host=localhost;dbname=dj_connect;charset=utf8'); // Updated database name
    define('DB_USER', 'serveruser');
    define('DB_PASS', 'gorgonzola7!');
    
    try {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    } catch (PDOException $e) {
        print "Error: " . $e->getMessage();
        die();
    }

    // Start session if not already active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Placeholder role-based logic for navigation
    if (!isset($_SESSION['role'])) {
        $_SESSION['role'] = 'guest'; // Default role
    }
    // Roles: 'admin', 'dj', 'client', 'guest'
?>
