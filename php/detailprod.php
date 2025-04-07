<?php
include("config.php"); // Connects to database

$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

if ($item_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <title>Product Detail</title>
    
    <style>
        body {
            font-family: Georgia, serif !important;
            margin: 20px !important;
            background-color: #f9f9f9;
            color: black;
        }

        .container {
            width: 80%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            display: block;
            margin: 20px auto;
            max-width: 250px;
            height: auto;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-top: 20px;
            color: black;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 10px;
            font-size: 16px;
        }

        th {
            background-color: #7FBFB0;
            color: white;
        }

        .cart-section {
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-section input {
            width: 50px;
            text-align: center;
            font-size: 16px;
        }

        .add-to-cart {
            background-color: #638AB4;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .add-to-cart:hover {
            background-color: #5078A1;
        }

        .continue-shopping {
            margin-top: 15px;
        }

        .continue-shopping a {
            text-decoration: none;
            font-size: 16px;
            color: #638AB4;
        }

        .continue-shopping a:hover {
            text-decoration: underline;
        }

        .review-section {
            margin-top: 30px;
        }

        .review-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .review-table th, .review-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .review-table th {
            background-color: #7FBFB0;
            color: white;
        }

        .review-form {
            margin-top: 20px;
        }

        .review-form input, .review-form textarea {
            width: 50%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }

        .review-form button {
            background-color: #7FBFB0;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .review-form button:hover {
            background-color: #66A099;
        }
    </style>
</head>
<body>
<div id="header"></div>

<div class="container">
    <?php if ($product): ?>
        <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>

        
        <img id="productImage" src="../img/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Product Image" class="product-img" onerror="this.onerror=null;this.src='../img/sample.jpg';">


        <table>
            <tr>
                <th>Product ID</th>
                <td><span id="productId"><?php echo $product['item_id']; ?></span></td>
            </tr>

            <tr>
                <th>Price</th>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
            </tr>
            <tr>
                <th>Stock Available</th>
                <td><?php echo $product['quantity']; ?></td>
            </tr>
        </table>  

        <div class="cart-section">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>">
            <button class="add-to-cart" id="addToCartBtn">Add to Cart</button>
        </div>
        
        <div class="continue-shopping">
            <a href="listprod.php">Continue Shopping</a>
        </div>

        <!-- Reviews (static placeholder) -->
        <div class="review-section">
            <h3>Reviews</h3>
            <table class="review-table">
                <thead>
                    <tr>
                        <th>Rate</th>
                        <th>Date</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody id="reviewTable">
                </tbody>
            </table>
        </div>

        <div class="review-form">
            <h3>Add Review</h3>
            <input type="number" id="reviewRate" min="1" max="5" placeholder="Rate (1-5)" required>
            <textarea id="reviewComment" placeholder="Write your review..." required></textarea>
            <button type="submit" id="submitReviewBtn">Submit Review</button>
        </div>
    <?php else: ?>
        <h2>Product Not Found</h2>
        <p>Sorry, the product you're looking for doesn't exist.</p>
    <?php endif; ?>
</div>

<script>
    document.getElementById("addToCartBtn")?.addEventListener("click", function () {
    const itemId = document.getElementById("productId").textContent;
    const quantity = parseInt(document.getElementById("quantity").value);

    fetch("add_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `item_id=${itemId}&quantity=${quantity}`
    })
    .then(response => {
        if (!response.ok) throw new Error("Failed to add to cart.");
        return response.text();
    })
    .then(result => {
        // Redirect to cart if successful
        window.location.href = "showcart.php";
    })
    .catch(error => {
        alert("Error: " + error.message);
    });
});


    function loadReviews(productId) {
    fetch(`get_reviews.php?item_id=${productId}`)
        .then(response => response.json())
        .then(reviews => {
            const reviewTable = document.getElementById("reviewTable");
            reviewTable.innerHTML = "";
            reviews.forEach(review => {
                const row = document.createElement("tr");
                row.innerHTML = `<td>${review.rating}</td><td>${review.review_date}</td><td>${review.content}</td>`;
                reviewTable.appendChild(row);
            });
        });
}

document.getElementById("submitReviewBtn").addEventListener("click", function() {
    let rate = document.getElementById("reviewRate").value;
    let comment = document.getElementById("reviewComment").value;
    let productId = document.getElementById("productId").textContent;

    if (!rate || !comment) {
        alert("Please fill out both fields.");
        return;
    }

    fetch("add_review.php", { 
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `item_id=${productId}&rating=${rate}&content=${encodeURIComponent(comment)}`
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            document.getElementById("reviewRate").value = "";
            document.getElementById("reviewComment").value = "";
            loadReviews(productId);
        } else {
            alert("Failed to submit review.");
        }
    });
});

// Call loadReviews() after loading product details:
window.onload = function() {
    const productId = new URLSearchParams(window.location.search).get("id");
    loadReviews(productId);
};


    // Load header
    fetch("header.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header").innerHTML = data;
            let loggedInUser = localStorage.getItem("loggedInUser");
            if (loggedInUser) {
                document.getElementById("userLoginSection").innerHTML = 
                    `<span>Signed in as: <b>${loggedInUser}</b> | 
                    <a href="#" id="logoutButton">Logout</a></span>`;
                document.getElementById("logoutButton").addEventListener("click", function() {
                    localStorage.removeItem("loggedInUser");
                    window.location.href = "login.php";
                });
            }
        });
</script>

</body>
</html>