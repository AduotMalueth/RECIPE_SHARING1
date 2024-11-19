<?php
session_start();
include'config.php';
// Assuming you have validated the user and the role is 'regular'
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // User signup logic here (e.g., inserting user data into the database)

    // Set session variables
    $_SESSION['username'] = $username;  // Assuming you store username
    $_SESSION['role'] = 'regular';      // Set user role to regular

    // Redirect to Recipe Feed page
    header("Location: Recipes.php");
    exit();
}
?>
