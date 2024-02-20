<?php
	
	session_start();

	include "../conexion.php";

	if(!empty($_POST)){

		$alert = '';

		if(empty($_POST['nit']) || empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])){
			$alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{

			$nit = $_POST['nit'];
			$nombre = $_POST['nombre'];
			$telefono = $_POST['telefono'];
			$direccion = $_POST['direccion'];
			$usuario_id = $_SESSION['idUser'];

			$query = mysqli_query($conection, "SELECT * FROM cliente WHERE nit = '$nit' ");
			$result = mysqli_fetch_array($query);
			
			if($result > 0){
				$alert='<p class="msg_error">El número de cédula ya existe.</p>';
			}else{
				$query_insert = mysqli_query($conection, "INSERT INTO cliente(nit,nombre,telefono,direccion,usuario_id) VALUES('$nit','$nombre','$telefono','$direccion','$usuario_id')");

				if($query_insert){
					$alert = '<p class="msg_save">Cliente guardado correctamente.</p>';
				}else{
					$alert = '<p class="msg_error">Error al guardar cliente.</p>';
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
	<title>Registro Destinatario</title>
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
			<h1>Registro destinatario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for="nit">Cédula: </label>
				<input type="text" name="nit" id="nit" placeholder="Número de CI" maxlength="10" minlength="10" onkeyup="this.value=Numeros(this.value)">
				<label for="nombre">Nombre: </label>
				<input type="text" name="nombre" id="nombre"placeholder="Nombre completo" onkeyup="this.value=Letras(this.value)">
				<label for="telefono">Teléfono: </label>
				<input type="text" name="telefono" id="telefono" placeholder="Teléfono" maxlength="10" minlength="7" onkeyup="this.value=Numeros(this.value)">
				<label for="direccion">Dirección: </label>
				<input type="text" name="direccion" id="direccion" placeholder="Dirección completa">
				<input type="submit" value="Guardar destinatario" class="btn_save">
			</form>
		</div>
		
	</section>
	<script>
		function Numeros(string){//Solo numeros
		    var out = '';
		    var filtro = '1234567890';//Caracteres validos
		    
		    //Recorrer el texto y verificar si el caracter se encuentra en la lista de validos 
		    for (var i=0; i<string.length; i++)
		       if (filtro.indexOf(string.charAt(i)) != -1) 
		             //Se añaden a la salida los caracteres validos
		         out += string.charAt(i);
		    
		    //Retornar valor filtrado
		    return out;
		} 

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
	</script>

	<?php include "includes/footer.php"; ?>

	
</body>
</html>