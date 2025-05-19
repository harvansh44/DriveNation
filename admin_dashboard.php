<?php
// Updated check at the top of all admin pages
session_start();
include 'db_connect.php';

// New admin authentication check
if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] != "admin") {
    die("Access denied. Admins only. <a href='admin_login.html'>Login here</a>");
}

// Rest of your admin dashboard code remains the same
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            width: 100%;
        }

        .overview-card {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            text-align: center;
            transition: all 0.3s ease;
        }

        .overview-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .overview-card h3 {
            font-size: 28px;
            margin-bottom: 25px;
            font-weight: 600;
            color: #f39c12;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .stat-item {
            margin-bottom: 15px;
            padding: 12px;
            background: rgba(243, 156, 18, 0.2);
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label {
            font-size: 16px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #f39c12;
        }
        
        .access-denied {
            font-family: 'Poppins', sans-serif;
            background-color: rgba(0, 0, 0, 0.7);
            color: #ff5252;
            text-align: center;
            padding: 25px;
            border: 2px solid #ff5252;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .access-denied a {
            color: #5d9cec;
            text-decoration: none;
            font-weight: 600;
            margin-left: 10px;
            padding: 5px 15px;
            background: rgba(93, 156, 236, 0.2);
            border-radius: 20px;
            transition: all 0.3s;
        }
        
        .access-denied a:hover {
            background: rgba(93, 156, 236, 0.4);
            box-shadow: 0 0 10px rgba(93, 156, 236, 0.5);
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 32px;
            }
            
            nav ul {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            
            nav ul li a {
                display: block;
                width: 200px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Admin Dashboard</h2>
            <p class="welcome-text">Welcome, <?php echo $_SESSION["username"]; ?>!</p>
        </div>
        
        <nav>
            <ul>
                <li><a href="admin_manage_cars.php">Manage Cars</a></li>
                <li><a href="admin_manage_users.php">Manage Users</a></li>
                <li><a href="view_car_reports.php">Car Reports</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <div class="dashboard-container">
            <div class="overview-card">
                <h3>System Overview</h3>
                <?php
                // Count users
                $user_sql = "SELECT COUNT(*) as total_users FROM users";
                $user_result = $conn->query($user_sql);
                $user_row = $user_result->fetch_assoc();
                $total_users = $user_row['total_users'];
                
                // Count cars
                $car_sql = "SELECT COUNT(*) as total_cars FROM cars";
                $car_result = $conn->query($car_sql);
                $car_row = $car_result->fetch_assoc();
                $total_cars = $car_row['total_cars'];
                ?>
                
                <div class="stat-item">
                    <span class="stat-label">Total Users</span>
                    <span class="stat-value"><?php echo $total_users; ?></span>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">Total Cars Listed</span>
                    <span class="stat-value"><?php echo $total_cars; ?></span>
                </div>
                
                <?php $conn->close(); ?>
            </div>
        </div>
    </div>
</body>
</html>