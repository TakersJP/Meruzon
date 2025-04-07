<?php
session_start();
include 'config.php'; // make sure this defines $conn

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    echo "<p>No order found! Please place an order first.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id']);

// Fetch order and customer info, including order_ref_num
$stmt = $conn->prepare("
    SELECT o.order_date, o.order_ref_num,
           u.first_name, u.last_name
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    WHERE o.order_id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if (!$order = $order_result->fetch_assoc()) {
    echo "<p>No order found! Please place an order first.</p>";
    exit();
}

// Fetch ordered items
$stmt_items = $conn->prepare("
    SELECT i.item_id, i.product_name, i.price, oi.quantity, (i.price * oi.quantity) AS subtotal
    FROM order_items oi
    JOIN items i ON oi.product_id = i.item_id
    WHERE oi.order_id = ?
");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();

$order_total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link href="../css/style.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Georgia, serif;
            margin: 20px;
            text-align: center;
            background-color: #f9f9f9;
            color: black;
        }

        .container {
            width: 60%;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-top: 20px;
            text-align: left;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 10px;
            color: black;
        }

        th {
            background-color: #7FBFB0;
            color: white;
        }

        .checkout-info {
            margin-top: 20px;
            font-size: 18px;
        }

        .link {
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }

        .link:hover {
            text-decoration: underline;
        }

        .button {
            background-color: #7FBFB0;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #66A099;
        }
    </style>
</head>
<body>
  <div id="header"></div>
  <div class="container">
    <h1>Your Order Summary</h1>
    <table id="orderTable">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($item = $items_result->fetch_assoc()): 
          $subtotal = $item['price'] * $item['quantity'];
          $order_total += $subtotal;
        ?>
          <tr>
          <td><?= htmlspecialchars($item['item_id']) ?></td>
          <td><?= htmlspecialchars($item['product_name']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td align="right">$<?= number_format($item['price'], 2) ?></td>
          <td align="right">$<?= number_format($item['subtotal'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" align="right"><b>Order Total</b></td>
          <td align="right">$<?= number_format($order_total, 2) ?></td>
        </tr>
      </tfoot>
    </table>

    <div class="checkout-info">
      <p><b>Order completed. Will be delivered soon...</b></p>
      <p><b>Order placed on:</b> <?= htmlspecialchars($order['order_date']) ?></p>
      <p><b>Your tracking number:</b> <?= htmlspecialchars($order['order_ref_num']) ?></p>
      <p><b>Name:</b> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></p>
    </div>

    <button class="button" onclick="goBackToShop()">Return to Shop</button>
  </div>

  <script>
    function goBackToShop() {
      window.location.href = "shop.php";
    }
    // Load header
    fetch("header.php")
      .then(response => response.text())
      .then(data => document.getElementById("header").innerHTML = data)
      .catch(error => console.error("Header load failed:", error));
  </script>
</body>
</html>
