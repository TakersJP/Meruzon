<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "User not logged in.";
    exit();
}

// Validate POST parameter
if (!isset($_POST['item_id']) || !is_numeric($_POST['item_id'])) {
    http_response_code(400);
    echo "Invalid or missing item_id.";
    exit();
}

$user_id = $_SESSION['user_id'];
$item_id = intval($_POST['item_id']);

// Check if item already exists in the cart
$stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND item_id = ?");
if (!$stmt) {
    http_response_code(500);
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Item exists, update quantity
    $newQty = $row['quantity'] + 1;
    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?");
    if (!$update) {
        http_response_code(500);
        die("Update prepare failed: " . $conn->error);
    }
    $update->bind_param("iii", $newQty, $user_id, $item_id);
    if (!$update->execute()) {
        http_response_code(500);
        die("Update execution failed: " . $update->error);
    }
    $update->close();
} else {
    // Item does not exist, insert new
    $insert = $conn->prepare("INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, 1)");
    if (!$insert) {
        http_response_code(500);
        die("Insert prepare failed: " . $conn->error);
    }
    $insert->bind_param("ii", $user_id, $item_id);
    if (!$insert->execute()) {
        http_response_code(500);
        die("Insert execution failed: " . $insert->error);
    }
    $insert->close();
}

$stmt->close();
$conn->close();

echo "Item added to cart!";
?>
