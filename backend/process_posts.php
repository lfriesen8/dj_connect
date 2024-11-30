<?php
require('connect.php'); 
session_start(); // Ensure session is started

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit;
}

// Check if the form was submitted and the title and content fields are not empty
if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($_POST['id']) && isset($_POST['command'])) {
        $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($command === 'Update') {
            $query = "UPDATE posts SET title = :title, content = :content, updated_at = NOW() WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':content', $content);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Post updated successfully!";
            } else {
                $_SESSION['message'] = "Failed to update post.";
            }
            header("Location: ../frontend/posts.php");
            exit;
        } elseif ($command === 'Delete') {
            $query = "DELETE FROM posts WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Post deleted successfully!";
            } else {
                $_SESSION['message'] = "Failed to delete post.";
            }
            header("Location: ../frontend/posts.php");
            exit;
        }
    } else {
        $query = "INSERT INTO posts (title, content, author_id, created_at, updated_at) VALUES (:title, :content, :author_id, NOW(), NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Post created successfully!";
        } else {
            $_SESSION['message'] = "Failed to create post.";
        }
        header("Location: ../frontend/posts.php");
        exit;
    }
} else {
    $_SESSION['message'] = "Both title and content are required.";
    header("Location: ../frontend/posts.php");
    exit;
}
?>
