<?php
	session_start();
	if($_SESSION['rol'] != 1){
		header("location: ./");
	}
	include "../conexion.php";

	if(!empty($_POST)){

		$alert = '';

		if(empty($_POST['proveedor']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['contacto'])){
			$alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
			include "../conexion.php";

			$idproveedor = $_POST['id'];
			$proveedor = $_POST['proveedor'];
			$contacto = $_POST['contacto'];
			$telefono = $_POST['telefono'];
			$direccion = $_POST['direccion'];

				$sql_update = mysqli_query($conection, "UPDATE proveedor SET proveedor = '$proveedor', contacto='$contacto', telefono='$telefono', direccion='$direccion' WHERE codproveedor=$idproveedor ");
				
				if($sql_update){
					$alert = '<p class="msg_save">Proveedor actualizado correctamente.</p>';
				}else{
					$alert = '<p class="msg_error">Error al actualizar el proveedor.</p>';
				}
		}
	}

//mostrar datos
if(empty($_REQUEST['id'])){
	header('Location: lista_proveedor.php');
	mysqli_close($conection);
}
$idproveedor = $_REQUEST['id'];

$sql = mysqli_query($conection, "SELECT * FROM proveedor WHERE codproveedor = $idproveedor AND estatus=1");

$result_sql = mysqli_num_rows($sql);

if($result_sql == 0){
	header('Location: lista_proveedor.php');
}else{
	$option = '';
	while ($data = mysqli_fetch_array($sql)){
		$idproveedor = $data['codproveedor'];
		$proveedor = $data['proveedor'];
		$contacto = $data['contacto'];
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
	<title>Actualizar Proveedor</title>
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
			<h1>Actualizar proveedor</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<input type="hidden" name="id"  value="<?php echo $idproveedor; ?>">
				<label for="proveedor">Proveedor: </label>
				<input type="text" name="proveedor" id="proveedor" placeholder="Nombre del proveedor" onkeyup="this.value=Letras(this.value)" value="<?php echo $proveedor; ?>">
				<label for="contacto">Contacto: </label>
				<input type="text" name="contacto" id="contacto"placeholder="Nombre completo del contacto" onkeyup="this.value=Letras(this.value)"  value="<?php echo $contacto; ?>">
				<label for="telefono">Teléfono: </label>
				<input type="text" name="telefono" id="telefono" placeholder="Teléfono" maxlength="10" minlength="10" onkeyup="this.value=Numeros(this.value)"  value="<?php echo $telefono; ?>">
				<label for="direccion">Dirección: </label>
				<input type="text" name="direccion" id="direccion" placeholder="Dirección completa"  value="<?php echo $direccion; ?>">
				<input type="submit" value="Actualizar proveedor" class="btn_save">
			</form>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>