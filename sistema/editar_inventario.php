<?php
	
	session_start();
	if($_SESSION['rol'] != 1){
		header("location: ./");
	}
	include "../conexion.php";

	if(!empty($_POST)){

		$alert = '';

		if(empty($_POST['producto']) || empty($_POST['proveedor']) || empty($_POST['cantidad']) || $_POST['cantidad'] <= 0 || empty($_POST['id'])){
			$alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{

			$codInventario = $_POST['id'];
			$proveedor = $_POST['proveedor'];
			$nombre_inventario = $_POST['producto'];
			$cantidad_inventario = $_POST['cantidad'];
			$medida_inv = $_POST['medida'];

			$query_update = mysqli_query($conection, "UPDATE inventario SET proveedor = $proveedor, nombre_inventario = '$nombre_inventario', cantidad_inventario = $cantidad_inventario, medida_inv = '$medida_inv' WHERE cod_inventario = $codInventario ");

			if($query_update){
				$alert = '<p class="msg_save">Materia prima actualizada correctamente.</p>';
			}else{
				$alert = '<p class="msg_error">Error al actualizar materia prima.</p>';
			}
		}	
	}

	//validar producto
	if(empty($_REQUEST['id'])){
		header("location: lista_utilidad.php");
	}else{
		$id_inventario = $_REQUEST['id'];
		if(!is_numeric($id_inventario)){
			header("location: lista_utilidad.php");
		}

		$query_utilidad = mysqli_query($conection, "SELECT p.cod_inventario, p.nombre_inventario, p.cantidad_inventario, p.medida_inv, pr.proveedor FROM inventario p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor WHERE p.cod_inventario = $id_inventario AND p.estatus = 1");
		$result_utilidad = mysqli_num_rows($query_utilidad);

		if($result_utilidad > 0){
			$data_utilidad = mysqli_fetch_assoc($query_utilidad);
		}else{
			header("location: lista_utilidad.php");
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Inventario</title>
</head>

<style>
	.form_register{
		width: 450px;
		margin: auto;
	}

	.form_register h1{
		color: #3c93b0;
	}

	hr{
		border: 0;
		background: #ccc;
		height: 1px;
		margin: 10px 0;
		display: block;
	}

	form{
		background: #fff;
		margin: auto;
		padding: 20px 50px;
		border: 1px solid #d1d1d1;
	}

	label{
		display: block;
		font-size: 12pt;
		font-family: 'GothamBook';
		margin: 15px auto 5px auto;
	}

	input, select{
		display: block;
		width: 100%;
		font-size: 13pt;
		padding: 5px;
		border: 1px solid #85929e;
		border-radius: 5px;
	}

	.notItemOne option:first-child{
		display: none;
	}

	.btn_save{
		font-size: 12pt;
		background: #12a4c6;
		padding: 10px;
		letter-spacing: 1px;
		border: 0;
		cursor: pointer;
		margin: 15px auto;
	}

	.alert{
		width: 100%;
		background: #66e07d66;
		border-radius: 6px;
		margin: 20px auto;
	}

	.msg_error{
		color: #e65656;
	}

	.msg_save{
		color: #126e00;
	}

	.alert p{
		padding: 10px;
	}

	.prevPhoto {
    display: flex;
    justify-content: space-between;
    width: 160px;
    height: 150px;
    border: 1px solid #CCC;
    position: relative;
    cursor: pointer;
    background: url(../images/uploads/user.png);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    margin: auto;}

	.prevPhoto label{
		cursor: pointer;
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 2;}

	.prevPhoto img{
		width: 100%;
		height: 100%;}

	.upimg, .notBlock{
		display: none !important;}

	.errorArchivo{
		font-size: 16px;
		font-family: arial;
		color: #cc0000;
		text-align: center;
		font-weight: bold; 
		margin-top: 10px;}

	.delPhoto{
		color: #FFF;
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: -o-flex;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 50%;
		width: 25px;
		height: 25px;
		background: red;
		position: absolute;
		right: -10px;
		top: -10px;
		z-index: 10;}

	#tbl_list_productos img{
		width: 50px;}

	.imgProductoDelete{
		width: 175px;}
</style>

<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar Inventario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post" enctype="multipart/form-data">

			<label for="proveedor">Proveedor</label>

				<?php

					$query_proveedor = mysqli_query($conection, "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
					$result_proveedor = mysqli_num_rows($query_proveedor);
					mysqli_close($conection);
				?>

				<select name="proveedor" id="proveedor" class="notItemOne">
					<option value="<?php echo $data_utilidad['codproveedor']; ?>" selected><?php echo $data_utilidad['proveedor']; ?></option>
				<?php

					if($result_proveedor > 0){
						while($proveedor = mysqli_fetch_array($query_proveedor)){
				?>
					<option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']; ?>
					</option>
				<?php
						}
					}
				?>

				</select>

				<input type="hidden" name="id" value="<?php echo $data_utilidad['cod_inventario']; ?>">
				<label for="producto">Nombre utilidad: </label>
				<input type="text" name="producto" id="producto" placeholder="Nombre del producto" value="<?php echo $data_utilidad['nombre_inventario']; ?>">
				<label for="cantidad">Cantidad: </label>
				<input type="number" name="cantidad" id="cantidad" placeholder="Stock" value="<?php echo $data_utilidad['cantidad_inventario']; ?>">

				<label for="medida">Medida</label>
				<select name="medida" id="medida" class="notItemOne">
					<option value="<?php echo $data_utilidad['medida_inv']; ?>" selected><?php echo $data_utilidad['medida_inv']; ?></option>
					<option value="Kilogramos">Kilogramos</option>
					<option value="Litros">Litros</option>
					<option value="Unidades">Unidades</option>
				</select>

				<input type="submit" value="Actualizar Utilidad" class="btn_save">

			</form>
		</div>
		
	</section>
	
	<?php include "includes/footer.php"; ?>

	
</body>
</html>