<?php
	
	session_start();
	if($_SESSION['rol'] != 1){
		header("location: ./");
	}

	include "../conexion.php";

	if(!empty($_POST)){

		$alert = '';

		if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) ||empty($_POST['clave']) || empty($_POST['rol'])){
			$alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{

			$nombre = $_POST['nombre'];
			$email = $_POST['correo'];
			$user = $_POST['usuario'];
			$clave = md5($_POST['clave']);
			$rol = $_POST['rol'];


			$query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email' ");
			$result = mysqli_fetch_array($query);

			if($result > 0){
				$alert = '<p class="msg_error">El correo o el usuario ya existe.</p>';
			}else{
				$query_insert = mysqli_query($conection, "INSERT INTO usuario(nombre,correo,usuario,clave,rol) VALUES('$nombre','$email','$user','$clave','$rol')");

				if($query_insert){
					$alert = '<p class="msg_save">Usuario creado correctamente.</p>';
				}else{
					$alert = '<p class="msg_error">Error al crear el usuario.</p>';
				}
			}
		}
		mysqli_close($conection);
	}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Usuario</title>
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
</style>



<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1>Registro usuario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for="nombre">Nombre: </label>
				<input type="text" name="nombre" placeholder="Nombre completo" onkeyup="this.value=Letras(this.value)">
				<label for=correo>Correo electónico: </label>
				<input type="email" name="correo" id="correo" placeholder="Correo electónico">
				<label for="usuario">Usuario: </label>
				<input type="text" name="usuario" id="usuario" placeholder="Usuario" onkeyup="this.value=Caracteres(this.value)">
				<label for="Clave">Clave</label>
				<input type="password" name="clave" id="clave" placeholder="Clave de acceso">
				<label for="rol">Tipo Usuario</label>

				<?php

					$query_rol = mysqli_query($conection, "SELECT * FROM rol");
					mysqli_close($conection);
					$result_rol = mysqli_num_rows($query_rol);

				?>

				<select name="rol" id="rol">
					<?php
						if($result_rol>0){
							while($rol = mysqli_fetch_array($query_rol)){
					?>
							<option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"] ?></option>
					<?php
							}
						}

					?>
				</select>
				<input type="submit" value="Crear usuario" class="btn_save">
			</form>

	</section>
	<script>
		function Letras(string){//Solo letras
		    var out = '';
		    var filtro = 'qwertyuiopñlkjhgfdsazxcvbnmMNBVCXZÑLKJHGFDSAPOIUYTREWQáéíóúÁÉÍÓÚ ';//Caracteres validos
		    
		    //Recorrer el texto y verificar si el caracter se encuentra en la lista de validos 
		    for (var i=0; i<string.length; i++)
		       if (filtro.indexOf(string.charAt(i)) != -1) 
		             //Se añaden a la salida los caracteres validos
		         out += string.charAt(i);
		    
		    //Retornar valor filtrado
		    return out;
		} 

		function Caracteres(string){//Solo numeros
		    var out = '';
		    var filtro = '1234567890qwertyuiopñlkjhgfdsazxcvbnm-_';//Caracteres validos
		    
		    //Recorrer el texto y verificar si el caracter se encuentra en la lista de validos 
		    for (var i=0; i<string.length; i++)
		       if (filtro.indexOf(string.charAt(i)) != -1) 
		             //Se añaden a la salida los caracteres validos
		         out += string.charAt(i);
		    
		    //Retornar valor filtrado
		    return out;
		} 
	</script>
	<?php include "includes/footer.php"; ?>
</body>
</html>