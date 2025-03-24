<?php
session_start();

// Check admin session
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// DB connection
require_once '../config.php';

// 1) Get daily sales summary from orders table
//    - Group by the date part of order_date
//    - Summation of total_amount
$salesSql = "
    SELECT 
        DATE(order_date) AS order_date,
        SUM(total_amount) AS daily_total
    FROM orders
    GROUP BY DATE(order_date)
    ORDER BY order_date DESC
";
$salesResult = $conn->query($salesSql);
$salesData = [];
if ($salesResult) {
    while ($row = $salesResult->fetch_assoc()) {
        // Each $row might look like: [ 'order_date' => '2025-03-22', 'daily_total' => '12.34' ]
        $salesData[] = $row;
    }
}

// 2) Get all users (or only non-admin users) from users table
$usersSql = "
    SELECT user_id, username, first_name, last_name, email, is_admin
    FROM users
    ORDER BY user_id ASC
";
$usersResult = $conn->query($usersSql);
$usersData = [];
if ($usersResult) {
    while ($row = $usersResult->fetch_assoc()) {
        // e.g. [ 'user_id'=>1, 'username'=>'bob', 'first_name'=>'Bob', 'is_admin'=>0, ... ]
        $usersData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrator Dashboard</title>
    <style>
        body {
            font-family: Georgia, serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 40px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            color: black;
        }
        th {
            background-color: #7FBFB0;
            color: white;
        }
        .section-title {
            margin-top: 40px;
            font-size: 22px;
            color: #333;
            text-align: left;
            padding: 10px 0;
        }
        .table-wrapper {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    
    <h1>Administrator Dashboard</h1>

    <!-- Sales Report Table -->
    <div class="table-wrapper">
        <div class="section-title">Sales Report (by Day)</div>
        <table>
            <thead>
                <tr>
                    <th>Order Date</th>
                    <th>Total Amount (Daily)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $grandTotal = 0.0;
            foreach ($salesData as $row) {
                $date = htmlspecialchars($row['order_date']);
                $dailyTotal = (float) $row['daily_total'];
                $grandTotal += $dailyTotal;
                echo "<tr>";
                echo "<td>{$date}</td>";
                echo "<td style='text-align:right;'>\$" . number_format($dailyTotal, 2) . "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th style="text-align:left;">Grand Total</th>
                    <th style="text-align:right;">
                        $<?php echo number_format($grandTotal, 2); ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Users Table -->
    <div class="table-wrapper">
        <div class="section-title">User List</div>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Admin?</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($usersData as $user) {
                $uid       = htmlspecialchars($user['user_id']);
                $username  = htmlspecialchars($user['username']);
                $fullName  = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                $email     = htmlspecialchars($user['email']);
                $isAdmin   = $user['is_admin'] ? "Yes" : "No";

                echo "<tr>";
                echo "<td>{$uid}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$fullName}</td>";
                echo "<td>{$email}</td>";
                echo "<td>{$isAdmin}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
