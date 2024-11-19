<?php
session_start();
include('config.php'); // Database connection
include('error_config.php'); // Custom error handling

$error_array = [];
$email = $password = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        array_push($error_array, "Please enter your email.");
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        array_push($error_array, "Please enter your password.");
    } else {
        $password = trim($_POST["password"]);
    }

    // Proceed if no validation errors
    if (empty($error_array)) {
        $sql = "SELECT user_id, first_name, last_name, userrole, password_hash FROM USERS WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Check if the email exists
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($user_id, $first_name, $last_name, $userrole, $hashed_password);
                $stmt->fetch();

                // Verify the password
                if (password_verify($password, $hashed_password)) {
                    // Successful login: store user information in session
                    $_SESSION["user_id"] = $user_id;
                    $_SESSION["first_name"] = $first_name;
                    $_SESSION["last_name"] = $last_name;
                    $_SESSION["userrole"] = $userrole;

                    // Redirect based on role
                    if ($userrole === "Admin") {
                        header("Location: Admin-dashboard.php");
                    } else {
                        header("Location: Reciples.php");
                    }
                    exit();
                } else {
                    array_push($error_array, "Invalid email or password.");
                }
            } else {
                array_push($error_array, "Invalid email or password.");
            }
            $stmt->close();
        } else {
            array_push($error_array, "Something went wrong. Please try again later.");
        }
    }

    // Store errors in session and redirect back to login page if there are errors
    if (!empty($error_array)) {
        $_SESSION['errors'] = $error_array;
        header("location: login.php");
        exit();
    }
}

// Close the database connection
$conn->close();
?>
