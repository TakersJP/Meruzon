<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "User not logged in.";
    exit();
}

if (!isset($_POST['item_id']) || !isset($_POST['quantity'])) {
    http_response_code(400);
    echo "Missing item_id or quantity.";
    exit();
}

$item_id = intval($_POST['item_id']);
$quantity = max(1, intval($_POST['quantity']));
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?");
if (!$stmt) {
    http_response_code(500);
    echo "Prepare failed: " . $conn->error;
    exit();
}

$stmt->bind_param("iii", $quantity, $user_id, $item_id);
if (!$stmt->execute()) {
    http_response_code(500);
    echo "Execute failed: " . $stmt->error;
    exit();
}

$stmt->close();
$conn->close();

echo "Quantity updated successfully.";
?>
