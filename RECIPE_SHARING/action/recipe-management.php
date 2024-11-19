<?php
// Check connection
include('config.php');


// Define variables and initialize with empty values
$title = $author = $ingredients = $prepTime = $cookTime = $servingSize = $calories = $instructions = "";
$editingRecipeId = null;

// Fetch recipes from the database
function fetchRecipes($conn) {
    $sql = "SELECT * FROM recipes";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add or update recipe based on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $ingredients = $_POST['ingredients'];
    $prepTime = $_POST['prepTime'];
    $cookTime = $_POST['cookTime'];
    $servingSize = $_POST['servingSize'];
    $calories = $_POST['calories'];
    $instructions = $_POST['instructions'];

    if (isset($_POST['editingRecipeId']) && $_POST['editingRecipeId'] != "") {
        $editingRecipeId = $_POST['editingRecipeId'];
        // Update recipe
        $sql = "UPDATE recipes SET title=?, author=?, ingredients=?, prepTime=?, cookTime=?, servingSize=?, calories=?, instructions=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $title, $author, $ingredients, $prepTime, $cookTime, $servingSize, $calories, $instructions, $editingRecipeId);
    } else {
        // Insert new recipe
        $sql = "INSERT INTO recipes (title, author, ingredients, prepTime, cookTime, servingSize, calories, instructions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $title, $author, $ingredients, $prepTime, $cookTime, $servingSize, $calories, $instructions);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: recipe-management.php"); // Refresh to clear the form
    exit;
}

// Delete recipe if delete request is made
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM recipes WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: recipe-management.php");
    exit;
}

$recipes = fetchRecipes($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Management</title>
    <style>
        /* Include styles here, similar to the HTML code */
    </style>
</head>
<body>
    <div class="header-container">
        <h1>Recipe Management</h1>
        <a href="index.php" class="back-button">Go Back</a>
    </div>

    <section id="recipe-form">
        <h2><?= $editingRecipeId ? "Edit Recipe" : "Add New Recipe" ?></h2>
        <form method="POST" action="recipe-management.php">
            <input type="hidden" name="editingRecipeId" value="<?= $editingRecipeId ?>">
            <div class="form-group">
                <label for="title">Recipe Title:</label>
                <input type="text" id="title" name="title" value="<?= $title ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" value="<?= $author ?>" required>
            </div>
            <div class="form-group">
                <label for="ingredients">Ingredients:</label>
                <textarea id="ingredients" name="ingredients" rows="4" required><?= $ingredients ?></textarea>
            </div>
            <div class="form-group">
                <label for="prepTime">Preparation Time (minutes):</label>
                <input type="number" id="prepTime" name="prepTime" value="<?= $prepTime ?>" required>
            </div>
            <div class="form-group">
                <label for="cookTime">Cooking Time (minutes):</label>
                <input type="number" id="cookTime" name="cookTime" value="<?= $cookTime ?>" required>
            </div>
            <div class="form-group">
                <label for="servingSize">Serving Size:</label>
                <input type="number" id="servingSize" name="servingSize" value="<?= $servingSize ?>" required>
            </div>
            <div class="form-group">
                <label for="calories">Calories per Serving:</label>
                <input type="number" id="calories" name="calories" value="<?= $calories ?>" required>
            </div>
            <div class="form-group">
                <label for="instructions">Instructions:</label>
                <textarea id="instructions" name="instructions" rows="5" required><?= $instructions ?></textarea>
            </div>
            <button type="submit" class="submit-btn"><?= $editingRecipeId ? "Update Recipe" : "Add Recipe" ?></button>
        </form>
    </section>

    <section id="recipe-table">
        <h2>All Recipes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recipes as $recipe): ?>
                    <tr>
                        <td><?= $recipe['id'] ?></td>
                        <td><?= $recipe['title'] ?></td>
                        <td><?= $recipe['author'] ?></td>
                        <td><?= $recipe['date_created'] ?></td>
                        <td>
                            <a href="recipe-management.php?edit=<?= $recipe['id'] ?>" class="edit-btn">Edit</a>
                            <a href="recipe-management.php?delete=<?= $recipe['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                            <button onclick="viewRecipe(<?= $recipe['id'] ?>)">View More</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Modal for viewing recipe details -->
    <div id="recipe-modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" onclick="document.getElementById('recipe-modal').style.display='none'">×</span>
            <h3 id="modalTitle"></h3>
            <p id="modalDetails"></p>
        </div>
    </div>

    <script>
        function viewRecipe(id) {
            // Fetch recipe details from server or populate directly if data is preloaded
            // Display modal
            document.getElementById('recipe-modal').style.display = 'block';
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
