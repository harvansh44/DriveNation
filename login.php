<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_type"] = $user["user_type"];
        $_SESSION["username"] = $user["username"];

        if ($user["user_type"] == "admin") {
            echo "<div class='success-container'>
                    <div class='success-message'>
                        <h2>Admin Login Successful!</h2>
                        <p>Welcome back, Admin!</p>
                        <a href='admin_dashboard.php' class='dashboard-link'>Go to Admin Dashboard →</a>
                    </div>
                  </div>";
        } elseif ($user["user_type"] == "seller") {
            echo "<div class='success-container'>
                    <div class='success-message'>
                        <h2>Login Successful!</h2>
                        <p>Welcome back! You are now redirected to your seller dashboard.</p>
                        <a href='seller_dashboard.html' class='dashboard-link'>Go to Seller Dashboard →</a>
                    </div>
                  </div>";
        } else {
            echo "<div class='success-container'>
                    <div class='success-message'>
                        <h2>Login Successful!</h2>
                        <p>Welcome back! You are now redirected to your customer dashboard.</p>
                        <a href='customer_dashboard.html' class='dashboard-link'>Go to Customer Dashboard →</a>
                    </div>
                  </div>";
        }
    } else {
        echo "<div class='error-container'>
                <div class='error-message'>
                    <h2>❌Invalid Credentials!</h2>
                    <p>Please check your email and password and try again.</p>
                    <a href='login.html' class='retry-link'>Try Again</a>
                </div>
              </div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
       body {
    font-family: 'Poppins', sans-serif;
    background: url('https://images.unsplash.com/photo-1533630217389-3a5e4dff5683?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
    background-size: cover;
    color: white;
    text-align: center;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    animation: fadeIn 1s ease-in-out;
    padding: 20px;
        }

        .success-container, .error-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
        }

        .success-message, .error-message {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 22px;
            width: 50%;
            max-width: 600px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.4);
            animation: popUp 0.8s ease-in-out;
        }

        .success-message h2 {
            font-size: 32px;
            margin-bottom: 15px;
            animation: glowText 1.5s infinite alternate;
        }

        .success-message p {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .success-message h2 {
            color: #c82333; /* Green for success */
        }

        .success-message.seller h2 {
            color: #c82333; /* Orange for seller */
        }

        .success-message.customer h2 {
            color: #c82333; /* Blue for customer */
        }

        .error-message h2 {
            color: #dc3545; /* Red for errors */
        }

        .dashboard-link, .retry-link {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s ease-in-out;
            border: 2px solid white;
        }

        .dashboard-link {
            background: linear-gradient(135deg, #333, #c82333);
            color: white;
        }

        .dashboard-link.seller {
            background: linear-gradient(135deg, #ff9800, #e65100);
        }

        .dashboard-link.customer {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .retry-link {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .dashboard-link:hover, .retry-link:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.5);
        }

        @keyframes popUp {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes backgroundShift {
            from { background: linear-gradient(135deg, #667eea, #764ba2); }
            to { background: linear-gradient(135deg, #764ba2, #667eea); }
        }
    </style>
</head>
<body>
</body>
</html>