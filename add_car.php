<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if the user is logged in and is a seller
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'seller') {
    die("You must be logged in as a seller to add a car. <a href='login.html'>Login here</a>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $make = $_POST["make"];
    $model = $_POST["model"];
    $year = $_POST["year"];
    $price = $_POST["price"];
    $kms_driven = $_POST["kms_driven"];
    $mobile_no = $_POST["mobile_no"];
    $registration_state = $_POST["registration_state"];
    $current_location = $_POST["current_location"];
    $user_id = $_SESSION["user_id"];
    
    // Insert car details into the database
    // In the SQL query in add_car.php
    $sql = "INSERT INTO cars (user_id, make, model, year, price, kms_driven, mobile_no, registration_state, current_location, status) 
    VALUES ('$user_id', '$make', '$model', '$year', '$price', '$kms_driven', '$mobile_no', '$registration_state', '$current_location', 'available')";
    
    if ($conn->query($sql) === TRUE) {
        $car_id = $conn->insert_id;
        $image_paths = array(); // To store image paths for reports
        
        // Handle image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $target_dir = "uploads/";
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['images']['name'][$key]);
                $target_file = $target_dir . time() . "_" . $file_name;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $conn->query("INSERT INTO car_images (car_id, image_path) VALUES ('$car_id', '$target_file')");
                    $image_paths[] = $target_file; // Store path for reporting
                }
            }
        }
        
        // Get seller's username and email for reporting
        $user_query = "SELECT username, email FROM users WHERE id = '$user_id'";
        $user_result = $conn->query($user_query);
        
        if ($user_result && $user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();
            $username = $user_data['username'];
            $email = $user_data['email'];
            // Get car's current status
            $status_sql = "SELECT status FROM cars WHERE id = $car_id";
            $status_result = $conn->query($status_sql);
            $status_row = $status_result->fetch_assoc();
            $car_status = $status_row['status'];
            // Insert into car_reports table
            $report_sql = "INSERT INTO car_reports (car_id, user_id, username, email, make, model, year, price, kms_driven, mobile_no, registration_state, current_location,status) 
                           VALUES ('$car_id', '$user_id', '$username', '$email', '$make', '$model', '$year','$price', '$kms_driven', '$mobile_no', '$registration_state', '$current_location', '$car_status')";
            
            if ($conn->query($report_sql) === TRUE) {
                $report_id = $conn->insert_id;
                
                // Insert image paths into car_report_images
                foreach ($image_paths as $image_path) {
                    $img_report_sql = "INSERT INTO car_report_images (report_id, image_path) 
                                       VALUES ('$report_id', '$image_path')";
                    $conn->query($img_report_sql);
                }
            }
        }
        
        echo "<style>
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
                    <h2>Car Added Successfully!</h2>
                    <p>Your car has been listed successfully.</p>
                    <a href='view_my_cars.php' class='dashboard-link'>View My Cars →</a>
                </div>
              </div>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>