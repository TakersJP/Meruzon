<?php
session_start();
// Admin access check
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Retrieve the search keyword (if any)
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Prepare SQL to search by username or first_name/last_name
$sql = "
    SELECT user_id, username, first_name, last_name, email, is_admin
    FROM users
    WHERE (username LIKE ? OR first_name LIKE ? OR last_name LIKE ?)
    ORDER BY user_id
";
$stmt = $conn->prepare($sql);
$likeTerm = '%' . $search . '%';
$stmt->bind_param('sss', $likeTerm, $likeTerm, $likeTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
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

<h1>User Management</h1>

<div class="search-form">
    <form method="GET" action="manage_users.php">
    <input type="text" name="q" placeholder="Search by name..." 
            value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Admin?</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <?php
        $uid = $row['user_id'];
        $username = htmlspecialchars($row['username']);
        $fullName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
        $email = htmlspecialchars($row['email']);
        $isAdmin = $row['is_admin'] ? 'Yes' : 'No';
    ?>
    <tr>
        <td><?php echo $uid; ?></td>
        <td><?php echo $username; ?></td>
        <td><?php echo $fullName; ?></td>
        <td><?php echo $email; ?></td>
        <td><?php echo $isAdmin; ?></td>
        <td>
        <!-- Delete link calls delete_user.php?user_id=xxx -->
        <a class="action-link" 
            href="delete_user.php?user_id=<?php echo $uid; ?>"
            onclick="return confirm('Are you sure you want to delete this user?');">
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
