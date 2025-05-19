<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "admin") {
    die("You must be an admin to manage users. <a href='login.html'>Login here</a>");
}

$sql = "SELECT * FROM users WHERE user_type != 'admin'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://wallpapershome.com/images/pages/pic_h/26412.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        h2 {
            font-size: 42px;
            margin-bottom: 10px;
            font-weight: 700;
            text-transform: uppercase;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
            letter-spacing: 1px;
        }

        .welcome-text {
            font-size: 18px;
            margin-bottom: 30px;
            font-weight: 500;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6);
        }

        nav {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            margin-bottom: 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            width: 100%;
            transition: all 0.3s ease;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            transition: all 0.3s;
            padding: 12px 20px;
            border-radius: 30px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            display: inline-block;
        }

        nav ul li a:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: rgba(243, 156, 18, 0.7);
            transition: all 0.3s;
            z-index: -1;
            border-radius: 30px;
        }

        nav ul li a:hover {
            color: white;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        nav ul li a:hover:before {
            width: 100%;
        }

        .user-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            width: 100%;
        }

        .user-card {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            text-align: center;
            transition: all 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .user-card h3 {
            font-size: 28px;
            margin-bottom: 25px;
            font-weight: 600;
            color: #f39c12;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .user-info {
            margin-bottom: 15px;
            padding: 12px;
            background: rgba(243, 156, 18, 0.2);
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-label {
            font-size: 16px;
            font-weight: 500;
        }

        .user-value {
            font-size: 18px;
            font-weight: 600;
            color: #f39c12;
        }

        .user-button {
            margin: 10px 5px;
            display: inline-block;
            padding: 12px 20px;
            background: #f39c12;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .user-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .delete-button {
            background: #e74c3c;
        }

        .delete-button:hover {
            background: #c0392b;
        }

        .back-button {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            transition: all 0.3s;
            padding: 12px 20px;
            border-radius: 30px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            display: inline-block;
            margin-bottom: 20px;
            background: rgba(0, 0, 0, 0.5);
        }

        .back-button:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: rgba(243, 156, 18, 0.7);
            transition: all 0.3s;
            z-index: -1;
            border-radius: 30px;
        }

        .back-button:hover {
            color: white;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        .back-button:hover:before {
            width: 100%;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 32px;
            }
            
            .user-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Manage Users</h2>
        </div>
        
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
        
        <div class="user-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='user-card'>";
                echo "<h3>" . $row["username"] . "</h3>";
                
                echo "<div class='user-info'>";
                echo "<span class='user-label'>Email:</span>";
                echo "<span class='user-value'>" . $row["email"] . "</span>";
                echo "</div>";
                
                echo "<div class='user-info'>";
                echo "<span class='user-label'>Role:</span>";
                echo "<span class='user-value'>" . ucfirst($row["user_type"]) . "</span>";
                echo "</div>";
                
                echo "<a href='admin_edit_user.php?user_id=" . $row["id"] . "' class='user-button'>Edit User</a>";
                echo "<a href='admin_delete_user.php?user_id=" . $row["id"] . "' class='user-button delete-button' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete User</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No users available.</p>";
        }
        ?>
        </div>
    </div>
</body>
</html>