<?php
session_start();
// Check if the current user is admin
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// If `item_id` and `new_name` are provided
if (isset($_POST['item_id']) && isset($_POST['new_name'])) {
    $itemId = (int) $_POST['item_id'];
    $newName = trim($_POST['new_name']);

    if ($newName !== '') {
        // Update product_name in the items table
        $sql = "UPDATE items SET product_name = ? WHERE item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $newName, $itemId);
        $stmt->execute();
    }
}
header("Location: manage_products.php");
exit();
