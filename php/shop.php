<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Meruzon</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/style.css" rel="stylesheet">

<style>
    .search-section {
        text-align: center;
        margin: 40px auto;
    }

    .search-box-large {
        width: 60%;
        font-size: 20px;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .search-button {
        font-size: 20px;
        padding: 12px 24px;
        margin-left: 10px;
        border-radius: 8px;
        border: none;
        background-color: #7FBFB0;
        color: white;
        cursor: pointer;
    }

    .category-section {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 30px;
        margin: 50px;
    }

    .category-card {
        width: 200px;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        color: black;
    }

    .category-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
    }

    .category-card span {
        display: block;
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
    }

    .product-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        padding: 20px;
    }

    .product-card {
        border: 1px solid #ccc;
        border-radius: 10px;
        width: 220px;
        padding: 10px;
        background-color: #f9f9f9;
        text-align: center;
    }

    .product-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
    }

    .product-name {
        font-weight: bold;
        margin-top: 10px;
        cursor: pointer;
    }

    .product-price {
        color: #555;
        margin-top: 5px;
    }
</style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="search-section">
    <input type="text" id="mainSearchBox" class="search-box-large" placeholder="Search products...">
    <button onclick="filterProducts()" class="search-button">Search</button>
    <button onclick="resetFilters()" class="search-button">Reset</button> <!-- 🔁 追加 -->
</div>

<h2 align="center">Browse by Category</h2>
<div class="category-section">
    <div class="category-card" onclick="filterProducts('Accessories')">
        <img src="../img/pendant.png" alt="Accessories">
        <span>Accessories</span>
    </div>
    <div class="category-card" onclick="filterProducts('Lifestyle Goods')">
        <img src="../img/plate.png" alt="Lifestyle Goods">
        <span>Lifestyle Goods</span>
    </div>
    <div class="category-card" onclick="filterProducts('Ceramics & Glass Crafts')">
        <img src="../img/vase.png" alt="Ceramics & Glass Crafts">
        <span>Ceramics & Glass Crafts</span>
    </div>
    <div class="category-card" onclick="filterProducts('Knitted Goods')">
        <img src="../img/scarf.png" alt="Knitted Goods">
        <span>Knitted Goods</span>
    </div>
    <div class="category-card" onclick="filterProducts('Craft Supplies & Tools')">
        <img src="../img/sewingkit.png" alt="Craft Supplies & Tools">
        <span>Craft Supplies & Tools</span>
    </div>
</div>

<h3 align="center">Product List</h3>
<div id="productList" class="product-grid"></div>

<script>
    let allProducts = [];
    let currentCategory = null;

    function fetchProducts() {
        fetch('get_products.php')
            .then(res => res.json())
            .then(data => {
                allProducts = data;
                displayProducts(allProducts);
            });
    }

    function displayProducts(products) {
        const container = document.getElementById('productList');
        container.innerHTML = "";

        if (products.length === 0) {
            container.innerHTML = "<p>No products found.</p>";
            return;
        }

        products.forEach(p => {
            const card = document.createElement('div');
            card.className = "product-card";
            card.innerHTML = `
                <img src="${p.image}" alt="${p.name}" onclick="goToDetail(${p.id})">
                <div class="product-name" onclick="goToDetail(${p.id})">${p.name}</div>
                <div class="product-price">$${p.price.toFixed(2)}</div>
            `;
            container.appendChild(card);
        });
    }

    function filterProducts(category = currentCategory) {
        currentCategory = category;
        const keyword = document.getElementById('mainSearchBox').value.toLowerCase().trim();
        const result = allProducts.filter(p => {
            const matchKeyword = keyword === "" || p.name.toLowerCase().includes(keyword);
            const matchCategory = category === null || p.category === category;
            return matchKeyword && matchCategory;
        });
        displayProducts(result);
    }

    function resetFilters() {
        document.getElementById("mainSearchBox").value = "";
        currentCategory = null;
        displayProducts(allProducts);
    }

    function goToDetail(id) {
        window.location.href = `detailprod.php?id=${id}`;
    }

    fetchProducts();
</script>

</body>
</html>
