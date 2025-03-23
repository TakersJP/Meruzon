<?php
include("config.php");

$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
$reviews = [];

if ($item_id > 0) {
    $stmt = $conn->prepare("SELECT rating, content, review_date FROM reviews WHERE item_id = ? ORDER BY review_date DESC");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($reviews);
?>
