<?php
session_start();
// Check if the user is logged in and has admin privileges
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
    /* Overall layout */
    body {
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    /* Header */
    .admin-header {
        background-color: #7FBFB0;
        color: white;
        padding: 15px 20px;
        text-align: center;
    }
    .admin-header h1 {
        margin: 0;
        font-size: 24px;
    }

    /* Container layout */
    .container {
        width: 80%;
        max-width: 1200px;
        margin: 30px auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 20px;
    }

    /* Card element styling */
    .admin-card {
        background: white;
        width: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Optional card image */
    .admin-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    /* Card content */
    .admin-card-content {
        padding: 20px;
    }
    .admin-card-content h2 {
        margin-top: 0;
        font-size: 20px;
        color: #333;
    }
    .admin-card-content p {
        margin: 10px 0 20px;
        font-size: 14px;
        color: #666;
    }

    /* Button styling */
    .admin-card button {
        background-color: #7FBFB0;
        color: white;
        border: none;
        padding: 12px 20px;
        margin-bottom: 20px;
        cursor: pointer;
        font-size: 14px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }
    .admin-card button:hover {
        background-color: #6AA097;
    }

    /* Footer link (return link) */
    .footer-link {
        text-align: center;
        margin: 30px;
    }
    .footer-link a {
        color: #7FBFB0;
        text-decoration: none;
        font-weight: bold;
    }
    .footer-link a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>

    <!-- Header section -->
    <div class="admin-header">
    <h1>Administrator Dashboard</h1>
    </div>

    <div class="container">
    <!-- User management card -->
    <div class="admin-card">
        <!-- You can add an image if you want. Remove this tag if not needed. -->
        <img src="../images/users.jpg" alt="Manage Users"> 
        <div class="admin-card-content">
        <h2>Manage Users</h2>
        <p>Search users by name, delete or update user info.</p>
        <button onclick="location.href='manage_users.php'">Go to User Management</button>
        </div>
    </div>

    <!-- Product management card -->
    <div class="admin-card">
        <img src="../images/products.jpg" alt="Manage Products">
        <div class="admin-card-content">
        <h2>Manage Products</h2>
        <p>Update, rename, or delete product listings.</p>
        <button onclick="location.href='manage_items.php'">Go to Product Management</button>
        </div>
    </div>
    
    <!-- If more features, add more cards here
    <div class="admin-card">
        <img src="../images/orders.jpg" alt="Manage Orders">
        <div class="admin-card-content">
        <h2>Manage Orders</h2>
        <p>Track orders, update order status, and view history.</p>
        <button onclick="location.href='orders.php'">Go to Order Management</button>
        </div>
    </div>
    -->
    </div>

    <div class="footer-link">
    <a href="../index.php">Return to Home Page</a>
    </div>

</body>
</html>
