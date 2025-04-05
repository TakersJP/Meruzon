<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if (!isset($_POST['item_id']) || !is_numeric($_POST['item_id'])) {
    die("Invalid or missing item_id.");
}

$user_id = $_SESSION['user_id'];
$item_id = (int)$_POST['item_id'];

// Debug print (check this works)
echo "Trying to delete item_id: $item_id for user_id: $user_id<br>";

$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND item_id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ii", $user_id, $item_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

header("Location: showcart.php");
exit();
?>
