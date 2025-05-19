<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if user is logged in and is a seller
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "seller") {
    die("You must be logged in as a seller to update car status. <a href='login.html'>Login here</a>");
}

if (isset($_GET['car_id']) && isset($_GET['status'])) {
    $car_id = $_GET['car_id'];
    $status = $_GET['status'];
    $user_id = $_SESSION['user_id'];
    
    // Verify the car belongs to the current seller
    $check_sql = "SELECT * FROM cars WHERE id = $car_id AND user_id = $user_id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        // Update the car status
        $update_sql = "UPDATE cars SET status = '$status' WHERE id = $car_id";
        
        if ($conn->query($update_sql) === TRUE) {
            // Also update any report for this car
            $update_report_sql = "UPDATE car_reports SET status = '$status' WHERE car_id = $car_id";
            $conn->query($update_report_sql);
            // Redirect back to the my cars page
            header("Location: view_my_cars.php");
            exit();
        } else {
            echo "Error updating car status: " . $conn->error;
        }
    } else {
        echo "You don't have permission to update this car's status.";
    }
} else {
    echo "Invalid request.";
}
?>