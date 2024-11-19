<?php
// Check if the form has been submitted
// errors handling here
include('errors.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['agree'])) {
        // If the user agrees, redirect them to signup.php
        header("Location: signup.php");
        exit();
    } elseif (isset($_POST['disagree'])) {
        // If the user disagrees, exit the application or redirect
        echo "<script>alert('You must agree to the terms to proceed.'); window.close();</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions</title>
    <style>
        /* Your CSS styles here */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: rgba(0, 0, 0, 0.7) url('images/OIP.jpeg') no-repeat center center fixed;
            background-size: cover;
            background-blend-mode: darken;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2rem;
        }

        p {
            margin: 10px 0;
            font-size: 1rem;
        }

        a, button {
            color: #FFA500; 
            text-decoration: none;
            border: none;
            padding: 10px 20px;
            background-color: #FFA500;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            margin: 10px;
            border-radius: 5px;
        }

        a:hover, button:hover {
            text-decoration: underline; 
            background-color: #e69500;
        }
    </style>
</head>
<body>
    <div>
        <h1>Terms & Conditions</h1>

        <p>1. Do not share your account information with others.</p>
        <p>2. All recipes submitted must adhere to community guidelines.</p>
        <p>3. Pay before you order to ensure your recipes are processed.</p>
        <p>4. Use only approved ingredients in your recipes.</p>

        <form method="POST">
            <button type="submit" name="agree">Agree</button>
            <button type="submit" name="disagree">Disagree</button>
        </form>
    </div>
</body>
</html>
