<?php
session_start();
// Admin check
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Get user_id from query parameter
if (isset($_GET['user_id'])) {
    $userId = (int) $_GET['user_id'];

    // Optional: prevent deleting yourself
    if ($userId == $_SESSION['user_id']) {
        // Some error or message
        header("Location: manage_users.php?error=CannotDeleteSelf");
        exit();
    }

    // Perform DELETE
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
}

// Redirect back
header("Location: manage_users.php");
exit();
