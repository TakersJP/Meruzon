<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=users", "seiya03", "seiya03");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    header("Location: login.html");
    exit;
} catch (PDOException $e) {
    echo "登録エラー: " . $e->getMessage();
}
?>
