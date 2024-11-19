<?php
// Start the session and include the database connection
session_start();
include('config.php');

// Check if the user is logged in and retrieve role
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role']; // e.g., 'super_admin' or 'admin'

// Variables for storing analytics and recipe data
$totalUsers = $totalRecipes = $pendingApprovals = 0;
$recipes = [];
$recentRecipes = [];
$userRecipes = [];
$userRecipeCount = 0;

// Fetch analytics data for Super Admin
if ($user_role === 'super_admin') {
    try {
        // Total number of users
        $result = $pdo->query("SELECT COUNT(*) FROM users");
        $totalUsers = $result->fetchColumn();

        // Total number of recipes
        $result = $pdo->query("SELECT COUNT(*) FROM recipes");
        $totalRecipes = $result->fetchColumn();

        // Number of pending user approvals
        $result = $pdo->query("SELECT COUNT(*) FROM users WHERE approved = 0");
        $pendingApprovals = $result->fetchColumn();

        // List of recently added recipes
        $stmt = $pdo->query("SELECT * FROM recipes ORDER BY created_at DESC LIMIT 5");
        $recentRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch personal analytics and recipes for Regular Admin
if ($user_role === 'admin') {
    try {
        // Number of recipes added by this admin
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM recipes WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $userRecipeCount = $stmt->fetchColumn();

        // Recent recipes added by this admin
        $stmt = $pdo->prepare("SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$user_id]);
        $userRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <h1>Recipe Admin Dashboard</h1>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <?php if ($user_role === 'super_admin'): ?>
            <!-- Super Admin Dashboard Content -->
            <section>
                <h2>System Analytics</h2>
                <p>Total Users: <?= $totalUsers ?></p>
                <p>Total Recipes: <?= $totalRecipes ?></p>
                <p>Pending User Approvals: <?= $pendingApprovals ?></p>

                <h3>Recent Recipes</h3>
                <ul>
                    <?php foreach ($recentRecipes as $recipe): ?>
                        <li><?= htmlspecialchars($recipe['title']) ?> - <?= htmlspecialchars($recipe['created_at']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
            
            <section>
                <h2>User Management</h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registration Date</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users");
                    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td>
                                <form action="delete_user.php" method="post">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </section>

        <?php elseif ($user_role === 'admin'): ?>
            <!-- Regular Admin Dashboard Content -->
            <section>
                <h2>Personal Analytics</h2>
                <p>Total Recipes: <?= $userRecipeCount ?></p>

                <h3>Your Recent Recipes</h3>
                <ul>
                    <?php foreach ($userRecipes as $recipe): ?>
                        <li><?= htmlspecialchars($recipe['title']) ?> - <?= htmlspecialchars($recipe['created_at']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section>
                <h2>Recipe Management</h2>
                <table>
                    <tr>
                        <th>Recipe Title</th>
                        <th>Created At</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    $stmt = $pdo->prepare("SELECT title, created_at, status FROM recipes WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    while ($recipe = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($recipe['title']) ?></td>
                            <td><?= htmlspecialchars($recipe['created_at']) ?></td>
                            <td><?= htmlspecialchars($recipe['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </section>
        <?php endif; ?>
    </div>
</body>
</html>
