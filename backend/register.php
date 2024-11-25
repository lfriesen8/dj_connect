<?php
    require('connect.php');
    session_start();

    if ($_POST && !empty($_POST['username']) && !empty($_POST['password'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, 'client')";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Registration successful. Please log in.";
            header("Location: ../frontend/login.php");
            exit;
        } else {
            $_SESSION['message'] = "Registration failed. Please try again.";
            header("Location: ../frontend/register.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Both username and password are required.";
        header("Location: ../frontend/register.php");
        exit;
    }
?>
