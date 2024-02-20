<?php
	session_start();
	if($_SESSION['rol'] != 1){
		header("location: ./");
	}
	include "../conexion.php";

	if(!empty($_POST)){

		if(empty($_POST['idcliente'])){
			header("location: lista_clientes.php");
			mysqli_close($conection);
		}

		$idcliente = $_POST['idcliente'];
		// $query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE idusuario=$idusuario ");
		$query_delete = mysqli_query($conection, "UPDATE cliente SET estatus = 0 WHERE idcliente = $idcliente ");
		mysqli_close($conection);
		if($query_delete){
			header("location: lista_clientes.php");
		}else{
			echo "Error al eliminar";
		}
	}

	if(empty($_REQUEST['id'])){
		header("location: lista_clientes.php");
		mysqli_close($conection);
	}else{
		$idcliente = $_REQUEST['id'];
		$query = mysqli_query($conection, "SELECT * FROM cliente WHERE idcliente = $idcliente ");
		mysqli_close($conection);
		$result = mysqli_num_rows($query);

		if($result > 0){
			while ($data = mysqli_fetch_array($query)){
				$nit = $data['nit'];
				$nombre = $data['nombre'];
			}
		}else{
			header("location: lista_clientes.php");
		}
	}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>ELiminar cliente</title>
</head>

<style>
	form{
		margin: auto;
		padding: 20px 50px;
		border: 0;
	}

	input, select{
		display: block;
		width: 100%;
		font-size: 13pt;
		padding: 5px;
		border: 1px solid #85929e;
		border-radius: 5px;
	}

	.link_delete{
		color: #f26b6b;
	}

	.data_delete{
		text-align: center;
	}

	.data_delete h2{
		font-size: 12pt;
	}

	.data_delete span{
		font-weight:  bold;
		color: #4f72d4;
		font-size: 12pt;
	}

	.btn_cancel, .btn_ok{
		width: 124px;
		background: #478ba2;
		color: #fff;
		display: inline-block;
		padding: 5px;
		border-radius: 5px;
		cursor: pointer;
		margin: 15px;
	}

	.btn_cancel{
		background: #42b343;
	}
</style>


<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Nombre del cliente: <span><?php echo $nombre; ?></span></p>
			<p>Cédula: <span><?php echo $nit; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>">
				<a href="lista_clientes.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>