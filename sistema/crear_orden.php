<?php

include(dirname(__DIR__) . '/conexion.php');
global $conection;

$recipes = [];

$query = mysqli_query($conection, "SELECT * FROM recetas");

while ($row = $query->fetch_assoc()) {
    $row['name'] = html_entity_decode($row['name']);
    $recipes[] = $row;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Crear orden</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .new-order-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .ui-table.recipes-added {
            background: #f1f1f1;
            border-radius: 5px;
        }

        .recipes-added a.delete {
            color: #eb4d4b;
        }

        .bootstrap-autocomplete .dropdown-item {
            display: block;
            padding: 10px;
            cursor: pointer;
        }
        .dropdown-item.active, .dropdown-item:active {
            color: #fff;
            text-decoration: none;
            background-color: #007bff;
        }

        .bootstrap-autocomplete {
            background: #fff;
            border: 1px solid #ccc;
            position: absolute;
            z-index: 999;
            border-radius: 5px;
            overflow: hidden;
            overflow-y: hidden;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 10rem;
            padding: .5rem 0;
            margin: .125rem 0 0;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: .25rem;
        }

        .dropdown-menu.show {
            display: block;
        }
    </style>
</head>

<body>
    <?php include(__DIR__ . '/includes/header.php'); ?>
    <main id="container" class="ui-container">
        <form class="ui-box ui-form new-order-container" method="POST" action="guardar_orden.php">
            <h3 class="ui-box-title">Crear nueva orden</h3>
            <div class="ui-box-content">
                <?php if (isset($error)): ?>
                <div class="ui-alert error">
                    <p><?php echo $error; ?></p>
                </div>
                <?php endif; ?>

                <div class="ui-form-group">
                    <label for="order-date">Nombre cliente</label>
                    <input type="text" id="order-customer-name" name="customerName" class="ui-form-control" placeholder="Nombre cliente">
                </div>
                <div class="ui-form-group">
                    <label for="thumbnail">Recetas</label>
                    <div class="ui-form-group compound">
                        <div class="ui-form-group">
                            <label for="recipe-item">Receta</label>
                            <select class="form-control" name="recipes" id="recipe-item">
                                <?php foreach ($recipes as $recipe) : ?>
                                    <option value="<?php echo $recipe['id']; ?>">
                                        <?php echo $recipe['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="ui-form-group">
                            <label for="quantity">Cantidad</label>
                            <input type="number" name="quantity" id="recipe-quantity" placeholder="Cantidad" step="1" value="1">
                        </div>
                        <div class="ui-form-group button">
                            <button type="button" class="ui-button ui-button blue" id="add-recipe">Agregar</button>
                        </div>
                        <div id="recipes-fields"></div>
                    </div>
                    <div class="form-group">
                        <label>Recetas agregadas: </label>
                        <div class="ui-table recipes-added minimal small">
                            <div class="row header">
                                <div class="column">Receta</div>
                                <div class="column">Cantidad</div>
                                <div class="column min-width">Acciones</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui-box-footer right-aligned">
                <button type="submit" class="ui-button ui-button green">Guardar</button>
            </div>
        </form>
    </main>
    <?php include(__DIR__ . '/includes/footer.php'); ?>
    <template id="added-recipes-table-template">
        <div class="row">
            <div class="column name"></div>
            <div class="column quantity"></div>
            <div class="column">
                <a class="delete" href="javascript:void(0)">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        </div>
    </template>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.7/dist/latest/bootstrap-autocomplete.min.js"></script>
    <script>
        const recipes = <?php echo json_encode($recipes); ?>;
        const addedRecipes = [];
        let currentRecipe = null;

        $('#recipe-item').autoComplete({
            resolver: 'custom',
            events: {
                search: function(qry, callback) {
                    const filtered = recipes
                        .filter(recipe => recipe.name.toLowerCase().includes(qry.toLowerCase()))
                        .map(recipe => ({
                            value: recipe.id,
                            text: recipe.name
                        }));
                    callback(filtered);
                }
            }
        });

        $('#recipe-item').on('autocomplete.select', function(evt, item) {
            currentRecipe = item;
            return false;
        });

        const addRecipeButton = document.querySelector('#add-recipe');

        addRecipeButton.addEventListener('click', () => {
            if (currentRecipe === null) {
                return;
            }

            const quantity = parseInt(document.querySelector('#recipe-quantity').value);

            if (quantity <= 0) {
                return;
            }

            const recipe = addedRecipes.find(recipe => recipe.id === currentRecipe.value);

            if (recipe) {
                const index = addedRecipes.findIndex(addedRecipe => addedRecipe.id === recipe.id);
                addedRecipes[index].quantity += quantity;
            } else {
                addedRecipes.push({
                    id: currentRecipe.value,
                    name: currentRecipe.text,
                    quantity: quantity
                });
            }

            updateRecipesFields();
            updateRecipeItems();
            console.log($('#recipe-item').autoComplete('get'));
        });

        function updateRecipesFields() {
            const recipesFields = document.querySelector('#recipes-fields');
            let index = 0;

            recipesFields.innerHTML = '';

            addedRecipes.forEach(recipe => {
                const inputId = document.createElement('input');
                const inputQuantity = document.createElement('input');

                inputId.type = 'hidden';
                inputId.name = `recipes[${index}][id]`;
                inputId.value = recipe.id;

                inputQuantity.type = 'hidden';
                inputQuantity.name = `recipes[${index}][quantity]`;
                inputQuantity.value = recipe.quantity;

                recipesFields.appendChild(inputId);
                recipesFields.appendChild(inputQuantity);
                index++;
            });
        }

        function updateRecipeItems() {
            const table = document.querySelector('.ui-table.recipes-added');

            const rows = table.querySelectorAll('.row:not(.header)');
            rows.forEach(row => row.remove());

            for (const recipe of addedRecipes) {
                const template = document.querySelector('#added-recipes-table-template');
                const clone = template.content.cloneNode(true);

                clone.querySelector('.name').textContent = recipe.name;
                clone.querySelector('.quantity').textContent = recipe.quantity;
                table.appendChild(clone);

                const deleteButton = table.querySelector('.delete:last-child');
                deleteButton.addEventListener('click', () => {
                    const index = addedRecipes.findIndex(addedRecipe => addedRecipe.id === recipe.id);
                    addedRecipes.splice(index, 1);
                    updateRecipesFields();
                    updateRecipeItems();
                });
            }
        }
    </script>
</body>

</html>