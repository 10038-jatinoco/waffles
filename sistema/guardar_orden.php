<?php
session_start();

include(dirname(__DIR__) . '/conexion.php');
global $conection;

$error = null;

if (empty($_POST['customerName'])) {
    $error = 'No se ha recibido el nombre del cliente';
    include(__DIR__ . '/crear_orden.php');
    exit;
}

if (empty($_POST['recipes']) || !is_array($_POST['recipes'])) {
    $error = 'No se han recibido las recetas';
    include(__DIR__ . '/crear_orden.php');
    exit;
}

$customerName = htmlentities($_POST['customerName']);
$recipes = $_POST['recipes'];
$now = date('Y-m-d H:i:s');
$userId = intval($_SESSION['idUser']);

foreach ($recipes as $recipe) {
    $recipe_id = intval($recipe['id']);
    $quantity = intval($recipe['quantity']);
    
    $stmt = mysqli_query($conection, 
        "SELECT p.codproducto, p.existencia, p.medida_pro, rp.cantidad, p.descripcion
         FROM receta_producto as rp
         LEFT JOIN producto as p ON(p.codproducto = rp.producto_id)
         WHERE rp.receta_id = {$recipe_id}");

    $ingredients = [];
    $rows = mysqli_fetch_all($stmt, MYSQLI_ASSOC);

    foreach ($rows as $ingredient) {
        $newQuantity = floatval($ingredient['existencia']) - (floatval($ingredient['cantidad']) * $quantity);

        if ($newQuantity < 0) {
            $error = "No hay suficientes ingredientes para la receta<br>"
                . "se intento usar {$ingredient['cantidad']} {$ingredient['medida_pro']} de {$ingredient['descripcion']}";
            include(__DIR__ . '/crear_orden.php');
            exit;
        }
    }
}


$stmt = mysqli_prepare($conection, "INSERT INTO ordenes(user_id, customer_name, created_at, updated_at) VALUES(?, ?, ?, ?)");

if (!$stmt) {
    $error = mysqli_error($conection);
    echo $error;
    exit;
}

$stmt->bind_param('isss', $userId, $customerName, $now, $now);
$stmt->execute();
$stmt->close();

$order_id = mysqli_insert_id($conection);

foreach ($recipes as $recipe) {
    $stmt = mysqli_prepare($conection, "INSERT INTO ordenes_recetas(orden_id, receta_id, quantity) VALUES(?, ?, ?)");
    $recipe_id = intval($recipe['id']);
    $quantity = intval($recipe['quantity']);
    $stmt->bind_param('iid', $order_id, $recipe_id, $quantity);
    $stmt->execute();
    $stmt->close();

    $stmt2 = mysqli_query($conection, 
        "SELECT p.codproducto, p.existencia, p.medida_pro, rp.cantidad
         FROM receta_producto as rp
         LEFT JOIN producto as p ON(p.codproducto = rp.producto_id)
         WHERE rp.receta_id = {$recipe_id}");
    $ingredients = [];
    $rows = mysqli_fetch_all($stmt2, MYSQLI_ASSOC);

    foreach ($rows as $ingredient) {
        $stmt3 = mysqli_prepare($conection, "UPDATE producto SET existencia = ? WHERE codproducto = ?");
        $newQuantity = floatval($ingredient['existencia']) - (floatval($ingredient['cantidad']) * $quantity);
        $stmt3->bind_param('di', $newQuantity, $ingredient['codproducto']);
        if (!$stmt3->execute()) {
            $error = mysqli_error($conection);
            echo $error;
            exit;
        }
        $stmt3->close();
    }
}

header('Location: lista_ordenes.php');