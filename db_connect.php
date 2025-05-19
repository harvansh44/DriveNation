<?php
$servername = "localhost";  // Change if using a remote database
$username = "root";         // Default MySQL username
$password = "";             // Default MySQL password (leave empty if none)
$database = "car_marketplace";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
