<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "admin") {
    die("You must be an admin to manage cars. <a href='login.html'>Login here</a>");
}

$sql = "SELECT cars.*, users.username as seller_name FROM cars JOIN users ON cars.user_id = users.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cars</title>
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

        nav ul li a, .back-button {
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

        nav ul li a:before, .back-button:before {
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

        nav ul li a:hover, .back-button:hover {
            color: white;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        nav ul li a:hover:before, .back-button:hover:before {
            width: 100%;
        }

        .car-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            width: 100%;
        }

        .car-card {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            text-align: center;
            transition: all 0.3s ease;
            width: 100%;
            border: none;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .car-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
            color: #f39c12;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .car-card p {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 10px;
            margin: 15px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .carousel-images {
            display: flex;
            width: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-images img {
            width: 100%;
            display: none;
            border-radius: 10px;
            border: none;
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
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 50%;
            font-size: 18px;
            z-index: 2;
            transition: all 0.3s;
        }

        .carousel button:hover {
            background: rgba(243, 156, 18, 0.7);
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .car-button {
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

        .car-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .delete-button {
            background: #e74c3c;
        }

        .delete-button:hover {
            background: #c0392b;
        }

        .edit-button {
            background: #3498db;
        }

        .edit-button:hover {
            background: #2980b9;
        }

        .back-button {
            margin-bottom: 20px;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            display: inline-block;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 32px;
            }
            
            .car-container {
                grid-template-columns: 1fr;
            }
            
            .car-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Manage Cars</h2>
        </div>
        
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
        
        <div class="car-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='car-card'>";
                echo "<h3>" . $row["make"] . " " . $row["model"] . " (" . $row["year"] . ")</h3>";
                echo "<p>Price: $" . $row["price"] . "</p>";
                echo "<p>KMs Driven: " . $row["kms_driven"] . " km</p>";
                echo "<p>Seller: " . $row["seller_name"] . "</p>";
                echo "<p>Registration State: " . $row["registration_state"] . "</p>";
                echo "<p>Current Location: " . $row["current_location"] . "</p>";
                
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
                    echo "<p>No images available.</p>";
                }
                
                echo "<a href='admin_edit_car.php?car_id=" . $row["id"] . "' class='car-button edit-button'>Edit Car</a>";
                echo "<a href='admin_delete_car.php?car_id=" . $row["id"] . "' class='car-button delete-button' onclick='return confirm(\"Are you sure you want to delete this car?\");'>Delete Car</a>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-cars'>No cars available.</p>";
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
</body>
</html>