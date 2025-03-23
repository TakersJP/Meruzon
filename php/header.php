<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <img src="../img/store_logo.png" alt="Logo">
    <h1 align="center">
        <a href="shop.php" style="color: #3399FF; text-decoration: none;">Meruzon</a>
    </h1>
    <nav>
        <a href="shop.php">Home</a>
        <a href="listprod.php">Shopping</a>
        <a href="listorder.php">Orders</a>
        <a href="customer.php">Customer</a>
        <a href="admin.php">Administrators</a>
        <a href="showcart.php">Cart</a>
        <span id="userLoginSection">
        <?php if (isset($_SESSION['username'])): ?>
            <span style="color: white;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span> |
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php" id="loginMenu">Login</a>
        <?php endif; ?>
        </span>
    </nav>
</header>
