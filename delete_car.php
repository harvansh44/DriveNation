<?php
include 'db_connect.php'; // Include database connection

session_start(); // Start session to get logged-in user ID

// Check if user is logged in and is a seller
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "seller") {
    die("You must be logged in as a seller to delete a car. <a href='login.html'>Login here</a>");
}

$user_id = $_SESSION["user_id"]; // Get the logged-in user's ID

?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Car</title>
    <style>
        body {
					font-family: 'Poppins', sans-serif;
					background: url('https://wallpaperaccess.com/full/9254213.jpg') no-repeat center center fixed;
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
				
				}
				</style>
				<div class='success-container'>
                <div class='success-message'>
                    <h2>Car Deleted Successfully!</h2>
                    <p>Your car has been deleted successfully.</p>
                    <a href='view_my_cars.php' class='dashboard-link'>View My Cars →</a>
                </div>
              </div>";
    </style>
</head>
<body>

<?php
if (isset($_GET["car_id"])) {
    $car_id = $_GET["car_id"];

    // Ensure the car belongs to the current user
    $sql = "DELETE FROM cars WHERE id = $car_id AND user_id = $user_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Car deleted successfully! <a href='view_my_cars.php'>Go Back</a></p>";
    } else {
        echo "<p class='error'>Error deleting car: " . $conn->error . "</p>";
    }
}
?>

</body>
</html>