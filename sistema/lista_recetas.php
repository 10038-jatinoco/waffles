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
    $subquery = mysqli_query($conection,
        "SELECT p.codproducto, p.descripcion, rp.cantidad, p.medida_pro, p.precio
                FROM receta_producto as rp
                LEFT JOIN producto as p ON (p.codproducto = rp.producto_id)
                WHERE rp.receta_id = {$row['id']}");
    $row['ingredients'] = [];
    $manufacturingCost = 0;

    while ($subrow = $subquery->fetch_assoc()) {
        $row['ingredients'][] = $subrow;
        $manufacturingCost += $subrow['cantidad'] * $subrow['precio'];
    }

    $row['manufacturingCost'] = $manufacturingCost;

    $recipes[] = $row;
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista de recetas</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .recipe-thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .ingredients-list {
            display: flex;
            flex-direction: column;
            font-size: 0.9em;
        }

        .ingredients-list > .ingredient {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .ingredients-list > .ingredient > .name {
            font-weight: bold;
        }

        .ingredients-list > .ingredient > .quantity {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<?php include(__DIR__ . '/includes/header.php'); ?>
<main id="container" class="ui-container">
    <div class="ui-box recipes-container">
        <h3 class="ui-box-title">Lista de recetas</h3>
        <div class="ui-box-content">
            <div class="ui-table">
                <div class="row header">
                    <div class="column min-width"></div>
                    <div class="column">Nombre</div>
                    <div class="column">Precio</div>
                    <div class="column">Ingredientes</div>
                    <div class="column min-width">Acciones</div>
                </div>
                <?php foreach ($recipes as $recipe): ?>
                    <div class="row">
                        <div class="column">
                            <img class="recipe-thumbnail" src="../img/<?php echo $recipe['thumbnail']; ?>" />
                        </div>
                        <div class="column"><?php echo $recipe['name']; ?></div>
                        <div class="column"><?php echo number_format($recipe['manufacturingCost'], 2, '.', ''); ?></div>
                        <div class="column">
                            <div class="ingredients-list">
                                <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                                    <div class="ingredient">
                                        <div class="name"><?php echo $ingredient['descripcion']; ?></div>
                                        <div class="quantity"><?php echo $ingredient['cantidad'] . ' ' . $ingredient['medida_pro']; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="column min-width buttons">
                            <a href="eliminar_receta.php?id=<?php echo $recipe['id']; ?>" class="ui-button red">Eliminar</a>
                            <a href="registro_receta.php?id=<?php echo $recipe['id']; ?>" class="ui-button blue">Editar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="ui-box-footer">
            <div class="ui-table">
                <div class="row">
                    <div class="column">Total de recetas: <?php echo $totals['recipes']; ?></div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include(__DIR__ . '/includes/footer.php'); ?>
<script>
</script>
</body>
</html>