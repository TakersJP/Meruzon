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
        $uploadPath = 'uploads/' . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Save the path in the database
            $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
            $stmt->bind_param("si", $uploadPath, $user_id);
            $stmt->execute();
            $stmt->close();

            header("Location: customer.php");
            exit();
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "File upload error.";
    }
}
?>
