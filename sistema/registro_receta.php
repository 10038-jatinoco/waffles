<?php
    include(dirname(__DIR__) . '/conexion.php');
    global $conection;

    $totals = [];

    $query = mysqli_query($conection, "SELECT * FROM producto");
    $totals['products'] = mysqli_num_rows($query);
    $products = [];

    while ($row = $query->fetch_assoc()) {
        $products[] = $row;
    }

    $query = mysqli_query($conection, "SELECT * FROM recetas");
    $totals['recipes'] = mysqli_num_rows($query);
    $recipes = [];

    while ($row = $query->fetch_assoc()) {
        $recipes[] = $row;
    }

    $update_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($id) ? intval($id) : null);

    if ($update_id) {
        $query = mysqli_query($conection, "SELECT * FROM recetas WHERE id = $update_id");
        $update_recipe = $query->fetch_assoc();
        $subquery = mysqli_query($conection,
            "SELECT p.codproducto, p.descripcion, rp.cantidad, p.medida_pro, p.precio
                FROM receta_producto as rp
                LEFT JOIN producto as p ON (p.codproducto = rp.producto_id)
                WHERE rp.receta_id = {$update_recipe['id']}");
        $update_recipe['ingredients'] = [];
        $update_recipe['manufacturingCost'] = 0;

        while ($subrow = $subquery->fetch_assoc()) {
            $update_recipe['ingredients'][] = $subrow;
            $update_recipe['manufacturingCost'] += $subrow['cantidad'] * $subrow['precio'];
        }
    }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Crear receta</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .ingredients-list {
            background: #c3e6ff;
            color: #196499;
            display: table;
            width: 100%;
        }

        .ingredients-list .row {
            display: table-row;
        }

        .ingredients-list .row .column {
            display: table-cell;
            padding: 10px;
        }

        .ingredients-list .row .column.quantity {
            background: #2480c2;
            color: #fff;
            border-radius: inherit;
            white-space: nowrap;
            width: 1px;
        }

        .ingredients-list .row .column.options {
            width: 1px;
            white-space: nowrap;
        }

        .ingredients-list .row .column.name {
            width: 90%;
        }

        .final-price {
            border-top: 2px dashed #2480c2;
            margin-top: 0px;
            padding: 9px 2px;
            background: #ccdce8;
        }
    </style>
</head>
<body>
<?php include(__DIR__ . '/includes/header.php'); ?>
<main id="container" class="ui-container">
    <form class="container-recipes ui-box ui-form recipe-form" method="POST" action="guardar_receta.php" enctype="multipart/form-data">
        <h2 class="ui-box-title">Registrar nueva receta</h2>
        <div class="ui-box-content">
            <?php if (isset($error)): ?>
            <div class="ui-alert error">
                <p><?php echo $error; ?></p>
            </div>
            <?php endif; ?>
            <div class="ui-form-group">
                <label for="name">Nombre</label>
                <input <?php echo (isset($update_recipe) ? 'value="' . $update_recipe['name'] . '"' : ''); ?> type="text" name="name" id="name" placeholder="Nombre de la receta" required>
            </div>
            <div class="ui-form-group">
                <label for="thumbnail">Imagen</label>
                <input type="file" name="thumbnail" id="thumbnail">
            </div>
            <div class="ui-form-group">
                <label for="thumbnail">Ingredientes</label>
                <div class="ui-form-group compound">
                    <div class="ui-form-group">
                        <label for="ingredient-item">Ingrediente</label>
                        <select name="products" id="ingredient-item">
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['codproducto']; ?>" 
                                        data-name="<?php echo $product['descripcion']; ?>"
                                        data-medida="<?php echo $product['medida_pro']; ?>"
                                        data-precio="<?php echo $product['precio']; ?>">
                                    <?php echo $product['descripcion']; ?> (<?php echo $product['medida_pro']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ui-form-group">
                        <label for="quantity">Cantidad</label>
                        <input type="number" name="quantity" id="ingredient-quantity" placeholder="Cantidad" step="0.01" value="1">
                    </div>
                    <div class="ui-form-group button">
                        <button type="button" class="ui-button ui-button blue" id="add-ingredient">Agregar</button>
                    </div>
                    <div id="ingredients-fields">
                        <?php if (isset($update_recipe)): ?>
                            <?php
                                $index = 0;
                            ?>
                            <?php foreach ($update_recipe['ingredients'] as $ingredient): ?>
                                <input type="hidden" name="ingredients[<?php echo $index; ?>][id]" value="<?php echo $ingredient['codproducto']; ?>">
                                <input type="hidden" name="ingredients[<?php echo $index; ?>][quantity]" value="<?php echo $ingredient['cantidad']; ?>">
                                <?php $index++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="ingredients-list" class="ingredients-list">
                    <?php if (isset($update_recipe)): ?>
                        <?php foreach ($update_recipe['ingredients'] as $ingredient): ?>
                            <div class="row">
                                <div class="column quantity">
                                    <?php echo $ingredient['cantidad'] . ' ' . $ingredient['medida_pro']; ?>
                                </div>
                                <div class="column name">
                                    <?php echo $ingredient['descripcion']; ?>
                                </div>
                                <div class="column options">
                                    <a href="javascript:void(0)" onclick="deleteIngredient(<?php echo $ingredient['codproducto']; ?>)"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="final-price">
                    <strong>Precio final: </strong>
                    <span id="final-price"><?php echo (isset($update_recipe) ? number_format($update_recipe['manufacturingCost'], 2, '.', '') : 0); ?></span>
                </div>
            </div>
        </div>
        <div class="ui-box-footer right-aligned">
            <button type="submit" class="ui-button ui-button green">Guardar</button>
        </div>
        <?php if (isset($update_recipe)): ?>
            <input type="hidden" name="id" value="<?php echo $update_recipe['id']; ?>">
        <?php endif; ?>
    </form>
</main>
<?php include(__DIR__ . '/includes/footer.php'); ?>
<script>
    const products = <?php echo json_encode($products); ?>;
    const addIngredientBtn = document.querySelector('#add-ingredient');
    const ingredients = <?php
        if (isset($update_recipe)) {
            $ingredients = [];

            foreach ($update_recipe['ingredients'] as $ingredient) {
                $ingredients[] = [
                    'id' => intval($ingredient['codproducto']),
                    'quantity' => $ingredient['cantidad'],
                    'name' => $ingredient['descripcion'],
                    'price' => $ingredient['precio']
                ];
            }

            echo json_encode($ingredients);
        } else {
            echo '[]';
        }
    ?>;

    addIngredientBtn.addEventListener('click', () => {
        const id = document.querySelector('#ingredient-item').value;
        const quantity = parseFloat(document.querySelector('#ingredient-quantity').value);

        if (!id) {
            alert('No se ha seleccionado un ingrediente');
            return;
        }

        if (isNaN(quantity) || quantity <= 0) {
            alert('La cantidad debe ser mayor a 0');
            return;
        }

        const name = document.querySelector('#ingredient-item')
            .options[document.querySelector('#ingredient-item').selectedIndex].dataset.name;

        const price = parseFloat(document.querySelector('#ingredient-item')
            .options[document.querySelector('#ingredient-item').selectedIndex].dataset.precio);

        const ingredient = {
            id,
            quantity,
            name,
            price
        };

        const exists = ingredients.find(ingredient => ingredient.id === id);

        if (exists) {
            const index = ingredients.indexOf(exists);
            ingredients[index].quantity += quantity;
            updateHiddenFields();
            updateIngredientsList();
            return;
        }

        ingredients.push(ingredient);
        updateHiddenFields();
        updateIngredientsList();
    });

    function updateIngredientsList() {
        const ingredientsList = document.querySelector('#ingredients-list');
        ingredientsList.innerHTML = '';

        let finalPrice = 0.0;

        ingredients.forEach(ingredient => {
            const row = document.createElement('div');
            row.classList.add('row');
            const producto = products.find(product => product.codproducto == ingredient.id);
            const unidad = producto['medida_pro'];

            row.innerHTML = `
                <div class="column quantity">${ingredient.quantity} ${unidad} ($${(ingredient.quantity * ingredient.price).toFixed(2)})</div>
                <div class="column name">${ingredient.name}</div>
                <div class="column options">
                    <a href="javascript:void(0)" onclick="deleteIngredient(${ingredient.id})"><i class="fa-solid fa-trash"></i></a>
                </div>
            `;
            ingredientsList.appendChild(row);

            finalPrice += ingredient.quantity * ingredient.price;
        });

        document.querySelector('#final-price').innerHTML = finalPrice.toFixed(2);
    }

    function deleteIngredient(id) {
        const ingredient = ingredients.find(ingredient => ingredient.id === id);
        const index = ingredients.indexOf(ingredient);
        ingredients.splice(index, 1);
        updateHiddenFields();
        updateIngredientsList();
    }

    function updateHiddenFields() {
        const ingredientsFields = document.querySelector('#ingredients-fields');
        ingredientsFields.innerHTML = '';

        ingredients.forEach((ingredient, index) => {
            const idField = document.createElement('input');
            idField.type = 'hidden';
            idField.name = `ingredients[${index}][id]`;
            idField.value = ingredient.id;

            const quantityField = document.createElement('input');
            quantityField.type = 'hidden';
            quantityField.name = `ingredients[${index}][quantity]`;
            quantityField.value = ingredient.quantity;

            ingredientsFields.appendChild(idField);
            ingredientsFields.appendChild(quantityField);
        });
    }
</script>
</body>
</html>