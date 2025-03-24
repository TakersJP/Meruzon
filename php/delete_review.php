<?php
session_start();
// Check admin
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

if (isset($_GET['review_id'])) {
    $reviewId = (int)$_GET['review_id'];
    // Delete that review
    $sql = "DELETE FROM reviews WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $reviewId);
    $stmt->execute();
}

// Redirect back
header("Location: manage_reviews.php");
exit();
