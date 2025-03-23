<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
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
        }

        h1 {
            font-size: 24px;
            color: black;
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
        img.profile-pic {
        border-radius: 50%;
        width: 120px;
        height: 120px;
        object-fit: cover;
        margin-bottom: 20px;
        border: 2px solid #ccc;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h1>Customer Profile</h1>

    <form action="update_profile_image.php" method="post" enctype="multipart/form-data" style="margin-top: 20px;">
        <input type="file" name="newProfileImage" accept="image/*" required>
        <button type="submit">Update Profile Image</button>
    </form>

    <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
        <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="profile-pic">
    <?php else: ?>
        <img src="../img/default-profile.png" alt="Default Profile" class="profile-pic">
    <?php endif; ?>

    <table>
        <tr><th>ID</th><td><?php echo htmlspecialchars($user['user_id']); ?></td></tr>
        <tr><th>First Name</th><td><?php echo htmlspecialchars($user['first_name']); ?></td></tr>
        <tr><th>Last Name</th><td><?php echo htmlspecialchars($user['last_name']); ?></td></tr>
        <tr><th>Email</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
        <tr><th>Phone</th><td><?php echo htmlspecialchars($user['phone']); ?></td></tr>
        <tr><th>Address</th><td><?php echo htmlspecialchars($user['address']); ?></td></tr>
        <tr><th>City</th><td><?php echo htmlspecialchars($user['city']); ?></td></tr>
        <tr><th>State</th><td><?php echo htmlspecialchars($user['state']); ?></td></tr>
        <tr><th>Postal Code</th><td><?php echo htmlspecialchars($user['postal_code']); ?></td></tr>
        <tr><th>Country</th><td><?php echo htmlspecialchars($user['country']); ?></td></tr>
        <tr><th>Username</th><td><?php echo htmlspecialchars($user['username']); ?></td></tr>
    </table>
</div>

</body>
</html>
