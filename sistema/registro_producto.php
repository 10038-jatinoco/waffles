<?php
	
	session_start();
	if($_SESSION['rol'] != 1){
		header("location: ./");
	}
	include "../conexion.php";

	if(!empty($_POST)){

		$alert = '';

		if(empty($_POST['producto']) || empty($_POST['proveedor']) || empty($_POST['precio']) || empty($_POST['cantidad']) || empty($_POST['medida']) || $_POST['precio'] <= 0 || $_POST['cantidad'] <=0){
			$alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{

			$producto = $_POST['producto'];
			$proveedor = $_POST['proveedor'];
			$precio = $_POST['precio'];
			$cantidad = $_POST['cantidad'];
			$medida = $_POST['medida'];
			$usuario_id = $_SESSION['idUser'];

			$foto = $_FILES['foto'];
			$nombre_foto = $foto['name'];
			$type = $foto['type'];
			$url_temp = $foto['tmp_name'];

			$imgProducto = 'img_producto.png';

			if($nombre_foto != ''){
				$destino = 'img/uploads/';
				$img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
				$imgProducto = $img_nombre.'.jpg';
				$src = $destino.$imgProducto;
			}

			$query_insert = mysqli_query($conection, "INSERT INTO producto(descripcion, proveedor, precio,existencia, medida_pro, usuario_id,foto) VALUES('$producto', '$proveedor', '$precio','$cantidad', '$medida', '$usuario_id','$imgProducto')");

			if($query_insert){
				if($nombre_foto != ''){
					move_uploaded_file($url_temp,$src);
				}
				$alert = '<p class="msg_save">Producto guardado correctamente.</p>';
			}else{
				$alert = '<p class="msg_error">Error al guardar producto.</p>';
			}
		}	
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Producto</title>
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
			<h1>Registro Producto</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post" enctype="multipart/form-data">

				<label for="producto">Producto: </label>
				<input type="text" name="producto" id="producto"placeholder="Nombre del producto">

				<label for="proveedor">Proveedor</label>

				<?php

					$query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
					$result_proveedor = mysqli_num_rows($query_proveedor);
					mysqli_close($conection);
				?>

				<select name="proveedor" id="proveedor">
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

				<label for="precio">Precio: </label>
				<input type="number" name="precio" id="precio" step="0.01" placeholder="Precio del producto">
				<label for="cantidad">Cantidad: </label>
				<input type="number" name="cantidad" id="cantidad" placeholder="Stock">

				<label for="medida">Medida</label>
				<select name="medida" id="medida">
					<option value="Kilogramos">Kilogramos</option>
					<option value="Litros">Litros</option>
					<option value="Unidades">Unidades</option>
					<option value="Onzas">Onzas</option>
					<option value="Libras">Libras</option>
				</select>

				<div class="photo">
					<label for="foto">Foto</label>
			        <div class="prevPhoto">
			        <span class="delPhoto notBlock">X</span>
			        <label for="foto"></label>
			        </div>
			        <div class="upimg">
			        <input type="file" name="foto" id="foto" accept="image/png, .jpeg, .jpg, image/gif">
			        </div>
			        <div id="form_alert"></div>
				</div>

				<input type="submit" value="Guardar producto" class="btn_save">

			</form>
		</div>
		
	</section>
	
	<?php include "includes/footer.php"; ?>

	
</body>
</html>