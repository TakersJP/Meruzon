<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Meruzon</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/style.css" rel="stylesheet">
</head>
<body>

    <?php include 'header.php'; ?>

    <h2 align="center">
        <?php if (isset($_SESSION['username'])): ?>
            Signed in as: <b><?php echo htmlspecialchars($_SESSION['username']); ?></b> | 
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </h2>

    <h2 align="center"><a href="listprod.php">Begin Shopping</a></h2>

    <h2 align="center"><a href="listorder.php">List All Orders</a></h2>

    <h2 align="center"><a href="customer.php">Customer Info</a></h2>

    <h2 align="center"><a href="admin.php">Administrators</a></h2>

</body>
</html>