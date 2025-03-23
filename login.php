<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=users", "seiya03", "seiya03");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        header("Location: listprod.html"); // ✅ 成功時
        exit;
    } else {
        echo "❌ ユーザー名またはパスワードが間違っています。";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>
