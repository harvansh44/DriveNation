<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "admin") {
    die("You must be an admin to edit cars. <a href='login.html'>Login here</a>");
}

// If form is submitted, update car details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = $_POST['car_id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $kms_driven = $_POST['kms_driven'];
    $registration_state = $_POST['registration_state'];
    $current_location = $_POST['current_location'];
    
    $update_sql = "UPDATE cars SET make=?, model=?, year=?, price=?, kms_driven=?, registration_state=?, current_location=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssiisssi", $make, $model, $year, $price, $kms_driven, $registration_state, $current_location, $car_id);
    
    if ($stmt->execute()) {
        header("Location: admin_manage_cars.php?success=Car updated successfully");
        exit();
    } else {
        echo "<p style='color:red;'>Error updating car: " . $conn->error . "</p>";
    }
}

if (!isset($_GET['car_id'])) {
    die("Car ID not provided.");
}

$car_id = $_GET['car_id'];
$sql = "SELECT * FROM cars WHERE id = $car_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Car not found.");
}

$car = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: white;
            text-align: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            animation: fadeIn 1s ease-in-out;
            padding: 20px;
            background-color: #2c2c2c;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 25px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
            width: 90%;
            max-width: 500px;
            margin: auto;
            animation: fadeIn 1.5s ease-in-out;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-group {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 20px;
            padding: 10px 0;
        }
        label {
            font-size: 18px;
            width: 35%;
            text-align: right;
            margin-right: 15px;
            color: #d0d0d0;
        }
        input[type="text"],
        input[type="number"] {
            width: 60%;
            padding: 12px;
            border-radius: 6px;
            border: none;
            outline: none;
            background: #3a3a3a;
            color: white;
            box-shadow: inset 0 2px 5px rgba(255, 255, 255, 0.1);
        }
        .form-button {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-top: 20px;
        }
        button {
            background: #444;
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
        }
        button:hover {
            transform: scale(1.15);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
            background: #666;
        }
    </style>
</head>
<body>
    <h2>Edit Car</h2>
    <div class="form-container">
        <form action="admin_edit_car.php" method="POST">
            <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
            
            <div class="form-group">
                <label for="make">Make:</label>
                <input type="text" id="make" name="make" value="<?php echo $car['make']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" value="<?php echo $car['model']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" value="<?php echo $car['year']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?php echo $car['price']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="kms_driven">KMs Driven:</label>
                <input type="number" id="kms_driven" name="kms_driven" value="<?php echo $car['kms_driven']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="registration_state">Registration State:</label>
                <input type="text" id="registration_state" name="registration_state" value="<?php echo $car['registration_state']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="current_location">Current Location:</label>
                <input type="text" id="current_location" name="current_location" value="<?php echo $car['current_location']; ?>" required>
            </div>
            
            <div class="form-button">
                <button type="submit">Update Car</button>
                <a href="admin_manage_cars.php" class="btn" style="background-color: #6c757d; text-decoration: none; padding: 14px 28px; border-radius: 12px; font-weight: bold;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
