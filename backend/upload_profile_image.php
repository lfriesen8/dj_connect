<?php
require('connect.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in as a DJ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dj') {
    header("Location: ../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dj_id = $_SESSION['user_id']; // Use session to get the DJ's ID

    if (!isset($_FILES['profile_image'])) {
        $_SESSION['message'] = "No file uploaded.";
        header("Location: ../frontend/dj_dashboard.php");
        exit;
    }

    // File properties
    $file = $_FILES['profile_image'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_error = $file['error'];
    $file_size = $file['size'];

    // Check for upload errors
    if ($file_error !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = "An error occurred during file upload.";
        header("Location: ../frontend/dj_dashboard.php");
        exit;
    }

    // Allowed extensions
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Validate file type
    if (!in_array($file_ext, $allowed_extensions)) {
        $_SESSION['message'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        header("Location: ../frontend/dj_dashboard.php");
        exit;
    }

    // Validate file size (limit to 2MB)
    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['message'] = "File size exceeds the 2MB limit.";
        header("Location: ../frontend/dj_dashboard.php");
        exit;
    }

    // Move the file to the uploads directory
    $upload_dir = "../uploads/dj_profiles/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $new_file_name = "dj_" . $dj_id . "_profile." . $file_ext;
    $file_path = $upload_dir . $new_file_name;

    // Resize the image before saving it
    if (resizeImage($file_tmp, $file_path, 300, 300)) {
        // Save the file path in the database
        $query = "UPDATE users SET profile_image = :profile_image WHERE id = :dj_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':profile_image', $new_file_name, PDO::PARAM_STR);
        $stmt->bindValue(':dj_id', $dj_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Profile picture uploaded and resized successfully!";
            header("Location: ../frontend/dj_dashboard.php");
            exit;
        } else {
            $_SESSION['message'] = "Failed to update profile picture in the database.";
            header("Location: ../frontend/dj_dashboard.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Failed to process the image.";
        header("Location: ../frontend/dj_dashboard.php");
        exit;
    }
}

/**
 * Resize and save an image.
 *
 * @param string $source Path to the source image.
 * @param string $destination Path to save the resized image.
 * @param int $width Desired width.
 * @param int $height Desired height.
 * @return bool Returns true if successful, false otherwise.
 */
function resizeImage($source, $destination, $width, $height) {
    $image_info = getimagesize($source);
    $image_type = $image_info[2];

    if ($image_type == IMAGETYPE_JPEG) {
        $image = imagecreatefromjpeg($source);
    } elseif ($image_type == IMAGETYPE_PNG) {
        $image = imagecreatefrompng($source);
        imagealphablending($image, false);
        imagesavealpha($image, true);
    } elseif ($image_type == IMAGETYPE_GIF) {
        $image = imagecreatefromgif($source);
    } else {
        return false; // Unsupported image type
    }

    $new_image = imagecreatetruecolor($width, $height);
    if ($image_type == IMAGETYPE_PNG) {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
    }

    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

    if ($image_type == IMAGETYPE_JPEG) {
        imagejpeg($new_image, $destination, 90);
    } elseif ($image_type == IMAGETYPE_PNG) {
        imagepng($new_image, $destination);
    } elseif ($image_type == IMAGETYPE_GIF) {
        imagegif($new_image, $destination);
    }

    imagedestroy($new_image);
    imagedestroy($image);

    return true;
}
?>
