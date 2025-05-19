<?php
include 'db_connect.php'; // Include database connection
session_start();

// Check if user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "admin") {
    die("You must be an admin to edit users. <a href='login.html'>Login here</a>");
}

// If form is submitted, update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    
    $update_sql = "UPDATE users SET username=?, email=?, user_type=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $username, $email, $user_type, $user_id);
    
    if ($stmt->execute()) {
        header("Location: admin_manage_users.php?success=User updated successfully");
        exit();
    } else {
        echo "<p style='color:red;'>Error updating user: " . $conn->error . "</p>";
    }
}

if (!isset($_GET['user_id'])) {
    die("User ID not provided.");
}

$user_id = $_GET['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        input[type="text"], input[type="email"] {
            width: 60%;
            padding: 12px;
            border-radius: 6px;
            border: none;
            outline: none;
            background: #3a3a3a;
            color: white;
            box-shadow: inset 0 2px 5px rgba(255, 255, 255, 0.1);
        }
        select {
            width: 63%;
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
    <h2>Edit User</h2>
    <div class="form-container">
        <form action="admin_edit_user.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="user_type">Role:</label>
                <select id="user_type" name="user_type" required>
                    <option value="customer" <?php if($user['user_type'] == 'customer') echo 'selected'; ?>>Customer</option>
                    <option value="seller" <?php if($user['user_type'] == 'seller') echo 'selected'; ?>>Seller</option>
                </select>
            </div>
            
            <div class="form-button">
                <button type="submit">Update User</button>
                <a href="admin_manage_users.php" class="btn" style="background-color: #6c757d; text-decoration: none; padding: 14px 28px; border-radius: 12px; font-weight: bold;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
