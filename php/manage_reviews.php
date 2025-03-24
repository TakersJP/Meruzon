<?php
session_start();
// Check admin
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Optional: get search keyword
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Example: search by review title OR partial content OR user’s username
$sql = "
    SELECT r.review_id, r.rating, r.title, r.content, r.review_date, 
            u.username AS user_name,
            i.product_name AS product_name
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    JOIN items i ON r.item_id = i.item_id
    WHERE (r.title LIKE ? 
        OR r.content LIKE ?
        OR u.username LIKE ?)
    ORDER BY r.review_id
";
$stmt = $conn->prepare($sql);
$likeTerm = '%'.$search.'%';
$stmt->bind_param('sss', $likeTerm, $likeTerm, $likeTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Reviews</title>
    <style>
        /* Add your own styling */
    </style>
</head>
<body>

<h1>Review Management</h1>

<!-- Search form -->
<form action="manage_reviews.php" method="GET">
    <input type="text" name="q" placeholder="Search reviews..." 
        value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
    <tr>
        <th>ID</th>
        <th>User</th>
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
        $userName   = htmlspecialchars($row['user_name']);
        $productName= htmlspecialchars($row['product_name']);
        $rating     = htmlspecialchars($row['rating']);
        $title      = htmlspecialchars($row['title']);
        $content    = htmlspecialchars($row['content']);
        $reviewDate = htmlspecialchars($row['review_date']);
    ?>
    <tr>
        <td><?php echo $reviewId; ?></td>
        <td><?php echo $userName; ?></td>
        <td><?php echo $productName; ?></td>
        <td><?php echo $rating; ?></td>
        <td><?php echo $title; ?></td>
        <td><?php echo $content; ?></td>
        <td><?php echo $reviewDate; ?></td>
        <td>
        <!-- Delete link -->
        <a href="delete_review.php?review_id=<?php echo $reviewId; ?>"
            onclick="return confirm('Are you sure you want to delete this review?');">
                Delete
        </a>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<p><a href="admin.php">Back to Admin Dashboard</a></p>

</body>
</html>
