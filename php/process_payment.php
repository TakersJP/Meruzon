<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

$user_id = $_SESSION['user_id'];

$payment_type   = $_POST['paymentType'] ?? '';
$payment_number = $_POST['paymentNumber'] ?? '';
$expiry_date    = $_POST['paymentExpiryDate'] ?? '';

if (!$payment_type || !$payment_number || !$expiry_date) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing payment data."]);
    exit();
}

// Server-side validation for payment number (16 digits)
if (!preg_match('/^\d{16}$/', $payment_number)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid payment number format."]);
    exit();
}

// Server-side validation for expiry date (MM/YY)
if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry_date)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid expiry date format. Use MM/YY."]);
    exit();
}

// Check if the card is expired (assuming 20xx for YY)
$exp_parts = explode('/', $expiry_date);
$exp_month = intval($exp_parts[0]);
$exp_year  = intval('20' . $exp_parts[1]);
$current_year  = intval(date("Y"));
$current_month = intval(date("n"));
if ($exp_year < $current_year || ($exp_year == $current_year && $exp_month < $current_month)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Card is expired."]);
    exit();
}

$conn->begin_transaction();

try {
    // Step 1: Get cart items
    $stmt = $conn->prepare("
        SELECT c.item_id, c.quantity, i.price 
        FROM cart c 
        JOIN items i ON c.item_id = i.item_id 
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Cart is empty.");
    }

    $cart_items  = [];
    $total_amount = 0;

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_amount += $row['price'] * $row['quantity'];
    }
    $stmt->close();

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Order failed 1: " . $e->getMessage()]);
    exit();
}

try {

    // Step 2: Insert order first
    $order_ref_num = uniqid("REF");

    $stmt = $conn->prepare("
        INSERT INTO orders (
            user_id, order_date, payment_type, payment_number, 
            payment_expiry, total_amount, order_ref_num, shipping_status
        ) 
        VALUES (?, NOW(), ?, ?, ?, ?, ?, 'Pending')
    ");
    $stmt->bind_param(
      "isssds",
      $user_id, 
      $payment_type, 
      $payment_number, 
      $expiry_date, 
      $total_amount, 
      $order_ref_num
    );
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Order failed 2: " . $e->getMessage()]);
    exit();
}

try{

    // Step 3: Insert into order_items with the correct order_id and all required fields
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($cart_items as $item) {
        $product_id = $item['item_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $subtotal = $price * $quantity;

        // Fetch product name from items table
        $name_stmt = $conn->prepare("SELECT product_name FROM items WHERE item_id = ?");
        $name_stmt->bind_param("i", $product_id);
        $name_stmt->execute();
        $name_result = $name_stmt->get_result();
        $product_name = $name_result->fetch_assoc()['product_name'] ?? 'Unknown';
        $name_stmt->close();

        // Bind with correct types: i = int, s = string, d = double
        $stmt->bind_param("iisdss", $order_id, $product_id, $product_name, $quantity, $price, $subtotal);
        $stmt->execute();
    }
    $stmt->close();
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Order failed 3: " . $e->getMessage()]);
    exit();
}

try {


    // Step 4: Clear the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    echo json_encode(["success" => true, "order_id" => $order_id]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Order failed 4: " . $e->getMessage()]);
    exit();
}
?>
