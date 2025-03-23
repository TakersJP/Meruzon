<?php
  session_start();
  include 'config.php';

  if (!isset($_SESSION['user_id'])) {
      header("Location: login.php");
      exit();
  }

  $currentUserId = $_SESSION['user_id'];

  $sql = "SELECT o.order_id, o.order_date, o.user_id, u.first_name, u.last_name, o.total_amount 
          FROM orders o
          JOIN users u ON o.user_id = u.user_id
          WHERE o.user_id = ?
          ORDER BY o.order_date DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $currentUserId);
  $stmt->execute();
  $result = $stmt->get_result();

  $orders = [];
  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $orders[] = $row;
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Order List</title>
  <link href="../css/style.css" rel="stylesheet">
  <style>
    body {
      font-family: Georgia, serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
      text-align: center;
      color: black;
    }
    .container {
      width: 60%;
      margin: 40px auto;
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      text-align: left; /* Align table to the left for better readability */
    }
    h1 {
      font-size: 24px;
      color: black;
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      margin-top: 20px;
      border: 1px solid #ccc;
    }
    th, td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }
    th {
      background-color: #7FBFB0;
      color: white;
    }
    /* Order information row */
    .order-row {
      background-color: #eef8f8; /* Adjust as needed */
    }
    /* Add extra bottom padding to order-row cells for spacing */
    .order-row td {
      padding-bottom: 15px;
    }
    /* Product information header row */
    .product-header-row th {
      background-color: #f0f0f0;
      color: black;
    }
    /* Product information row */
    .product-row td {
      background-color: #fff;
    }
    </style>
</head>
<body>
<div id="header"></div>
<div class="container">
    <h1>Your Order List</h1>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Order Date</th>
          <th>Customer ID</th>
          <th>Customer Name</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)) : ?>
          <tr><td colspan="5">No orders found.</td></tr>
        <?php else: ?>
          <?php foreach ($orders as $order) : ?>
            <tr class="order-row">
              <td><?php echo $order['order_id']; ?></td>
              <td><?php echo $order['order_date']; ?></td>
              <td><?php echo $order['user_id']; ?></td>
              <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
              <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
</div>

<script>
  fetch("header.php")
      .then(response => response.text())
      .then(data => {
          document.getElementById("header").innerHTML = data;
          let loggedInUser = localStorage.getItem("loggedInUser");
          if (loggedInUser) {
              document.getElementById("userLoginSection").innerHTML = `
                  <span>Signed in as: <b>${loggedInUser}</b> | 
                  <a href="#" id="logoutButton">Logout</a></span>
              `;
              document.getElementById("logoutButton").addEventListener("click", function() {
                  localStorage.removeItem("loggedInUser");
                  window.location.href = "login.php";
              });
          }
      })
      .catch(error => console.error("Error loading header:", error));
</script>
</body>
</html>
