<?php

session_start();

include(dirname(__DIR__) . '/conexion.php');
global $conection;

$id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

if (empty($_POST['name'])) {
    $error = 'no se ha recibido el nombre';
    include(__DIR__ . '/registro_receta.php');
    exit;
}

$nothumbnail = false;

if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] == UPLOAD_ERR_NO_FILE) {
    $nothumbnail = true;
}

if (empty($_POST['ingredients']) || !is_array($_POST['ingredients'])) {
    $error = 'No se han ingresado los ingredientes';
    include(__DIR__ . '/registro_receta.php');
    exit;
}

$name = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
$thumbnail = $_FILES['thumbnail'];
$ingredients = $_POST['ingredients'];
$user_id = intval($_SESSION['idUser']);
$now = date('Y-m-d H:i:s');

$ingredientsIds = [];
$ingredientsRel = [];

foreach ($ingredients as $ingredient) {
    $ingredientsIds[] = $ingredient['id'];
    $ingredientsRel[$ingredient['id']] = $ingredient;
}

$stmt2 = mysqli_query($conection, "SELECT codproducto, precio FROM producto WHERE codproducto IN (" . implode(',', $ingredientsIds) . ")");;

if (!$stmt2) {
    die(mysqli_error($conection));
}

$result = $stmt2->fetch_all(MYSQLI_ASSOC);
$price = 0.0;

foreach ($result as $row) {
    $ingredient = $ingredientsRel[$row['codproducto']];
    $unitPrice = floatval($row['precio']);
    $quantity = floatval($ingredient['quantity']);
    $price += ($unitPrice * $quantity);
}

$product = [
    'descripcion' => $name,
    'precio' => $price,
    'ingredientes' => $ingredients
];

$filename = '';

if (!$nothumbnail) {
    $product['thumbnail'] = $thumbnail['name'];

    $target_dir = dirname(__DIR__) . '/img/';
    $ext = pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
    $filename = 'recipe_' . time() . '.' . $ext;
    $target_file = $target_dir . $filename;

    if (!move_uploaded_file($thumbnail['tmp_name'], $target_file)) {
        echo json_encode(['error' => 'No se ha podido guardar la imagen']);
        exit;
    }
}

if ($id == null) {
    $stmt = mysqli_prepare($conection, "INSERT INTO recetas(user_id, name, price, thumbnail, created_at, updated_at) VALUES(?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        $error = mysqli_error($conection);
        echo $error;
        exit;
    }

    $stmt->bind_param('isdsss', $user_id, $name, $price, $filename, $now, $now);
    $stmt->execute();
    $stmt->close();

    $recipe_id = mysqli_insert_id($conection);

    foreach ($ingredients as $ingredient) {
        $stmt = mysqli_prepare($conection, "INSERT INTO receta_producto(receta_id, producto_id, cantidad) VALUES(?, ?, ?)");
        $quantity = floatval($ingredient['quantity']);
        $stmt->bind_param('iid', $recipe_id, $ingredient['id'], $quantity);
        $stmt->execute();
        $stmt->close();
    }

} else {
    // delete old image
    $stmt = mysqli_prepare($conection, "SELECT thumbnail FROM recetas WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    $old_image = $row['thumbnail'];
    $old_image_path = dirname(__DIR__) . '/img/' . $old_image;
    unlink($old_image_path);


    $stmt = mysqli_prepare($conection, "UPDATE recetas SET name = ?, price = ?, thumbnail = ?, updated_at = ? WHERE id = ?");
    $stmt->bind_param('sdssi', $name, $price, $filename, $now, $id);
    $stmt->execute();
    $stmt->close();

    $stmt = mysqli_prepare($conection, "DELETE FROM receta_producto WHERE receta_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    foreach ($ingredients as $ingredient) {
        $stmt = mysqli_prepare($conection, "INSERT INTO receta_producto(receta_id, producto_id, cantidad) VALUES(?, ?, ?)");
        $quantity = floatval($ingredient['quantity']);
        $stmt->bind_param('iid', $id, $ingredient['id'], $quantity);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: lista_recetas.php');