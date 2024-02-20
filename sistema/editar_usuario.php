<?php
	session_start();
	if($_SESSION['rol'] != 1){
		header("location: ./");
	}
	include "../conexion.php";

	if(!empty($_POST)){

		$alert = '';

		if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])){
			$alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
			include "../conexion.php";

			$idUsuario = $_POST['id'];
			$nombre = $_POST['nombre'];
			$email = $_POST['correo'];
			$user = $_POST['usuario'];
			$clave = md5($_POST['clave']);
			$rol = $_POST['rol'];


			$query = mysqli_query($conection, "SELECT * FROM usuario WHERE (usuario = '$user' AND idusuario != $idUsuario) OR (correo = '$email' AND idusuario != $idUsuario)");

			$result = mysqli_fetch_array($query);
			$result = count($result);

			if($result > 0){
				$alert = '<p class="msg_error">El correo o el usuario ya existe.</p>';
			}else{
				if(empty($_POST['clave'])){
					$sql_update = mysqli_query($conection, "UPDATE usuario SET nombre = '$nombre', correo='$email', usuario='$user', rol='$rol' WHERE idusuario=$idUsuario ");
				}else{
					$sql_update = mysqli_query($conection, "UPDATE usuario SET nombre = '$nombre', correo='$email', usuario='$user', clave='$clave', rol='$rol' WHERE idusuario=$idUsuario ");
				}

				if($sql_update){
					$alert = '<p class="msg_save">Usuario actualizado correctamente.</p>';
				}else{
					$alert = '<p class="msg_error">Error al actualizar el usuario.</p>';
				}
			}
		}
	}

//mostrar datos
if(empty($_REQUEST['id'])){
	header('Location: lista_usuarios.php');
	mysqli_close($conection);
}
$iduser = $_REQUEST['id'];

$sql = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol FROM usuario u INNER JOIN rol r on u.rol = r.idrol WHERE idusuario = $iduser AND estatus=1");

$result_sql = mysqli_num_rows($sql);

if($result_sql == 0){
	header('Location: lista_usuarios.php');
}else{
	$option = '';
	while ($data = mysqli_fetch_array($sql)){
		$iduser = $data['idusuario'];
		$nombre = $data['nombre'];
		$correo = $data['correo'];
		$usuario = $data['idrol'];
		$rol = $data['rol'];

		if($idrol == 1){
			$option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
		}else if($idrol == 2){
			$option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
		}else if($idrol == 3){
			$option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
		}
	}
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Usuario</title>
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
			<h1>Actualizar usuario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $iduser; ?>">
				<label for="nombre">Nombre: </label>
				<input type="text" name="nombre" placeholder="Nombre completo" value="<?php echo $nombre; ?>">
				<label for=correo>Correo electónico: </label>
				<input type="email" name="correo" id="correo" placeholder="Correo electónico" value="<?php echo $correo; ?>">
				<label for="usuario">Usuario: </label>
				<input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">
				<label for="Clave">Clave</label>
				<input type="password" name="clave" id="clave" placeholder="Clave de acceso">
				<label for="rol">Tipo Usuario</label>

				<?php
					include "../conexion.php";
					$query_rol = mysqli_query($conection, "SELECT * FROM rol");
					mysqli_close($conection);
					$result_rol =mysqli_num_rows($query_rol);

				?>

				<select name="rol" id="rol" class="notItemOne">
					<?php
						echo $option;
						if($result_rol>0){
							while($rol = mysqli_fetch_array($query_rol)){
					?>
							<option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"] ?></option>
					<?php
							}
						}

					?>
				</select>
				<input type="submit" value="Actualizar usuario" class="btn_save">
			</form>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>