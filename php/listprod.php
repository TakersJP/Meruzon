<?php
session_start();
include("config.php");

$products = [];
$query = "SELECT item_id, product_name, price, product_image FROM items";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <title>Meruzon</title>

    <style>
        body {
            font-family: Georgia, serif !important;
            margin: 20px !important;
            background-color: #f9f9f9;
        }

        h1, h3, label, p {
            color: black;
        }

        table {
            font-size: 18px;
            width: 100%;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-top: 20px;
            color: black;
        }

        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #7FBFB0;
            color: white;
        }

        .category-select, .search-box {
            font-size: 16px;
            padding: 5px;
            width: 200px;
        }

        .add-to-cart {
            background-color: #638AB4;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
        }

        .add-to-cart:hover {
            background-color: #5078A1;
        }

        .img {
            max-width: 120px;
            height: auto;
            display: block;
            margin: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div id="header"></div>

    <h1>Browse Products By Category and Search by Product Name:</h1>

    <form onsubmit="event.preventDefault(); filterProducts();">
        <p align="left">
            <select id="categoryFilter" class="category-select" onchange="filterProducts()">
                <option value="All">All</option>
                <option value="Accessories">Accessories</option>
                <option value="Lifestyle Goods">Lifestyle Goods</option>
                <option value="Ceramics & Glass Crafts">Ceramics & Glass Crafts</option>
                <option value="Knitted Goods">Knitted Goods</option>
                <option value="Craft Supplies & Tools">Craft Supplies & Tools</option>
            </select>

            <input type="text" id="productSearch" class="search-box" placeholder="Search product name">
            <button type="submit">Submit</button>
            <button type="reset" onclick="resetFilters()">Reset</button>
        </p>
    </form>

    <h3>All Products</h3>

    <table>
        <thead>
            <tr>
                <th></th>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Category</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody id="productTable"></tbody>
    </table>

    <script>
        let products = [];

        function fetchProducts() {
            fetch('get_products.php')
                .then(response => response.json())
                .then(data => {
                    products = data;
                    displayProducts();
                })
                .catch(error => console.error('Error loading products:', error));
        }

        function displayProducts(filteredProducts = products) {
            const tableBody = document.getElementById("productTable");
            tableBody.innerHTML = "";

            filteredProducts.forEach(product => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td style="text-align: center;">
                        <button class="add-to-cart" onclick="addToCart(${product.id}, '${product.name}', ${product.price})">Add to Cart</button>
                    </td>
                    <td><a href="detailprod.php?id=${product.id}">${product.name}</a></td>
                    <td><img class="img" src="${product.image}" alt="${product.name}"></td>
                    <td>${product.category}</td>
                    <td>$${product.price.toFixed(2)}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        function filterProducts() {
            const category = document.getElementById("categoryFilter").value;
            const searchText = document.getElementById("productSearch").value.toLowerCase().trim();

            const filteredProducts = products.filter(product => {
                const matchesCategory = category === "All" || product.category === category;
                const matchesSearch = product.name.toLowerCase().includes(searchText);
                return matchesCategory && matchesSearch;
            });

            displayProducts(filteredProducts);
        }

        function resetFilters() {
            document.getElementById("categoryFilter").value = "All";
            document.getElementById("productSearch").value = "";
            displayProducts();
        }

        function addToCart(id, name, price) {
            let cartKey = `cart_${id}`;
            let existingItem = localStorage.getItem(cartKey);

            if (existingItem) {
                let itemData = existingItem.split("|");
                let newQuantity = parseInt(itemData[2]) + 1;
                localStorage.setItem(cartKey, `${name}|${price}|${newQuantity}`);
            } else {
                localStorage.setItem(cartKey, `${name}|${price}|1`);
            }

            alert(`Added "${name}" to cart!`);
        }

        fetchProducts();

        fetch("header.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("header").innerHTML = data;

                let loggedInUser = localStorage.getItem("loggedInUser");
                if (loggedInUser) {
                    document.getElementById("userLoginSection").innerHTML = `
                        <span>Signed in as: <b>${loggedInUser}</b> | 
                        <a href="#" id="logoutButton">Logout</a></span>
                    `;

                    document.getElementById("logoutButton").addEventListener("click", function() {
                        localStorage.removeItem("loggedInUser");
                        window.location.href = "login.php";
                    });
                }
            })
            .catch(error => console.error("Error loading header:", error));

    </script>

</body>
</html>
