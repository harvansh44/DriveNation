<?php
include 'db_connect.php';
session_start();

// Check if user is admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "admin") {
    die("Access denied. Admins only. <a href='login.html'>Login here</a>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Car</title>
    <style>
        body {
					font-family: 'Poppins', sans-serif;
					background: url('https://wallpaperaccess.com/full/3311394.jpg') no-repeat center center fixed;
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
                .dashboard-link {
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
                .dashboard-link:hover, .retry-link:hover {
					transform: scale(1.1);
					box-shadow: 0 5px 15px rgba(255, 255, 255, 0.5);
				}
				
				
				</style>
				<div class='success-container'>
                <div class='success-message'>
                    <h2>Car Deleted Successfully!</h2>
                    <p>Your car has been deleted successfully.</p>
                    <a href='admin_dashboard.php' class='dashboard-link'>Admin Dashboard →</a>
                </div>
              </div>
    </style>
</head>
<body>

<?php
if (isset($_GET["car_id"])){
    $car_id = $_GET["car_id"];

    // Delete the car
    $sql = "DELETE FROM cars WHERE id = $car_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Car deleted successfully! <a href='admin_manage_car.php'>Back to Manage All Cars</a>";
    } else {
        echo "Error deleting car: " . $conn->error;
    }
	} else {
    echo "Invalid request.";
}

$conn->close();
?>
</body>
</html>