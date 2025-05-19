<?php
include 'db_connect.php'; // Include database connection
session_start();

$sql = "SELECT cars.*, users.username as seller_name FROM cars JOIN users ON cars.user_id = users.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cars</title>
    <style>
        /* Global Styles */
        :root {
            --primary-bg: #262424;
            --secondary-bg: #262424;
            --card-bg: #C9C8B9;
            --primary-button: #007bff;
            --primary-button-hover: #0069d9;
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
        }
        
        h2:after {
            content: '';
            position: absolute;
            width: 60px;
            height: 3px;
            background-color: var(--accent-color);
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
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
            position: relative;
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
            text-align: center;
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
        
        /* Image Carousel - Updated styling */
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
            background-color: #FFFFFF;
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
            margin-top: auto;
        }
        
        .car-button:hover {
            background-color: var(--primary-button-hover);
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
        
        /* Icons - Updated to match image */
        .icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 5px;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        
        /* Add this to the style section */
        .sold-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(220, 53, 69, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            z-index: 3;
        }

        .car-card {
            position: relative;
            /* existing styles */
        }
        /* Add this to the style section */
        .car-card.sold {
            opacity: 0.8;
        }

        .car-card.sold::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            pointer-events: none;
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
            <h2>AVAILABLE CARS</h2>
            <div class="button-container" style="text-align: right; margin-bottom: 20px;">  
            <?php
                $dashboard = "login.html"; // Default to login if session is not set
                if (isset($_SESSION['user_type'])) {
                    if ($_SESSION['user_type'] == "customer") {
                        $dashboard = "customer_dashboard.html";
                    } elseif ($_SESSION['user_type'] == "seller") {
                        $dashboard = "seller_dashboard.html";
                    }
                }
            ?>
            <a href="<?php echo $dashboard; ?>" class="back-button"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

            </div>  
        </div>
    </div>
    
    <div class="container">
        <div class="car-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cardClass = ($row["status"] == "sold") ? "car-card sold" : "car-card";
                echo "<div class='$cardClass'>";
                
                if ($row["status"] == "sold") {
                    echo "<div class='sold-indicator'>SOLD</div>";
                }
                
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
                echo "<p><span class='icon'>🛣️</span> " . number_format($row["kms_driven"]) . " km</p>";
                echo "<p><span class='icon'>📍</span> " . $row["current_location"] . "</p>";
                echo "<a href='car_details.php?car_id=" . $row["id"] . "' class='car-button'>View Details</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='no-cars-message'>No cars available.</div>";
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
