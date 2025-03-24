<?php
session_start();
// Ensure that only an admin can access this page
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Retrieve the search keyword if provided
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Search in the items table by product_name
$sql = "
    SELECT item_id, product_id, product_name, price, quantity, product_image
    FROM items
    WHERE product_name LIKE ?
    ORDER BY item_id
";
$stmt = $conn->prepare($sql);
$likeTerm = '%' . $search . '%';
$stmt->bind_param('s', $likeTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <style>
    body {
        font-family: "Segoe UI", Tahoma, sans-serif;
        background-color: #f4f4f4;
        margin: 20px;
    }
    h1 {
        text-align: center;
    }
    .search-form {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #FFF;
    }
    th, td {
        border: 1px solid #cccccc;
        padding: 8px;
    }
    th {
        background-color: #7FBFB0;
        color: #fff;
    }
    form.rename-form {
        display: inline-block;
        margin: 0;
    }
    input[name="new_name"] {
        width: 120px;
    }
    .action-link {
        color: #c00;
        text-decoration: none;
    }
    .action-link:hover {
        text-decoration: underline;
    }
    .back-link {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        color: #7FBFB0;
        font-weight: bold;
    }
    .back-link:hover {
        text-decoration: underline;
    }
    .image-cell img {
        max-width: 80px;
        height: auto;
    }
    </style>
</head>
<body>

<h1>Product Management (items table)</h1>

<div class="search-form">
    <form method="GET" action="manage_products.php">
    <input type="text" name="q" placeholder="Search product name..."
                value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>Item ID</th>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Image</th>
        <th>Rename</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <?php
        $itemId = $row['item_id'];
        $prodId = $row['product_id'];
        $pname  = htmlspecialchars($row['product_name']);
        $price  = htmlspecialchars($row['price']);
        $qty    = htmlspecialchars($row['quantity']);
    ?>
    <tr>
        <td><?php echo $itemId; ?></td>
        <td><?php echo $prodId; ?></td>
        <td><?php echo $pname; ?></td>
        <td><?php echo $price; ?></td>
        <td><?php echo $qty; ?></td>
        <td class="image-cell">
        <?php if (!empty($img)): ?>
            <img src="<?php echo $img; ?>" alt="Product Image">
        <?php else: ?>
            No Image
        <?php endif; ?>
        </td>
        <td>
        <!-- Rename form that posts to rename_product.php -->
        <form class="rename-form" method="POST" action="rename_product.php">
            <input type="hidden" name="item_id" value="<?php echo $itemId; ?>">
            <input type="text" name="new_name" placeholder="New name">
            <button type="submit">Update</button>
        </form>
        </td>
        <td>
        <a class="action-link"
            href="delete_product.php?item_id=<?php echo $itemId; ?>"
            onclick="return confirm('Are you sure you want to delete this product record?');">
            Delete
        </a>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<p style="text-align:center;">
    <a class="back-link" href="admin.php">Back to Admin Dashboard</a>
</p>

</body>
</html>
