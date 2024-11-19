<?php
// PHP logic for redirecting and handling dynamic content
session_start(); // Start a session to handle user state, if required (e.g., user login)
// Include database connection
include('config.php'); // This will include your database connection
if (!isset($_SESSION['user_logged_in'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Example of fetching dynamic data (recipes)
$recipes = [
    [
        'title' => 'Fried Fish',
        'description' => 'Healthy grilled fish with herbs.',
        'status' => 'Active',
        'image' => 'images/OIP (1).jpeg'
    ],
    [
        'title' => 'Bread Food',
        'description' => 'Tasty vegetable stir fry.',
        'status' => 'Pending',
        'image' => 'images/OIP (2).jpeg'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regular Admin Dashboard</title>
    <link rel="stylesheet" href="Recipes.css">

    <!-- Include Chart.js for optional analytics charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Back Button -->
    <button onclick="goBack()" class="back-button">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Go to Recipes Page
    </button>

    <!-- Page Title -->
    <h1>Regular Admin Dashboard</h1>

    <!-- Personal Analytics Section -->
    <section class="personal-analytics">
        <h2>Personal Analytics</h2>
        <p>Total Recipes Added: <span id="totalRecipes">10</span></p>
        <h3>Recent Recipe Submissions</h3>
        <ul id="recentRecipes">
            <li>Fried Fish - Submitted on 01/01/2024</li>
            <li>Spicy Fried Chicken - Submitted on 02/01/2024</li>
        </ul>
    </section>

    <!-- Recipe Management Section -->
    <section class="recipe-management">
        <h2>Recipe Management</h2>
        <div class="recipe-grid">
            <!-- Dynamic Recipe Cards -->
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <img src="<?= $recipe['image'] ?>" alt="Recipe Image">
                    <h3><?= $recipe['title'] ?></h3>
                    <p><?= $recipe['description'] ?></p>
                    <span>Status: <?= $recipe['status'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Optional: Analytics Charts -->
    <section class="analytics-charts">
        <h2>Personal Analytics Charts</h2>

        <!-- Submission Trends Chart -->
        <h3>Recipe Submission Trends</h3>
        <canvas id="submissionTrendsChart"></canvas>

        <!-- Category Distribution Chart -->
        <h3>Recipe Category Distribution</h3>
        <canvas id="categoryDistributionChart"></canvas>
    </section>

    <script>
        // JavaScript function to navigate to Recipes.html
        function goBack() {
            window.location.href = 'Recipes.html'; // Adjust the path if necessary
        }

        // Chart.js Configuration
        window.onload = function() {
            // Submission Trends Chart
            const submissionTrendsCtx = document.getElementById('submissionTrendsChart').getContext('2d');
            new Chart(submissionTrendsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                    datasets: [{
                        label: 'Recipes Submitted',
                        data: [2, 3, 1, 4, 5],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false
                    }]
                }
            });

            // Category Distribution Chart
            const categoryDistributionCtx = document.getElementById('categoryDistributionChart').getContext('2d');
            new Chart(categoryDistributionCtx, {
                type: 'bar',
                data: {
                    labels: ['Vegetarian', 'Non-Vegetarian', 'Vegan', 'Dessert'],
                    datasets: [{
                        label: 'Category Count',
                        data: [10, 20, 5, 8],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        };
    </script>
</body>
</html>
