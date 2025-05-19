<?php
// Start the session and include database connection
session_start();
include 'db_connect.php';

// Check if user is admin
if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] != "admin") {
    die("Access denied. Admins only. <a href='admin_login.html'>Login here</a>");
}

// Check if report_id is provided
if (!isset($_GET['report_id'])) {
    die("Report ID not provided. <a href='view_car_reports.php'>Back to Reports</a>");
}

$report_id = $_GET['report_id'];

// Fetch report details
$report_sql = "SELECT * FROM car_reports WHERE report_id = ?";
$stmt = $conn->prepare($report_sql);
$stmt->bind_param("i", $report_id);
$stmt->execute();
$report_result = $stmt->get_result();

if ($report_result->num_rows == 0) {
    die("Report not found. <a href='view_car_reports.php'>Back to Reports</a>");
}

$report = $report_result->fetch_assoc();

// Fetch images associated with this report
$images_sql = "SELECT * FROM car_report_images WHERE report_id = ?";
$stmt = $conn->prepare($images_sql);
$stmt->bind_param("i", $report_id);
$stmt->execute();
$images_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Report Details</title>
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

        .report-card {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 30px;
        }

        .report-card h3 {
            font-size: 28px;
            margin-bottom: 25px;
            font-weight: 600;
            color: #f39c12;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .car-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            margin-bottom: 15px;
            padding: 12px;
            background: rgba(243, 156, 18, 0.2);
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-label {
            font-size: 16px;
            font-weight: 500;
        }

        .info-value {
            font-size: 18px;
            font-weight: 600;
            color: #f39c12;
        }

        .car-images {
            margin-top: 30px;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .car-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid rgba(243, 156, 18, 0.3);
            transition: transform 0.3s, border-color 0.3s;
        }

        .car-image:hover {
            transform: scale(1.05);
            border-color: #f39c12;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 32px;
            }
            
            .car-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Car Report Details</h2>
        </div>
        
        <a href="view_car_reports.php" class="back-button">Back to Reports</a>
        
        <div class="report-card">
            <h3><?php echo $report['make'] . ' ' . $report['model'] . ' (' . $report['year'] . ')'; ?></h3>
            
            <div class="car-info">
                <div class="info-item">
                    <span class="info-label">Report ID:</span>
                    <span class="info-value"><?php echo $report['report_id']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Car ID:</span>
                    <span class="info-value"><?php echo $report['car_id']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Seller:</span>
                    <span class="info-value"><?php echo $report['username']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo $report['email']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Make:</span>
                    <span class="info-value"><?php echo $report['make']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Model:</span>
                    <span class="info-value"><?php echo $report['model']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Year:</span>
                    <span class="info-value"><?php echo $report['year']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Price:</span>
                    <span class="info-value">₹<?php echo number_format($report['price'], 2); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Kilometers Driven:</span>
                    <span class="info-value"><?php echo number_format($report['kms_driven']); ?> km</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Mobile Number:</span>
                    <span class="info-value"><?php echo $report['mobile_no']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Registration State:</span>
                    <span class="info-value"><?php echo $report['registration_state']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Current Location:</span>
                    <span class="info-value"><?php echo $report['current_location']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Report Date:</span>
                    <span class="info-value"><?php echo date('F d, Y H:i:s', strtotime($report['report_date'])); ?></span>
                </div>
            </div>
            
            <div class="car-images">
                <h3>Car Images</h3>
                <div class="image-gallery">
                    <?php
                    if ($images_result->num_rows > 0) {
                        while ($image = $images_result->fetch_assoc()) {
                            echo "<img src='" . $image['image_path'] . "' alt='Car Image' class='car-image'>";
                        }
                    } else {
                        echo "<p>No images available for this car.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>