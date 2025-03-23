<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link href="../css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: Georgia, serif !important;
            margin: 20px !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #7FBFB0;
            color: white;
        }

        .hd-h1, .hd-h2 {
            font-family: Georgia, serif !important;
            padding-top: 20px !important;
            padding-left: 10px !important;
        }

        .cart-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .update-btn, .remove-btn {
            background-color: #638AB4;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .update-btn:hover, .remove-btn:hover {
            background-color: #5078A1;
        }
    </style>
</head>
<body>
    <div id="header"></div>

    <h1>Your Shopping Cart</h1>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="cartTableBody"></tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="right"><b>Order Total</b></td>
                <td align="right" id="cartTotal">$0.00</td>
            </tr>
        </tfoot>
    </table>


    <script>
        function loadCart() {
            let cartTableBody = document.getElementById("cartTableBody");
            let cartTotal = document.getElementById("cartTotal");
            let totalAmount = 0;

            cartTableBody.innerHTML = "";

            for (let i = 0; i < localStorage.length; i++) {
                let key = localStorage.key(i);
                if (key.startsWith("cart_")) {
                    let [name, price, quantity] = localStorage.getItem(key).split("|");
                    price = parseFloat(price);
                    quantity = parseInt(quantity);

                    let row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${name}</td>
                        <td>${quantity}</td>
                        <td>$${price.toFixed(2)}</td>
                        <td>$${(price * quantity).toFixed(2)}</td>
                        <td>
                            <button class="remove-btn" onclick="removeFromCart('${key}')">Remove</button>
                        </td>
                    `;
                    cartTableBody.appendChild(row);
                    totalAmount += price * quantity;
                }
            }

            cartTotal.textContent = `$${totalAmount.toFixed(2)}`;
        }

        function removeFromCart(key) {
            localStorage.removeItem(key);
            loadCart();
        }

        loadCart();

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

    <h2 class=hd-h2><a href="checkout.php">Check Out</a></h2>

    <h2 class=hd-h2><a href="listprod.php">Continue Shopping</a></h2>

</body>
</html>
