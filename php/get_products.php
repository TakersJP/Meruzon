<?php
include("config.php");

$result = $conn->query("SELECT item_id, product_name, price, product_image FROM items");
$productArray = [];

while ($row = $result->fetch_assoc()) {
    $name = strtolower($row['product_name']);
    if (str_contains($name, 'earrings') || str_contains($name, 'bracelet') || str_contains($name, 'necklace')) {
        $category = "Accessories";
    } elseif (str_contains($name, 'tote') || str_contains($name, 'candle') || str_contains($name, 'coaster')) {
        $category = "Lifestyle Goods";
    } elseif (str_contains($name, 'mug') || str_contains($name, 'vase') || str_contains($name, 'plate')) {
        $category = "Ceramics & Glass Crafts";
    } elseif (str_contains($name, 'scarf') || str_contains($name, 'mittens') || str_contains($name, 'beanie')) {
        $category = "Knitted Goods";
    } else {
        $category = "Craft Supplies & Tools";
    }

    $productArray[] = [
        'id' => $row['item_id'],
        'name' => $row['product_name'],
        'price' => floatval($row['price']),
        'category' => $category,
        'image' => "../img/" . $row['product_image']
    ];
}

header('Content-Type: application/json');
echo json_encode($productArray);
?>
