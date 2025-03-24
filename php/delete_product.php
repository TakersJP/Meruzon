<?php
session_start();
// Check if the current user is admin
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

if (isset($_GET['item_id'])) {
    $itemId = (int)$_GET['item_id'];
    // Delete from items table
    $sql = "DELETE FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $itemId);
    $stmt->execute();
}

header("Location: manage_products.php");
exit();
