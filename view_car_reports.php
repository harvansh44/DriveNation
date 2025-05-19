<?php
// Start the session and include database connection
session_start();
include 'db_connect.php';

// Check if user is admin
if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] != "admin") {
    die("Access denied. Admins only. <a href='admin_login.html'>Login here</a>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Reports</title>
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

        .reports-card {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 30px;
        }

        .reports-card h3 {
            font-size: 28px;
            margin-bottom: 25px;
            font-weight: 600;
            color: #f39c12;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: rgba(243, 156, 18, 0.2);
            color: #f39c12;
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .view-details {
            display: inline-block;
            padding: 8px 15px;
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

        .view-details:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 32px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Car Reports</h2>
        </div>
        
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
        
        <div class="reports-card">
            <h3>Historical Car Listing Reports</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Car Make & Model</th>
                        <th>Seller</th>
                        <th>Price</th>
                        <th>Report Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all car reports with seller information
                    $sql = "SELECT * FROM car_reports ORDER BY report_date DESC";
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['report_id'] . "</td>";
                            echo "<td>" . $row['make'] . " " . $row['model'] . " (" . $row['year'] . ")</td>";
                            echo "<td>" . $row['username'] . " (" . $row['email'] . ")</td>";
                            echo "<td>₹" . number_format($row['price'], 2) . "</td>";
                            echo "<td>" . date('M d, Y', strtotime($row['report_date'])) . "</td>";
                            echo "<td><a href='view_car_report_details.php?report_id=" . $row['report_id'] . "' class='view-details'>View Details</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No car reports found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>