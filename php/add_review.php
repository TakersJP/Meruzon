<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $user_id = 1;
    $title = 'Review'; // Default title

    if ($item_id > 0 && $rating > 0 && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, item_id, rating, title, content, review_date) VALUES (?, ?, ?, ?, ?, CURDATE())");
        $stmt->bind_param("iiiss", $user_id, $item_id, $rating, $title, $content);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
