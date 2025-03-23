<?php
$servername = "localhost";
$username = "seiya03";  // use the database owner's CWL
$password = "seiya03";  // seiya03's MySQL password
$dbname = "seiya03";   // the database name (same as seiya03’s CWL)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>