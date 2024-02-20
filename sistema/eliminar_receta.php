<?php

session_start();

if (!isset($_SESSION['rol'])) {
    header('location: ../index.php');
} else if ($_SESSION['rol'] != 1) {
    header('location: ../index.php');
}

$id = intval($_GET['id']);

if (empty($id)) {
    header('location: lista_recetas.php');
}

include "../conexion.php";

$query_delete = mysqli_query($conection, "DELETE FROM receta_producto WHERE receta_id = $id");
$query_delete = mysqli_query($conection, "DELETE FROM recetas WHERE id = $id");

header('location: lista_recetas.php');