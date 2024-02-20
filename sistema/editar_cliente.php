<?php
	session_start();
	include "../conexion.php";

	if(!empty($_POST)){

		$alert = '';

		if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['nit'])){
			$alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
			include "../conexion.php";

			$idCliente = $_POST['id'];
			$nit = $_POST['nit'];
			$nombre = $_POST['nombre'];
			$telefono = $_POST['telefono'];
			$direccion = $_POST['direccion'];


			$query = mysqli_query($conection, "SELECT * FROM cliente WHERE nit = '$nit' AND idcliente != $idCliente");

			$result = mysqli_fetch_array($query);
			$result = count($result);

			if($result > 0){
				$alert = '<p class="msg_error">La cédula ya está registrada.</p>';
			}else{
				$sql_update = mysqli_query($conection, "UPDATE cliente SET nit = '$nit', nombre='$nombre', telefono='$telefono', direccion='$direccion' WHERE idcliente=$idCliente ");
				
				if($sql_update){
					$alert = '<p class="msg_save">Cliente actualizado correctamente.</p>';
				}else{
					$alert = '<p class="msg_error">Error al actualizar el cliente.</p>';
				}
			}
		}
	}

//mostrar datos
if(empty($_REQUEST['id'])){
	header('Location: lista_clientes.php');
	mysqli_close($conection);
}
$idcliente = $_REQUEST['id'];

$sql = mysqli_query($conection, "SELECT * FROM cliente WHERE idcliente = $idcliente ");

$result_sql = mysqli_num_rows($sql);

if($result_sql == 0){
	header('Location: lista_clientes.php');
}else{
	$option = '';
	while ($data = mysqli_fetch_array($sql)){
		$idcliente = $data['idcliente'];
		$nit = $data['nit'];
		$nombre = $data['nombre'];
		$telefono = $data['telefono'];
		$direccion = $data['direccion'];
	}
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Cliente</title>
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
</style>



<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar cliente</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $idcliente; ?>">
				<label for="nit">Cédula: </label>
				<input type="text" name="nit" id="nit" placeholder="Número de CI" maxlength="10" minlength="10" onkeyup="this.value=Numeros(this.value)" value="<?php echo $nit; ?>">
				<label for="nombre">Nombre: </label>
				<input type="text" name="nombre" id="nombre"placeholder="Nombre completo" onkeyup="this.value=Letras(this.value)" value="<?php echo $nombre; ?>">
				<label for="telefono">Teléfono: </label>
				<input type="text" name="telefono" id="telefono" placeholder="Teléfono" maxlength="10" minlength="10" onkeyup="this.value=Numeros(this.value)" value="<?php echo $telefono; ?>">
				<label for="direccion">Dirección: </label>
				<input type="text" name="direccion" id="direccion" placeholder="Dirección completa" value="<?php echo $direccion; ?>">
				<input type="submit" value="Actualizar cliente" class="btn_save" >
			</form>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>