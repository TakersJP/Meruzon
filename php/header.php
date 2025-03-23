<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

$profileImage = "../img/default-profile.png"; // default
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT profile_image FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($profileImagePath);
    $stmt->fetch();
    $stmt->close();

    if (!empty($profileImagePath) && file_exists($profileImagePath)) {
        $profileImage = "../" . ltrim($profileImagePath, '/'); 
    }
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
        <span id="userLoginSection" style="display: flex; align-items: center; gap: 10px;">
        <?php if (isset($_SESSION['username'])): ?>
            <img src="<?php echo $profileImage ? $profileImage : '../img/default-profile.png'; ?>" alt="Profile Image" width="40" height="40" style="border-radius: 50%; vertical-align: middle; margin-right: 8px;">
            <span style="color: white;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span> |
            <a href="logout.php" style="color: white;">Logout</a>
        <?php else: ?>
            <a href="login.php" id="loginMenu">Login</a>
        <?php endif; ?>
        </span>
    </nav>
</header>
