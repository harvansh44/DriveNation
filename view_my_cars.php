<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if user is logged in and is a seller
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "seller") {
    die("You must be logged in as a seller to view your cars. <a href='login.html'>Login here</a>");
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM cars WHERE user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cars</title>
    <style>
        /* Global Styles */
        :root {
            --primary-bg:rgb(46, 46, 46);
            --secondary-bg: #262424;
            --card-bg: #C9C8B9;
            --primary-button: #007bff;
            --primary-button-hover: #0069d9;
            --edit-button: #28a745;
            --edit-button-hover: #218838;
            --delete-button: #dc3545;
            --delete-button-hover: #c82333;
            --border-radius: 10px;
            --box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            --text-light: #ffffff;
            --text-dark: #333333;
            --border-color: #dddddd;
            --accent-color: #007bff;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: var(--primary-bg);
            background-image: linear-gradient(to bottom right, var(--primary-bg), var(--secondary-bg));
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
        }
        
        /* Header Section */
        .header-container {
            position: relative;
            text-align: center;
            padding: 30px 0;
            margin-bottom: 20px;
            background-color: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        h2 {
            text-align: center;
            font-size: 32px;
            font-weight: 600;
            color: var(--text-light);
            margin: 0;
            letter-spacing: 1.5px;
            display: inline-block;
            position: relative;
            /* Removed the h2:after pseudo-element */
        }
        
        .dashboard-link {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        /* Car Grid Layout */
        .car-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }
        
        /* Car Card Design - Updated to match image */
        .car-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            text-align: center;
            background: var(--card-bg);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        
        .car-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .car-card h3 {
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--text-dark);
            font-size: 18px;
        }
        
        .car-card p {
            font-weight: 500;
            color: var(--text-dark);
            margin: 8px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        /* Image Carousel */
        .carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }
        
        .carousel-images {
            display: flex;
            width: 100%;
            transition: transform 0.5s ease-in-out;
            aspect-ratio: 16/10;
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .carousel-images img {
            width: 100%;
            height: 100%;
            display: none;
            object-fit: cover;
        }
        
        .carousel-images img.active {
            display: block;
        }
        
        .carousel button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
            z-index: 2;
        }
        
        .carousel button:hover {
            background: rgba(0, 0, 0, 0.7);
        }
        
        .prev {
            left: 10px;
        }
        
        .next {
            right: 10px;
        }
        
        /* Buttons - Updated to match image */
        .car-button {
            display: inline-block;
            width: 100%;
            padding: 12px 20px;
            background-color: var(--primary-button);
            color: var(--text-light);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-align: center;
            margin-top: 15px;
        }
        
        .car-button:hover {
            background-color: var(--primary-button-hover);
            transform: translateY(-2px);
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
        }
        
        .edit-button {
            flex: 1;
            display: inline-block;
            padding: 10px 0;
            background-color: var(--edit-button);
            color: var(--text-light);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .edit-button:hover {
            background-color: var(--edit-button-hover);
            transform: translateY(-2px);
        }
        
        .delete-button {
            flex: 1;
            display: inline-block;
            padding: 10px 0;
            background-color: var(--delete-button);
            color: var(--text-light);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .delete-button:hover {
            background-color: var(--delete-button-hover);
            transform: translateY(-2px);
        }
        
        /* No Cars Available Message */
        .no-cars-message {
            color: var(--text-light);
            text-align: center;
            width: 100%;
            font-weight: 600;
            font-size: 18px;
            padding: 40px 0;
            grid-column: 1 / -1;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
        }
        
        /* Icons */
        .icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 5px;
        }
        
        /* Mark Sold Button */
        .mark-sold-button {
            flex: 1;
            display: inline-block;
            padding: 10px 0;
            background-color: #FF9800;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
        }

        .mark-sold-button:hover {
            background-color: #F57C00;
            transform: translateY(-2px);
        }

        .mark-available-button {
            flex: 1;
            display: inline-block;
            padding: 10px 0;
            background-color: #4CAF50;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
        }

        .mark-available-button:hover {
            background-color: #388E3C;
            transform: translateY(-2px);
        }
        
        /* Updated styles to match the image */
        .header-container {
            background-color: var(--primary-bg);
            border-bottom: none; /* Removed border */
            padding: 20px 0;
        }
        
        .dashboard-link .car-button {
            padding: 10px 15px;
            border-radius: 4px;
        }
        
        .car-info {
            background-color: #e6e5d8;
            padding: 15px;
        }
        
        .icon-location {
            color: #ff4081;
        }
        
        .icon-mileage {
            color: #2196F3;
        }
        .footer {
            width: 100%;
            background: #333;
            color: #fff;
            padding: 40px 0;
            margin-top: 80px;
            text-align: center;
        }

        .footer-content {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer h3 {
            font-size: 24px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .footer h3::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: #ffdd57;
        }

        .footer p {
            color: #ccc;
            font-size: 18px;
            margin: 20px 0;
        }

        .footer .email {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .footer .email-icon {
            margin-right: 10px;
            font-size: 24px;
        }

        .footer .contact-email {
            color: #ffdd57;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer .contact-email:hover {
            color: #fff;
        }

        .footer .copyright {
            margin-top: 30px;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="container">
            <h2>MY CARS</h2>
            <div class="dashboard-link">
                <a href="seller_dashboard.html" class="car-button">Back to Dashboard</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="car-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='car-card'>";
                
                // Fetch multiple images for this car
                $car_id = $row["id"];
                $img_sql = "SELECT image_path FROM car_images WHERE car_id = $car_id";
                $img_result = $conn->query($img_sql);
                
                if ($img_result->num_rows > 0) {
                    echo "<div class='carousel' id='carousel-$car_id'>";
                    echo "<div class='carousel-images'>";
                    $i = 0;
                    while ($img_row = $img_result->fetch_assoc()) {
                        $activeClass = ($i == 0) ? 'active' : '';
                        echo "<img src='" . $img_row["image_path"] . "' class='slide-$i $activeClass' data-index='$i'>";
                        $i++;
                    }
                    echo "</div>";
                    echo "<button class='prev' onclick='prevSlide($car_id)'>&#10094;</button>";
                    echo "<button class='next' onclick='nextSlide($car_id)'>&#10095;</button>";
                    echo "</div>";
                } else {
                    echo "<div class='carousel'><div class='carousel-images'><p style='padding: 40px 0; width: 100%; text-align: center;'>No images available</p></div></div>";
                }
                
                echo "<div class='car-info'>";
                echo "<h3>" . $row["make"] . " " . $row["model"] . " (" . $row["year"] . ")</h3>";
                echo "<p><span class='icon icon-mileage'>🛣️</span> " . number_format($row["kms_driven"]) . " km</p>";
                echo "<p><span class='icon icon-location'>📍</span> " . $row["current_location"] . "</p>";
                
                echo "<div class='action-buttons'>";
                echo "<a href='edit_car.php?car_id=" . $row["id"] . "' class='edit-button'>Edit</a>";
                echo "<a href='delete_car.php?car_id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this car?\");' class='delete-button'>Delete</a>";
                echo "</div>";
                
                echo "<a href='car_details.php?car_id=" . $row["id"] . "' class='car-button'>View Details</a>";
                
                // Mark as Sold/Available button
                echo "<div class='action-buttons' style='margin-top: 10px;'>";
                if ($row["status"] == "sold") {
                    echo "<a href='toggle_sold_status.php?car_id=" . $row["id"] . "&status=available' class='mark-available-button'>Mark as Available</a>";
                } else {
                    echo "<a href='toggle_sold_status.php?car_id=" . $row["id"] . "&status=sold' class='mark-sold-button'>Mark as Sold</a>";
                }
                echo "</div>";
                
                echo "</div>"; // Close car-info
                echo "</div>"; // Close car-card
            }
        } else {
            echo "<div class='no-cars-message'>You haven't added any cars yet.</div>";
        }
        ?>
        </div>
    </div>

    <script>
        function nextSlide(carId) {
            let carousel = document.querySelector(`#carousel-${carId} .carousel-images`);
            let images = carousel.querySelectorAll("img");
            let activeIndex = [...images].findIndex(img => img.classList.contains("active"));
            images[activeIndex].classList.remove("active");
            let nextIndex = (activeIndex + 1) % images.length;
            images[nextIndex].classList.add("active");
        }

        function prevSlide(carId) {
            let carousel = document.querySelector(`#carousel-${carId} .carousel-images`);
            let images = carousel.querySelectorAll("img");
            let activeIndex = [...images].findIndex(img => img.classList.contains("active"));
            images[activeIndex].classList.remove("active");
            let prevIndex = (activeIndex - 1 + images.length) % images.length;
            images[prevIndex].classList.add("active");
        }
    </script>
    <footer class="footer">
        <div class="footer-content">
            <h3>Contact Us</h3>
            <div class="email">
                <span class="email-icon">✉️</span>
                <a href="mailto:userhelpdrivenation@gmail.com" class="contact-email">userhelpdrivenation@gmail.com</a>
            </div>
            <p>We're here to help you find your perfect vehicle.</p>
            <div class="copyright">
                &copy; 2025 DriveNation. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>