<?php
// Check connection
include('config.php');
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search input from the form
    $searchTerm = trim($_POST['search']);

    // Dummy data for recipes
    $recipes = [
        [
            "title" => "Fried Fish",
            "description" => "Healthy and delicious grilled fish with herbs.",
            "image" => "images/OIP (1).jpeg"
        ],
        [
            "title" => "Bread Food",
            "description" => "A quick and tasty vegetable stir fry with soy sauce.",
            "image" => "images/OIP (2).jpeg"
        ],
        [
            "title" => "Paper Food",
            "description" => "A quick and tasty sauce.",
            "image" => "images/OIP (3).jpeg"
        ],
        [
            "title" => "Fried Meat with Ingredients",
            "description" => "Rich and moist tasty topped food with ganache.",
            "image" => "images/OIP (4).jpeg"
        ],
        [
            "title" => "Fried Chicken with Ingredients",
            "description" => "This dish provides a delicious contrast of flavors.",
            "image" => "images/OIP (5).jpeg"
        ],
        [
            "title" => "Spicy Fried Chicken with Ingredients",
            "description" => "Perfect for those who crave a bit of spice in their meal.",
            "image" => "images/OIP (6).jpeg"
        ],
        [
            "title" => "Fried delicious chicken with Ingredients",
            "description" => "Rich Herb-Crusted Fried Chicken.",
            "image" => "images/OIP (7).jpeg"
        ],
        [
            "title" => "Classic Southern Fried Chicken",
            "description" => "Rich and tasty Classic Southern Fried Chicken.",
            "image" => "images/OIP (8).jpeg"
        ],
        [
            "title" => "Delicious-fried-chicken",
            "description" => "Rich and delicious-fried-chicken topped food with ganache.",
            "image" => "images/OIP (9).jpeg"
        ],
        [
            "title" => "Delicious-fried-chicken with Ingredients",
            "description" => "It's a delightful fusion of sweet and savory that will leave you wanting more.",
            "image" => "images/OIP (10).jpeg"
        ],
    ];

    // Filter recipes based on the search term
    $filteredRecipes = [];
    if (!empty($searchTerm)) {
        foreach ($recipes as $recipe) {
            if (stripos($recipe["title"], $searchTerm) !== false || stripos($recipe["description"], $searchTerm) !== false) {
                $filteredRecipes[] = $recipe;
            }
        }
    } else {
        $filteredRecipes = $recipes; // Show all recipes if no search term is entered
    }
} else {
    // Redirect to the Recipe Feed if accessed directly
    header("Location: Recipes.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Results</title>
    <link rel="stylesheet" href="Recipes.css">
</head>
<body>
    <button onclick="window.location.href='RecipeFeed.html'" class="back-button">Go Back</button>
    <h1>Recipe Search Results</h1>
    <div class="recipe-grid">
        <?php if (!empty($filteredRecipes)) : ?>
            <?php foreach ($filteredRecipes as $recipe) : ?>
                <div class="recipe-card">
                    <img src="<?php echo htmlspecialchars($recipe["image"]); ?>" alt="Recipe Image" style="max-width:100%; height:auto;">
                    <h3><?php echo htmlspecialchars($recipe["title"]); ?></h3>
                    <p><?php echo htmlspecialchars($recipe["description"]); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No recipes found matching your search criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>
