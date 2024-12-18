<?php
session_start();
include('config.php'); // Ensure this file sets up $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["userrole"]))) {
        array_push($error_array, "Please select a user role.");
    } else {
        $userrole = trim($_POST["userrole"]);
    }

    // Validate other fields
    if (empty(trim($_POST["first_name"]))) {
        array_push($error_array, "First Name is required.");
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    if (empty(trim($_POST["last_name"]))) {
        array_push($error_array, "Last Name is required.");
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        array_push($error_array, "Invalid email format.");
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        array_push($error_array, "Password is required.");
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["phone"]))) {
        array_push($error_array, "Phone number is required.");
    } else {
        $phone = trim($_POST["phone"]);
    }

    // If no errors, insert into database
    if (empty($error_array)) {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password, userrole) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $hashed_password, $userrole);
        $stmt->execute();        

        if ($stmt->execute()) {
            unset($_SESSION['errors']);
            header("location: index.php");
            exit();
        } else {
            array_push($error_array, "Database error. Please try again.");
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            background: url('./images/OIP.jpeg') no-repeat center center fixed;
            background-size: cover; /* Ensures the image covers the entire background */
            color: #ffffff; /* White text for better contrast against the background */
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: orangered;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }

        .form-group select {
            height: 40px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: orangered;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: darkred;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: orangered;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Register</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="userrole">User Role</label>
                <select id="userrole" name="userrole" required>
                    <option value="">Select Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Regular">Regular</option>
                </select>
            </div>
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="error-message">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="signup.php">Login here</a></p>
        </div>
    </div>
</body>
</html>