<?php
include 'db_connect.php'; // Include database connection
session_start();

if (!isset($_GET['car_id'])) {
    die("Car not found.");
}

$car_id = $_GET['car_id'];
$sql = "SELECT cars.*, users.username as seller_name FROM cars JOIN users ON cars.user_id = users.id WHERE cars.id = $car_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Car not found.");
}

$car = $result->fetch_assoc();

$img_sql = "SELECT image_path FROM car_images WHERE car_id = $car_id";
$img_result = $conn->query($img_sql);
$images = [];
while ($img_row = $img_result->fetch_assoc()) {
    $images[] = $img_row['image_path'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $car['make'] . ' ' . $car['model']; ?> - Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Barlow Condensed', sans-serif;
        }
        
        body {
            background: #262424 ;
            color: white;
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #d3cec9;
            font-size: 42px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        strong {
            font-size: 44px;
        }
        
        .carousel-container {
            position: relative;
            width: 100%;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        .carousel-images {
            width: 100%;
            height: 100%;
        }
        
        .carousel-images img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .carousel-images img.active {
            opacity: 1;
        }
        
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 44px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        
        .carousel-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: translateY(-50%) scale(1.1);
        }
        
        .prev {
            left: 20px;
        }
        
        .next {
            right: 20px;
        }
        
        .details-card {
            background: #d3cec9;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .details-card h3 {
            color: black;
            font-size: 38px;
            margin-bottom: 20px;
            text-transform: uppercase;
            border-bottom: 2px solid #5A4C42;
            padding-bottom: 10px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .detail-item {
            margin-bottom: 15px;
        }
        
        .detail-item strong {
            display: block;
            color: #262424;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-weight: bold;
            
        }
        
        .detail-item span {
            font-size: 24px;
            color: #262424;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 25px;
            background: linear-gradient(90deg, rgb(26, 18, 20), rgb(12, 10, 11));
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            text-decoration: none;
            text-align: center;
        }
        
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        @media (max-width: 768px) {
            .carousel-container {
                height: 350px;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
            }
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
    <div class="container">
        <header>
            <h2><?php echo $car['make'] . ' ' . $car['model']; ?></h2>
        </header>
        
        <div class="carousel-container">
            <div class="carousel-images">
                <?php foreach ($images as $index => $image) {
                    $activeClass = $index === 0 ? 'active' : '';
                    echo "<img src='$image' class='$activeClass'>";
                } ?>
            </div>
            <button class="carousel-btn prev" onclick="prevSlide()">&#10094;</button>
            <button class="carousel-btn next" onclick="nextSlide()">&#10095;</button>
        </div>
        
        <div class="details-card">
            <h3>Car Details</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <strong>Price</strong>
                    <span>Inr <?php echo number_format($car['price']); ?></span>
                </div>
                <div class="detail-item">
                    <strong>KM Driven</strong>
                    <span><?php echo number_format($car['kms_driven']); ?> km</span>
                </div>
                <div class="detail-item">
                    <strong>Seller</strong>
                    <span><?php echo $car['seller_name']; ?></span>
                </div>
                <div class="detail-item">
                    <strong>Contact</strong>
                    <span><?php echo $car['mobile_no']; ?></span>
                </div>
                <div class="detail-item">
                    <strong>Registration State</strong>
                    <span><?php echo $car['registration_state']; ?></span>
                </div>
                <div class="detail-item">
                    <strong>Current Location</strong>
                    <span><?php echo $car['current_location']; ?></span>
                </div>
            </div>  
        </div>
        
        <div class="button-group">
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
<div class="button-group">
    <a href="view_cars.php" class="btn">Back to Cars</a>
    <a href="<?php echo $dashboard; ?>" class="btn">Back to Dashboard</a>
</div>

        </div>
    </div>

    <script>
        let currentIndex = 0;
        const images = document.querySelectorAll('.carousel-images img');
        
        function showSlide(index) {
            images.forEach(img => img.classList.remove('active'));
            images[index].classList.add('active');
        }
        
        function nextSlide() {
            currentIndex = (currentIndex + 1) % images.length;
            showSlide(currentIndex);
        }
        
        function prevSlide() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showSlide(currentIndex);
        }
        
        // Auto-rotate slides
        setInterval(nextSlide, 9000);
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