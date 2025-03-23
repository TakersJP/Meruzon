<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['newProfileImage'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['newProfileImage'];

    if ($file['error'] === 0) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = 'profile_' . uniqid() . '.' . $ext;

        // Make sure file uploads to the correct physical directory
        $uploadDir = __DIR__ . '/uploads/';
        $dbPath = 'uploads/' . $newFileName;
        $uploadPath = $uploadDir . $newFileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!is_writable($uploadDir)) {
            die("Upload directory is not writable. Please set correct permissions.");
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Save relative path to database
            $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
            $stmt->bind_param("si", $dbPath, $user_id);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: customer.php");
                exit();
            } else {
                echo "Database update failed: " . $stmt->error;
            }
        } else {
            echo "Failed to move uploaded file. Check folder permissions.";
        }
    } else {
        echo "File upload error: " . $file['error'];
    }
}
?>
