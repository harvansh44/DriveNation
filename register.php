<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $result = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($result->num_rows > 0) {
        header("Location: register.html?error=Email Already");
        exit();
    }

    // Insert user (allow duplicate usernames)
    $conn->query("INSERT INTO users (username, email, password, user_type, created_at) VALUES 
                 ('{$_POST['username']}', '$email', '{$_POST['password']}', '{$_POST['user_type']}', NOW())");

    header("Location: register.html?success=Registration successful!");
    exit();
}
?>
