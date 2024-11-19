<?php
session_start();

// Include the database connection file
include 'config.php';

// Check if the login form is submitted
if (isset($_POST['login'])) {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Check if user exists and verify password
    if ($user) {
        // Verify password using password_hash and password_verify
        if (password_verify($password, $user['password'])) {
            // Store user information in session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] == 1) {
                // Super Admin Dashboard
                header("Location: Admin-dashboard.php");
                exit();
            } else if ($user['role'] == 2) {
                // Regular Admin Dashboard
                header("Location: Regular_Admin.php");
                exit();
            } else {
                // Default redirect if the role is not recognized
                header("Location: index.php");
                exit();
            }
        } else {
            // Incorrect password
            $error_message = "Invalid password!";
        }
    } else {
        // No user found with the entered email
        $error_message = "No user found with that email!";
    }
}
?>

<!-- HTML for displaying login page with error message if needed -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content ="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
    </div>

    <div class="content">
        <h3>Welcome Back!</h3>

        <?php
        // Display error message if any
        if (isset($error_message)) {
            echo "<div class='error'>$error_message</div>";
        }
        ?>

        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required><br><br>

            <input type="submit" value="Login" name="login">
        </form>
        <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
    </div>
</body>
</html>
