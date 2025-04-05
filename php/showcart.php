<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT i.item_id, i.product_name, i.price, c.quantity
    FROM cart c
    JOIN items i ON c.item_id = i.item_id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_rows = "";
$total = 0;

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $cart_rows .= "<tr>
        <td>{$row['product_name']}</td>
        <td>
            <input type='number' 
                value='{$row['quantity']}' 
                min='1' 
                style='width: 60px;' 
                onchange='updateQuantity({$row['item_id']}, this.value)'>
        </td>

        <td>\${$row['price']}</td>
        <td>\$" . number_format($subtotal, 2) . "</td>
        <td>
            <form method='post' action='remove_from_cart.php'>
                <input type='hidden' name='item_id' value='{$row['item_id']}'>
                <button class='remove-btn' type='submit'>Remove</button>
            </form>
        </td>
    </tr>";
}


$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link href="../css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: Georgia, serif !important;
            margin: 20px !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #7FBFB0;
            color: white;
        }

        .hd-h1, .hd-h2 {
            font-family: Georgia, serif !important;
            padding-top: 20px !important;
            padding-left: 10px !important;
        }

        .cart-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .update-btn, .remove-btn {
            background-color: #638AB4;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .update-btn:hover, .remove-btn:hover {
            background-color: #5078A1;
        }
    </style>
</head>
<body>
    <div id="header"></div>

    <h1>Your Shopping Cart</h1>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $cart_rows ?: "<tr><td colspan='5'>Your cart is empty.</td></tr>"; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="right"><b>Order Total</b></td>
                <td align="right"><b>$<?php echo number_format($total, 2); ?></b></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <h2 class="hd-h2"><a href="checkout.php">Check Out</a></h2>
    <h2 class="hd-h2"><a href="listprod.php">Continue Shopping</a></h2>

    <script>
        fetch("header.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("header").innerHTML = data;
            })
            .catch(error => console.error("Error loading header:", error));

        function updateQuantity(itemId, newQuantity) {
            fetch('update_cart_ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `item_id=${itemId}&quantity=${newQuantity}`
            })
            .then(response => response.text())
            .then(data => {
                // Optionally reload or update subtotal/order total here
                location.reload(); // Reload to reflect updated subtotal/total
            })
            .catch(err => {
                console.error("Error updating quantity:", err);
            });
        }
    </script>
</body>
</html>
