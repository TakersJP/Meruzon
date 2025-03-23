<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phonenum'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];
    $country = $_POST['country'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle profile image upload with safe unique filename
    $profileImageName = '';
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $extension = pathinfo($_FILES["profileImage"]["name"], PATHINFO_EXTENSION);
        $safeFileName = uniqid("profile_", true) . "." . $extension;
        $profileImageName = $targetDir . $safeFileName;
        move_uploaded_file($_FILES["profileImage"]["tmp_name"], $profileImageName);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, email, phone, address, city, state, postal_code, country, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $username, $hashed_password, $firstName, $lastName, $email, $phone, $address, $city, $state, $postalCode, $country, $profileImageName);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $errorMsg = "Error: " . $stmt->error;
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
    <title>Sign Up Page</title>
    <link href="../css/style.css" rel="stylesheet">
    <style>
        body { font-family: Georgia, serif; background-color: #f9f9f9; text-align: center; color: black; }
        .container { width: 50%; margin: 40px auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #ccc; }
        td label { font-weight: bold; }
        td input[type="text"], td input[type="password"], td input[type="email"], td input[type="file"] { width: 95%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 5px; }
        .submit { background-color: #7FBFB0; color: white; border: none; padding: 10px 15px; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .submit:hover { background-color: #66A099; }
        .field-error { color: red; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <h1>Enter Your Information</h1>
    <p id="errorMessage" class="error-message"><?php if (!empty($errorMsg)) echo htmlspecialchars($errorMsg); ?></p>
    <form action="register.php" method="post" enctype="multipart/form-data" id="registerForm">
        <table>
            <tr><td><label for="profileImage">Profile Image:</label></td><td><input type="file" name="profileImage" id="profileImage" accept="image/*"></td></tr>
            <tr><td><label for="firstName">First Name:</label></td><td><input type="text" name="firstName" id="firstName"><span class="field-error" id="error-firstName"></span></td></tr>
            <tr><td><label for="lastName">Last Name:</label></td><td><input type="text" name="lastName" id="lastName"><span class="field-error" id="error-lastName"></span></td></tr>
            <tr><td><label for="email">Email:</label></td><td><input type="email" name="email" id="email"><span class="field-error" id="error-email"></span></td></tr>
            <tr><td><label for="phonenum">Phone Number:</label></td><td><input type="text" name="phonenum" id="phonenum"><span class="field-error" id="error-phonenum"></span></td></tr>
            <tr><td><label for="address">Address:</label></td><td><input type="text" name="address" id="address"><span class="field-error" id="error-address"></span></td></tr>
            <tr><td><label for="city">City:</label></td><td><input type="text" name="city" id="city"><span class="field-error" id="error-city"></span></td></tr>
            <tr><td><label for="state">State:</label></td><td><input type="text" name="state" id="state"><span class="field-error" id="error-state"></span></td></tr>
            <tr><td><label for="postalCode">Postal Code:</label></td><td><input type="text" name="postalCode" id="postalCode"><span class="field-error" id="error-postalCode"></span></td></tr>
            <tr><td><label for="country">Country:</label></td><td><input type="text" name="country" id="country"><span class="field-error" id="error-country"></span></td></tr>
            <tr><td><label for="username">Username:</label></td><td><input type="text" name="username" id="username"><span class="field-error" id="error-username"></span></td></tr>
            <tr><td><label for="password">Password:</label></td><td><input type="password" name="password" id="password"><span class="field-error" id="error-password"></span></td></tr>
        </table>
        <br/>
        <input class="submit" type="submit" value="Create Account">
    </form>
</div>
<script src="../script/register_validation.js"></script>
</body>
</html>
