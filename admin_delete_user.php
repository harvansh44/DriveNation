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
    <title>Delete User</title>
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
				
				}
					</style>
					<div class='success-container'>
					<div class='success-message'>
                    <h2>User Deleted Successfully!</h2>
                    <p>User and all their cars deleted successfully!</p>
                    <a href='admin_dashboard.php' class='dashboard-link'>Admin Dashboard →</a>
                </div>
              </div>
    </style>
</head>
<body>

<?php
if (isset($_GET["user_id"])) {
    $user_id = $_GET["user_id"];
    
    // Prevent admin from deleting themselves
    if ($user_id == $_SESSION["user_id"]) {
        die("You cannot delete your own admin account! <a href='admin_manage_users.php'>Back</a>");
    }

    // Delete the user
    $sql = "DELETE FROM users WHERE id = $user_id";
    
    if ($conn->query($sql) === TRUE) {
        // Also delete all cars associated with this user
        $car_sql = "DELETE FROM cars WHERE user_id = $user_id";
        $conn->query($car_sql);
        
        echo "User and all their cars deleted successfully! <a href='admin_manage_users.php'>Back to Manage Users</a>";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>

</body>
</html>