<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link href="../css/style.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Georgia, serif;
            margin: 20px;
            text-align: center;
            background-color: #f9f9f9;
            color: black;
        }

        .container {
            width: 60%;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-top: 20px;
            text-align: left;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 10px;
            color: black;
        }

        th {
            background-color: #7FBFB0;
            color: white;
        }

        .checkout-info {
            margin-top: 20px;
            font-size: 18px;
        }

        .link {
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }

        .link:hover {
            text-decoration: underline;
        }

        .button {
            background-color: #7FBFB0;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #66A099;
        }
    </style>
</head>
<body>

    <div id="header"></div>

    <div class="container">
        <h1>Your Order Summary</h1>
        
        <table id="orderTable">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <td colspan="4" align="right"><b>Order Total</b></td>
                    <td align="right" id="orderTotal">$0.00</td>
                </tr>
            </tfoot>
        </table>

        <div class="checkout-info">
            <p><b>Order completed. Will be delivered soon...</b></p>
            <p>Check delivery status: <a id="deliveryLink" class="link" href="#">Track Order</a></p>
            <p><b>Your order reference number:</b> <span id="orderId"></span></p>
            <p><b>Delivering to customer:</b> <span id="customerId"></span></p>
            <p><b>Name:</b> <span id="customerName"></span></p>
        </div>

        <button class="button" onclick="goBackToShop()">Return to Shop</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        let orderData = localStorage.getItem("orderData");
        let customerName = localStorage.getItem("customerName");
        let customerId = localStorage.getItem("customerId");
        let orderId = Math.floor(Math.random() * 1000000); // Simulated Order ID

        let orderTableBody = document.querySelector("#orderTable tbody");
        let orderTotalElement = document.getElementById("orderTotal");
        let orderMessage = document.createElement("p");
        let trackOrderLink = document.getElementById("deliveryLink");
        let returnButton = document.querySelector(".button");

        if (!orderData) {
            orderMessage.innerHTML = "<b>No order found! Please place an order first.</b>";
            orderMessage.style.color = "red";
            orderMessage.style.marginTop = "20px";
            document.querySelector(".container").appendChild(orderMessage);

            trackOrderLink.style.pointerEvents = "none";
            trackOrderLink.style.color = "gray";
        } else {
            let orderItems = orderData.split("|"); 
            let orderTotal = 0;

            orderTableBody.innerHTML = "";
            orderItems.forEach(item => {
                let details = item.split(",");
                let productId = details[0];
                let productName = details[1];
                let price = parseFloat(details[2]);
                let quantity = parseInt(details[3]);
                let subtotal = price * quantity;
                orderTotal += subtotal;

                let row = `
                    <tr>
                        <td>${productId}</td>
                        <td>${productName}</td>
                        <td align="center">${quantity}</td>
                        <td align="right">$${price.toFixed(2)}</td>
                        <td align="right">$${subtotal.toFixed(2)}</td>
                    </tr>
                `;
                orderTableBody.innerHTML += row;
            });

            orderTotalElement.textContent = `$${orderTotal.toFixed(2)}`;

            document.getElementById("orderId").textContent = orderId;
            document.getElementById("customerId").textContent = customerId ? customerId : "Unknown";
            document.getElementById("customerName").textContent = customerName ? customerName : "Guest";
            trackOrderLink.href = `ship.php?orderId=${orderId}`;

            localStorage.removeItem("orderData");
        }

        returnButton.addEventListener("click", function() {
            window.location.href = "shop.php";
        });

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
        });

    </script>

</body>
</html>
