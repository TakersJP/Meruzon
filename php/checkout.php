<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="../css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: Georgia, serif !important;
            margin: 20px !important;
        }

        table {
            width: 60%;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin: 0 auto;
            color: black;
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

        .hd-h1 {
            font-family: Georgia, serif !important;
            padding-top: 20px !important;
            padding-left: 10px !important;
            margin-bottom: 20px;
            text-align: center;
            color: black;
        }

        .checkout-container {
            text-align: center;
            margin-top: 20px;
        }

        .checkout-form {
            display: inline-block;
            text-align: left;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>

    <div id="header"></div>

    <h1 class="hd-h1">Enter your Payment Information to Complete the Transaction:</h1>

    <div class="checkout-container">
        <form class="checkout-form" id="checkoutForm">
            <table>
                <tbody>
                    <tr>
                        <td><label>Payment Type:</label></td>
                        <td>
                            <input type="radio" id="visa" name="paymentType" value="VISA" required>
                            <label for="visa">VISA</label><br>
                            <input type="radio" id="master" name="paymentType" value="Mastercard" required>
                            <label for="master">Mastercard</label><br>
                            <input type="radio" id="amex" name="paymentType" value="AMEX" required>
                            <label for="amex">AMEX</label><br>
                            <input type="radio" id="jcb" name="paymentType" value="JCB" required>
                            <label for="jcb">JCB</label>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="paymentNumber">Payment Number:</label></td>
                        <td><input type="text" id="paymentNumber" required></td>
                    </tr>
                    <tr>
                        <td><label for="paymentExpiryDate">Expiry Date:</label></td>
                        <td><input type="text" id="paymentExpiryDate" placeholder="MM/YY" required></td>
                    </tr>
                    <tr>
                        <td><button type="reset">Reset</button></td>
                        <td><button type="submit">Submit</button></td>
                    </tr>
                </tbody>
            </table>
            <p id="errorMessage" class="error-message"></p>
        </form>
    </div>

    <script>
        document.getElementById("checkoutForm").addEventListener("submit", function(event) {
            event.preventDefault(); 

            let paymentType = document.querySelector("input[name='paymentType']:checked")?.value;
            let paymentNumber = document.getElementById("paymentNumber").value.trim();
            let paymentExpiryDate = document.getElementById("paymentExpiryDate").value.trim();
            let errorMessage = document.getElementById("errorMessage");

            if (!paymentType || !paymentNumber || !paymentExpiryDate) {
                errorMessage.textContent = "All fields are required!";
                errorMessage.style.display = "block";
                return;
            }

            if (!/^\d{16}$/.test(paymentNumber)) {
                errorMessage.textContent = "Invalid payment number! Enter 16 digits without spaces.";
                errorMessage.style.display = "block";
                return;
            }

            if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(paymentExpiryDate)) {
                errorMessage.textContent = "Invalid expiry date format! Use MM/YY.";
                errorMessage.style.display = "block";
                return;
            }

            localStorage.setItem("paymentType", paymentType);
            localStorage.setItem("paymentNumber", paymentNumber);
            localStorage.setItem("paymentExpiryDate", paymentExpiryDate);
            localStorage.setItem("orderDate", new Date().toLocaleString());

            fetch("process_payment.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `paymentType=${encodeURIComponent(paymentType)}&paymentNumber=${encodeURIComponent(paymentNumber)}&paymentExpiryDate=${encodeURIComponent(paymentExpiryDate)}`
            })
            .then(response => response.json()) // Parse as JSON
            .then(data => {
                if (data.success && data.order_id) {
                    window.location.href = `order-confirmation.php?order_id=${data.order_id}`;
                } else {
                    errorMessage.textContent = data.message || "Something went wrong. Please try again.";
                    errorMessage.style.display = "block";
                }
            })
            .catch(error => {
                console.error("Error submitting payment:", error);
                errorMessage.textContent = "Error submitting payment.";
                errorMessage.style.display = "block";
            });
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
    </script>

</body>
</html>