<?php
session_start();

// ログインしていなければ login.html にリダイレクト
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meruzon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <div id="header"></div>

    <script>
        // header.html を読み込む（既存のままでOK）
        fetch("header.html")
            .then(response => response.text())
            .then(data => {
                document.getElementById("header").innerHTML = data;
            })
            .catch(error => console.error("Error loading header:", error));
    </script>

    <div style="text-align: center;">
        <p>
            ✅ Signed in as: <b><?php echo htmlspecialchars($_SESSION['username']); ?></b> |
            <a href="logout.php">Logout</a>
        </p>

        <h2><a href="listprod.html">Begin Shopping</a></h2>
        <h2><a href="listorder.html">List All Orders</a></h2>
        <h2><a href="customer.html">Customer Info</a></h2>
        <h2><a href="admin.html">Administrators</a></h2>
    </div>

</body>
</html>
