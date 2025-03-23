<?php
session_start();
include 'config.php'; // Make sure this is the correct path

// Handle form submission
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: shop.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Username not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <title>Login Screen</title>
    <style>
        body {
            font-family: Georgia, serif !important;
            margin: 20px !important;
        }
        .hd-h3 {
            padding-top: 20px !important;
            color: black !important; 
            font-size: 20px; 
            text-align: center; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-top: 20px;
        }
        td div {
            color: black !important; 
            font-size: 16px; 
            font-weight: bold; 
        }
        .submit {
            background-color: #7FBFB0;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .submit:hover {
            background-color: #66A099;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
        .register-link {
            display: block; 
            margin-top: 10px; 
            font-size: 14px;
            text-decoration: none; 
            color: blue; 
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div style="margin:0 auto;text-align:center;display:inline">
    <h3 class="hd-h3">Please Login to System</h3>

    <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="login.php" method="post">
    <table style="display:inline">
        <tr>
            <td>
                <div align="right">
                    <font face="Arial, Helvetica, sans-serif" size="2">Username:</font>
                </div>
            </td>
            <td><input type="text" name="username" size="10" maxlength="50" required></td>
        </tr>
        <tr>
            <td>
                <div align="right">
                    <font face="Arial, Helvetica, sans-serif" size="2">Password:</font>
                </div>
            </td>
            <td><input type="password" name="password" size="10" maxlength="50" required></td>
        </tr>
    </table>
    <br/>
    <input class="submit" type="submit" value="Login">
    </form>


    <p><a href="register.php" class="register-link">Create new account</a></p>
</div>

</body>
</html>
