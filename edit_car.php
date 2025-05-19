<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if user is logged in and is a seller
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "seller") {
    die("You must be logged in as a seller to edit cars. <a href='login.html'>Login here</a>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $kms_driven = $_POST['kms_driven'];
    $mobile_no = $_POST['mobile_no'];
    $registration_state = $_POST['registration_state'];
    $current_location = $_POST['current_location']; // Corrected key name

    $sql = "UPDATE cars SET make=?, model=?, year=?, price=?, kms_driven=?, mobile_no=?, registration_state=?, current_location=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiissssii", $make, $model, $year, $price, $kms_driven, $mobile_no, $registration_state, $current_location, $car_id, $_SESSION['user_id']); // Corrected number of parameters

    if ($stmt->execute()) {
        header("Location: seller_dashboard.html?success=Car updated successfully");
        exit();
    } else {
        echo "<p style='color: red;'>Error updating car: " . $conn->error . "</p>";
    }
}

if (!isset($_GET['car_id'])) {
    die("Car ID not provided.");
}

$car_id = $_GET['car_id'];
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM cars WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $car_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Car not found or you don't have permission to edit this car.");
}

$car = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://wallpaperaccess.com/full/9254213.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 32px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
        }

        form {
            background: rgba(51, 51, 51, 0.8);
            backdrop-filter: blur(10px);
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 600px;
            animation: fadeIn 0.8s ease-out;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label {
            font-size: 16px;
            color: #ddd;
            font-weight: 500;
            margin-bottom: 5px;
        }

        input {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(68, 68, 68, 0.7);
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
        }

        input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        input::placeholder {
            color: #aaa;
        }

        .button-container {
            grid-column: span 2;
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        button {
            background: linear-gradient(135deg, #555 0%, #333 100%);
            color: white;
            border: none;
            padding: 14px 40px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #666 0%, #444 100%);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success-message {
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
            color: #c82333;
        }
        
        .dashboard-link {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s ease-in-out;
            border: 2px solid white;
            background: linear-gradient(135deg, #333, #c82333);
            color: white;
        }
        
        .dashboard-link:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.5);
        }
        
        .back-link {
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        
        .back-link:hover {
            transform: translateX(-5px);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
        }

        /* Adding responsive design */
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .button-container {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <a href="view_my_cars.php" class="back-link"><i class="fas fa-arrow-left"></i>&nbsp; Back to My Cars</a>
    <h2>Edit Car Details</h2>
    
    <form action="edit_car.php" method="POST">
        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
        
        <div class="form-group">
            <label for="make">Brand</label>
            <input type="text" id="make" name="make" value="<?php echo $car['make']; ?>" placeholder="e.g. Toyota" required>
        </div>

        <div class="form-group">
            <label for="model">Model</label>
            <input type="text" id="model" name="model" value="<?php echo $car['model']; ?>" placeholder="e.g. Corolla" required>
        </div>

        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" id="year" name="year" value="<?php echo $car['year']; ?>" placeholder="e.g. 2022" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" value="<?php echo $car['price']; ?>" placeholder="e.g. 25000.00" required>
        </div>

        <div class="form-group">
            <label for="kms_driven">Kilometres Driven</label>
            <input type="number" id="kms_driven" name="kms_driven" value="<?php echo $car['kms_driven']; ?>" placeholder="e.g. 15000" required>
        </div>

        <div class="form-group">
            <label for="mobile_no">Mobile Number</label>
            <input type="text" id="mobile_no" name="mobile_no" value="<?php echo $car['mobile_no']; ?>" placeholder="e.g. 9876543210" required>
        </div>
        
        <div class="form-group">
            <label for="registration_state">Registration State</label>
            <input type="text" id="registration_state" name="registration_state" value="<?php echo $car['registration_state']; ?>" placeholder="e.g. California" required>
        </div>
        
        <div class="form-group">
            <label for="current_location">Current Location</label>
            <input type="text" id="current_location" name="current_location" value="<?php echo $car['current_location']; ?>" placeholder="e.g. Los Angeles" required>
        </div>

        <div class="button-container">
            <button type="submit"><i class="fas fa-save"></i> Update Car</button>
        </div>
    </form>
</body>
</html>