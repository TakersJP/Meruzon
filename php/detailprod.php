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

    <!-- Header -->
    <div id="header"></div>

    <div class="container">
        <h2 id="productName">Product Name</h2>
        <p><strong>Description:</strong> <span id="productDescription"></span></p>

        <!-- Product Image -->
        <img id="productImage" src="img/sample.jpg" alt="Product Image" class="product-img">
        
        <table>
            <tr>
                <th>Id</th>
                <td><span id="productId"></span></td>
            </tr>
            <tr>
                <th>Price</th>
                <td>$<span id="productPrice"></span></td>
            </tr>
        </table>  

        <div class="cart-section">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" value="1" min="1">
            <button class="add-to-cart" id="addToCartBtn">Add to Cart</button>
        </div>
        
        <div class="continue-shopping">
            <a href="listprod.php">Continue Shopping</a>
        </div>

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
                <tbody id="reviewTable"></tbody>
            </table>
        </div>

        <div class="review-form">
            <h3>Add Review</h3>
            <input type="number" id="reviewRate" min="1" max="5" placeholder="Rate (1-5)" required>
            <textarea id="reviewComment" placeholder="Write your review..." required></textarea>
            <button type="submit" id="submitReviewBtn">Submit Review</button>
        </div>
    </div>

    <script>
        // (1) Define product data (15 items)
        const products = [
            { id: 1, name: "Handmade Beaded Earrings", description: "Beautiful handmade earrings made with high-quality beads. A perfect accessory to brighten your outfit.", price: 15.00, image: "img/earrings.png" },
            { id: 2, name: "Leather Bracelet", description: "Handcrafted leather bracelet. Durable and stylish.", price: 18.00, image: "img/bracelet.png" },
            { id: 3, name: "Resin Pendant Necklace", description: "A resin pendant necklace with a minimalistic design.", price: 22.00, image: "img/pendant.png" },
            { id: 4, name: "Minimalist Tote Bag", description: "Spacious and durable tote bag for everyday use.", price: 25.00, image: "img/totebag.png" },
            { id: 5, name: "Scented Candle", description: "Relaxing scented candle to lighten up your mood.", price: 12.00, image: "img/candle.png" },
            { id: 6, name: "Wooden Coaster Set", description: "A set of wooden coasters for your favorite drinks.", price: 20.00, image: "img/coasters.png" },
            { id: 7, name: "Ceramic Coffee Mug", description: "Handmade ceramic mug for your coffee and tea.", price: 18.00, image: "img/coffeemug.png" },
            { id: 8, name: "Glass Vase", description: "Elegant glass vase for flowers or home decoration.", price: 40.00, image: "img/vase.png" },
            { id: 9, name: "Hand-Painted Plate", description: "Uniquely hand-painted ceramic plate.", price: 35.00, image: "img/plate.png" },
            { id: 10, name: "Knitted Scarf", description: "Warm and cozy knitted scarf.", price: 30.00, image: "img/scarf.png" },
            { id: 11, name: "Wool Mittens", description: "Soft wool mittens for cold weather.", price: 20.00, image: "img/mittens.png" },
            { id: 12, name: "Crocheted Beanie", description: "Stylish crocheted beanie for casual wear.", price: 28.00, image: "img/beanie.png" },
            { id: 13, name: "DIY Beading Kit", description: "Perfect kit for creating your own beaded jewelry.", price: 12.00, image: "img/beadingkit.png" },
            { id: 14, name: "Embroidery Starter Set", description: "All-in-one set for embroidery beginners.", price: 15.00, image: "img/embroidery.png" },
            { id: 15, name: "Basic Sewing Kit", description: "Essential tools for sewing projects.", price: 10.00, image: "img/sewingkit.png" }
        ];

        // (2) Retrieve the product ID from the URL, find the product, and display its details
        function loadProductDetails() {
            // Get the query parameter ?id=xxx
            const urlParams = new URLSearchParams(window.location.search);
            const productId = parseInt(urlParams.get("id"));

            // Find the product with the matching id in the products array
            const product = products.find(p => p.id === productId);

            if (product) {
                document.getElementById("productName").textContent = product.name;
                document.getElementById("productDescription").textContent = product.description;
                document.getElementById("productId").textContent = product.id;
                document.getElementById("productPrice").textContent = product.price.toFixed(2);
                document.getElementById("productImage").src = product.image;
            } else {
                // 万が一商品が見つからない場合
                document.getElementById("productName").textContent = "Product Not Found";
                document.getElementById("productDescription").textContent = "No description available.";
                document.getElementById("productId").textContent = "N/A";
                document.getElementById("productPrice").textContent = "0.00";
                document.getElementById("productImage").src = "img/sample.jpg";
            }
        }

        // (3) Initialize sample reviews
        let reviews = [
            { rate: 4, date: "2024-12-06", comment: "Really nice product!" },
            { rate: 5, date: "2024-12-07", comment: "Absolutely loved it!" }
        ];

        function displayReviews() {
            const reviewTable = document.getElementById("reviewTable");
            reviewTable.innerHTML = "";  
            
            reviews.forEach(review => {
                const row = document.createElement("tr");
                row.innerHTML = `<td>${review.rate}</td><td>${review.date}</td><td>${review.comment}</td>`;
                reviewTable.appendChild(row);
            });
        }

        document.getElementById("submitReviewBtn").addEventListener("click", function() {
            let rate = document.getElementById("reviewRate").value;
            let comment = document.getElementById("reviewComment").value;
            let date = new Date().toISOString().split("T")[0];

            if (!rate || !comment) {
                alert("Please fill out both fields.");
                return;
            }

            reviews.push({ rate, date, comment });
            displayReviews();

            document.getElementById("reviewRate").value = "";
            document.getElementById("reviewComment").value = "";
        });

        document.getElementById("addToCartBtn").addEventListener("click", function() {
            let quantity = document.getElementById("quantity").value;
            alert(`Added ${quantity} item(s) to the cart!`);
        });

        // (4) Load product details and reviews when the page is fully loaded
        window.onload = function() {
            loadProductDetails();
            displayReviews();
        };

        // (5) Fetch the header
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
            })
            .catch(error => console.error("Error loading header:", error));
    </script>
</body>
</html>
