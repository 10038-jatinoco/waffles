<?php

include(dirname(__DIR__) . '/conexion.php');
global $conection;

$orders = [];

$stmt = mysqli_query($conection, "SELECT * FROM ordenes");

if (!$stmt) {
    $error = mysqli_error($conection);
    echo $error;
    exit;
}

while ($row = mysqli_fetch_assoc($stmt)) {
    $stmt2 = mysqli_query($conection, 
        "SELECT r.*, ordenes_recetas.quantity as quantity, ordenes_recetas.quantity * r.price as total
        FROM ordenes_recetas
        LEFT JOIN recetas as r ON r.id = ordenes_recetas.receta_id
        WHERE orden_id = {$row['id']}");
    $recipes = [];

    while ($row2 = mysqli_fetch_assoc($stmt2)) {
        $recipes[] = $row2;
    }

    $row['recipes'] = $recipes;
    $orders[] = $row;
}

?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista de ordenes</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .order-container {
            max-width: 900px;
            margin: 0 auto 20px;
        }

        .ui-table.recipes-added {
            background: #f1f1f1;
            border-radius: 5px;
        }

        .recipes-added a.delete {
            color: #eb4d4b;
        }

        .order-container .recipes > strong {
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>
<body>
<?php include(__DIR__ . '/includes/header.php'); ?>
<main id="container" class="ui-container">
    <?php foreach ($orders as $order): ?>
    <div class="ui-box order-container">
        <h3 class="ui-box-title">Orden #<?php echo str_pad($order['id'], 7, "0", STR_PAD_LEFT) ?></h3>
        <div class="ui-box-content">
            <div><strong>Cliente:</strong> <?php echo $order['customer_name'] ?></div>
            <div><strong>Fecha:</strong> <?php echo $order['created_at'] ?></div>
            <div class="recipes">
                <strong>Recetas:</strong>    
                <div class="ui-table recipes-added minimal small">
                    <div class="row header">
                        <div class="column">Receta</div>
                        <div class="column">precio unitario</div>
                        <div class="column">Cantidad</div>
                        <div class="column min-width">total</div>
                    </div>
                    <?php $subtotal = 0; ?>
                    <?php foreach ($order['recipes'] as $recipe): ?>
                    <div class="row">
                        <div class="column name"><?php echo $recipe['name']; ?></div>
                        <div class="column price">$<?php echo $recipe['price']; ?></div>
                        <div class="column quantity"><?php echo $recipe['quantity']; ?></div>
                        <div class="column total">
                            <strong>$<?php echo $recipe['total']; ?></strong>
                        </div>
                    </div>
                    <?php
                        $subtotal += $recipe['total'];
                    ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="ui-box-footer">
            <strong>Total: </strong>
            <span>$<?php echo $subtotal; ?></span>
        </div>
    </div>
    <?php endforeach; ?>
</main>
<?php include(__DIR__ . '/includes/footer.php'); ?>
<script>
</script>
</body>
</html>