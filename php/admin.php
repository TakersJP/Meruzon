<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Page</title>
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
            width: 90%;
            margin: 40px auto;
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .table-container {
            width: 48%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 22px;
            color: black;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #F2F2F2;
            margin-top: 10px;
            text-align: left;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            color: black;
        }

        th {
            background-color: #7FBFB0;
            color: white;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }

        .hidden {
            display: none;
        }

        .password-box {
            width: 300px;
            margin: 100px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .password-box input {
            width: 80%;
            padding: 8px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .password-box button {
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #7FBFB0;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .password-box button:hover {
            background-color: #66A099;
        }
    </style>
</head>
<body>

    <div id="header"></div>

    <!-- Password meruzon_admin -->
    <div id="passwordPrompt" class="password-box">
        <h2>Admin Access Required</h2>
        <p>Enter the admin password to access this page:</p>
        <input type="password" id="adminPassword" placeholder="Enter Password">
        <button onclick="checkAdminAccess()">Submit</button>
        <p id="errorMessage" class="error-message hidden">Incorrect password. Try again.</p>
    </div>

    <div class="container hidden" id="adminContent">
        <div class="table-container">
            <h1>Administrator Sales Report by Day</h1>
            <table id="salesReportTable">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Total Order Amount</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <td id="totalSales" style="text-align: right;">$0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="table-container">
            <h1>Customer List</h1>
            <table id="customerListTable">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        function checkAdminAccess() {
            let enteredPassword = document.getElementById("adminPassword").value;
            const correctPassword = "meruzon_admin";

            if (enteredPassword === correctPassword) {
                document.getElementById("passwordPrompt").classList.add("hidden");
                document.getElementById("adminContent").classList.remove("hidden");
            } else {
                document.getElementById("errorMessage").classList.remove("hidden");
            }
        }

        document.addEventListener("DOMContentLoaded", function () {

            localStorage.removeItem("adminAccess"); 

            let salesData = [
                { date: "2025-02-25", total: 250.75 },
                { date: "2025-02-26", total: 400.20 },
                { date: "2025-02-27", total: 150.50 }
            ];

            let customerData = [
                { id: 101, firstName: "John", lastName: "Doe" },
                { id: 102, firstName: "Jane", lastName: "Smith" },
                { id: 103, firstName: "Alice", lastName: "Johnson" }
            ];

            let salesTableBody = document.querySelector("#salesReportTable tbody");
            let totalSales = 0;

            salesData.forEach(sale => {
                let row = `
                    <tr>
                        <td>${sale.date}</td>
                        <td style="text-align: right;">$${sale.total.toFixed(2)}</td>
                    </tr>
                `;
                salesTableBody.innerHTML += row;
                totalSales += sale.total;
            });

            document.getElementById("totalSales").textContent = `$${totalSales.toFixed(2)}`;

            let customerTableBody = document.querySelector("#customerListTable tbody");

            customerData.forEach(customer => {
                let row = `
                    <tr>
                        <td>${customer.id}</td>
                        <td>${customer.firstName} ${customer.lastName}</td>
                    </tr>
                `;
                customerTableBody.innerHTML += row;
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

                    document.getElementById("logoutButton").addEventListener("click", function () {
                        localStorage.removeItem("loggedInUser");
                        localStorage.removeItem("adminAccess"); // Clear admin access on logout
                        window.location.href = "login.php";
                    });
                }
            })
            .catch(error => console.error("Error loading header:", error));
    </script>

</body>
</html>
