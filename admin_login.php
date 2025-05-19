<?php
session_start();
include 'db_connect.php';

// Admin credentials - hardcoded for security
$admin_username = "admin"; // You can change this to your preferred admin username
$admin_password = "admin123"; // You should use a strong password in production

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["admin_username"];
    $password = $_POST["admin_password"];
    
    // Check if credentials match the admin credentials
    if ($username === $admin_username && $password === $admin_password) {
        // Set admin session variables
        $_SESSION["user_id"] = 0; // Special ID for admin
        $_SESSION["user_type"] = "admin";
        $_SESSION["username"] = "Administrator";
        
        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Invalid admin credentials. <a href='admin_login.html'>Try again</a>";
    }
}
?>