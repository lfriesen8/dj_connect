<?php
require('connect.php');

if ($_POST) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);
    $genres = filter_input(INPUT_POST, 'genres', FILTER_SANITIZE_STRING);

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $query = "INSERT INTO users (username, password, role, bio, genres) 
              VALUES (:username, :password, :role, :bio, :genres)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $hashed_password);
    $stmt->bindValue(':role', $role);
    $stmt->bindValue(':bio', $bio);
    $stmt->bindValue(':genres', $genres);

    if ($stmt->execute()) {
        header("Location: ../frontend/login.php");
        exit;
    } else {
        die("Error registering user.");
    }
}
?>
