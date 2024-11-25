<?php
session_start();
session_unset();
session_destroy();
header("Location: ../frontend/login.php"); // Adjust to point to the correct login page
exit;
?>
