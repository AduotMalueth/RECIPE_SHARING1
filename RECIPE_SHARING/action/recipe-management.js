
let recipes = [
    { id: 1, title: 'Chocolate Cake', author: 'John Doe', date: '01/10/2024' },
    { id: 2, title: 'Fried Chicken', author: 'Jane Doe', date: '02/10/2024' }
];
let editingRecipeId = null;

function goBack() {
    window.history.back();
}

function displayRecipes() {
    const tbody = document.querySelector('#recipeList tbody');
    tbody.innerHTML = '';

    recipes.forEach(recipe => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${recipe.id}</td>
            <td>${recipe.title}</td>
            <td>${recipe.author}</td>
            <td>${recipe.date}</td>
            <td>
                <button class="edit-btn" onclick="editRecipe(${recipe.id})">Edit</button>
                <button class="delete-btn" onclick="deleteRecipe(${recipe.id})">Delete</button>
                <button onclick="viewRecipe(${recipe.id})">View More</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function addRecipe(event) {
    event.preventDefault();

    const title = document.getElementById('title').value.trim();
    const author = document.getElementById('author').value.trim();
    const ingredients = document.getElementById('ingredients').value.trim();
    const prepTime = document.getElementById('prepTime').value.trim();
    const cookTime = document.getElementById('cookTime').value.trim();
    const servingSize = document.getElementById('servingSize').value.trim();
    const calories = document.getElementById('calories').value.trim();
    const instructions = document.getElementById('instructions').value.trim();

    if (!title || !author || !ingredients || !prepTime || !cookTime || !servingSize || !calories || !instructions) {
        alert('Please fill in all fields');
        return;
    }

    if (editingRecipeId !== null) {
        // Edit existing recipe
        const recipe = recipes.find(r => r.id === editingRecipeId);
        recipe.title = title;
        recipe.author = author;
        recipe.ingredients = ingredients;
        recipe.prepTime = prepTime;
        recipe.cookTime = cookTime;
        recipe.servingSize = servingSize;
        recipe.calories = calories;
        recipe.instructions = instructions;
        editingRecipeId = null;
    } else {
        // Add new recipe
        const newRecipe = {
            id: recipes.length + 1,
            title: title,
            author: author,
            ingredients: ingredients,
            prepTime: prepTime,
            cookTime: cookTime,
            servingSize: servingSize,
            calories: calories,
            instructions: instructions,
            date: new Date().toLocaleDateString(),
        };
        recipes.push(newRecipe);
    }

    displayRecipes();
    document.getElementById('recipeForm').reset();
}

function editRecipe(id) {
    const recipe = recipes.find(r => r.id === id);
    document.getElementById('title').value = recipe.title;
    document.getElementById('author').value = recipe.author;
    document.getElementById('ingredients').value = recipe.ingredients;
    document.getElementById('prepTime').value = recipe.prepTime;
    document.getElementById('cookTime').value = recipe.cookTime;
    document.getElementById('servingSize').value = recipe.servingSize;
    document.getElementById('calories').value = recipe.calories;
    document.getElementById('instructions').value = recipe.instructions;

    editingRecipeId = id;
}

function deleteRecipe(id) {
    recipes = recipes.filter(recipe => recipe.id !== id);
    displayRecipes();
}

function viewRecipe(id) {
    const recipe = recipes.find(r => r.id === id);
    document.getElementById('modalTitle').innerText = recipe.title;
    document.getElementById('modalDetails').innerText = `
        Author: ${recipe.author}
        Ingredients: ${recipe.ingredients}
        Preparation Time: ${recipe.prepTime} minutes
        Cooking Time: ${recipe.cookTime} minutes
        Serving Size: ${recipe.servingSize}
        Calories: ${recipe.calories}
        Instructions: ${recipe.instructions}
        Date Created: ${recipe.date}
    `;
    document.getElementById('recipe-modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('recipe-modal').style.display = 'none';
}

document.getElementById('recipeForm').addEventListener('submit', addRecipe);
displayRecipes();
