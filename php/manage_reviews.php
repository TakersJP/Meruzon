<?php
session_start();
// Check admin
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Retrieve the search keyword (q) or default to empty
$searchValue = trim($_GET['q'] ?? '');

// Convert search to integer for user_id matching
$userIdSearch = (int)$searchValue;

// For partial matches on username/product_name
$likeTerm = '%' . $searchValue . '%';

// Build the SQL with an OR condition
$sql = "
    SELECT r.review_id, r.rating, r.title, r.content, r.review_date,
            u.user_id AS user_id,
            u.username AS user_name,
            i.product_name AS product_name
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    JOIN items i ON r.item_id = i.item_id
    WHERE (
        (u.user_id = ?)               /* exact match on user ID */
        OR (u.username LIKE ?)        /* partial match on username */
        OR (i.product_name LIKE ?)    /* partial match on product name */
    )
    ORDER BY r.review_id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iss', $userIdSearch, $likeTerm, $likeTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Reviews</title>
    <style>
    /* Overall styling (similar to manage_users or manage_products) */
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
    </style>
</head>
<body>

<h1>Review Management</h1>

<div class="search-form">
    <form action="manage_reviews.php" method="GET">
    <input type="text" name="q" placeholder="Search by user ID, username, or product..."
            value="<?php echo htmlspecialchars($searchValue); ?>">
    <button type="submit">Search</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>Review ID</th>
        <th>User ID</th>
        <th>Username</th>
        <th>Product</th>
        <th>Rating</th>
        <th>Title</th>
        <th>Content</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <?php
        $reviewId   = $row['review_id'];
        $userId     = htmlspecialchars($row['user_id']);
        $userName   = htmlspecialchars($row['user_name']);
        $productName= htmlspecialchars($row['product_name']);
        $rating     = htmlspecialchars($row['rating']);
        $title      = htmlspecialchars($row['title']);
        $content    = htmlspecialchars($row['content']);
        $reviewDate = htmlspecialchars($row['review_date']);
        ?>
        <tr>
        <td><?php echo $reviewId; ?></td>
        <td><?php echo $userId; ?></td>
        <td><?php echo $userName; ?></td>
        <td><?php echo $productName; ?></td>
        <td><?php echo $rating; ?></td>
        <td><?php echo $title; ?></td>
        <td><?php echo $content; ?></td>
        <td><?php echo $reviewDate; ?></td>
        <td>
            <a class="action-link"
                href="delete_review.php?review_id=<?php echo $reviewId; ?>"
                onclick="return confirm('Are you sure you want to delete this review?');">
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
