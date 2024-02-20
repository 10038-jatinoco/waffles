<?php
$alert = "";
session_start();

if(!empty($_SESSION['active'])){
		header('location: sistema/');
}else{
	if(!empty($_POST))
	{
		if(empty($_POST['usuario']) || empty($_POST['clave']))
		{
			$alert = "Ingrese su usuario y su contraseña";
		}else{
            require_once(__DIR__ . "/conexion.php");
            global $conection;

			$user = mysqli_real_escape_string($conection, $_POST['usuario']);
			$pass = md5(mysqli_real_escape_string($conection, $_POST['clave']));

			$query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$pass'");
			mysqli_close($conection);
			$result = mysqli_num_rows($query);

			if($result > 0){
				$data = mysqli_fetch_array($query);
				$_SESSION['active'] = true;
				$_SESSION['idUser'] = $data['idusuario'];
				$_SESSION['nombre'] = $data['nombre'];
				$_SESSION['email'] = $data['correo'];
				$_SESSION['user'] = $data['usuario'];
				$_SESSION['rol'] = $data['rol'];

				header('location: sistema/');
			}else{
				$alert = "El usuario o contraseña son incorrectos";
				session_destroy();
			}

		}
	}
}

?>


<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- <link href="css/estilo.css" rel="stylesheet"> -->
	<title>Login</title>
	<link rel="shortcut icon" href="img/icono.ico">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
</head>


<style>
@charset "utf-8";
/* CSS Document */

body{
	padding: 0;
	margin: 0;
}

.padre{
	height: 100vh;
	width: 100%;
}

.padre #fondo{
	height: 100%;
	width: 100%;
	object-fit: cover;
	position: absolute;
	opacity: 0.65;
}

.hijo{
	height: 100vh;
	width: 100%;
	display: flex;
	align-items: center;
	justify-content: center;
}

.nieto{
	width: 20rem;
	z-index: 10;
	padding: 1em;
	border: 0.5em solid #192328;
    border-radius: 1em;
    background-color: #83779F;
}

.nieto h2{
	text-align: center;
	color: #fff;
	font-size: 2rem;
	font-family: 'monospace', sans-serif;
}

.nieto img{
	height: 7em;
	width: 7em;
	z-index: 20;
	display: flex;
	margin: 0 auto;
}

/*literal el texto de 'usuario' y 'contraseña'*/

.nieto #labelxd, .nieto #labelxd2{
	font-family: 'Orbitron', sans-serif;
	color: #fff;
	font-size: 1.1em;
	letter-spacing: 0.2em;
}

/*cajas de texto de usuario y contrasñea*/

.nieto #name, .nieto #apellido{
    width: 100%;
    padding-bottom: .4em;
    margin-bottom: 0.6em;
    border-radius: 0.3px;
    display: block;
    border: none;
    border-bottom: 3px solid #192328;
    background: transparent;
    font-family: 'monospace', sans-serif;
    font-size: 1rem;
    font-weight: bold;
}

.nieto #name, .nieto #apellido{
	margin-top: 0.5em;
}

.nieto #name:focus, .nieto #apellido:focus {
	background: linear-gradient(to bottom , transparent, #333);
	outline: none;
	border-bottom: 3px solid #fff;
	color: #fff;
	font-size: 1rem;
}

.nieto #apellido{
	letter-spacing: 0.2em;
}

.nieto #apellido:focus{
	letter-spacing: 0.2em;
}


/*boton de ingresar*/

.nieto #ingresar{
  border: none;
  width: 7em;
  height: 3em;
  border-radius: 0.5em;
  background-color: #192328;
  color: #fff;
  cursor: pointer;
  font-family: 'monospace', sans-serif; 
  margin: 1rem auto;
  font-weight: bold;
  display: block;
}


.nieto #ingresar:hover {
    background-color: #fff;
    color: #192328;
}

.nieto #alerta{
	display: flex;
	align-items: center;
	justify-content: center;
	font-weight: bold;
	color: red;
	margin-top: 1rem;
	margin-bottom: 1rem;
	font-family: 'monospace', sans-serif;
}

/*haciendo los tamaños responsivos*/

@media screen and (max-width: 900px){
	
	.nieto{
	height: 34em;
	}

	.nieto img{
		margin-bottom: 2em;
	}

	.nieto #name{
		margin-bottom: 3em;
	}

	.nieto #ingresar{
		margin-top: 3.5em;
		font-size: 1.2em;
	}
}
</style>

<body>
	<div class="padre">
		<img src="img/fondo2.jpg" alt="fondo 1" id="fondo">
		<div class="hijo">
			<div class="nieto">
				<form class="login" action="" method="post">
		        <h2 id="title">INICIAR SESIÓN</h2>
		        <img src="img/usuario.png" alt="usuario 1" id="usuario">
		        <label for="name" id="labelxd">Usuario:</label>
		        <input type="text" id="name" name="usuario">
		        <label for="name" id="labelxd2">Contraseña:</label>
		        <input type="password" id="apellido" name="clave">
		        <label class="alert" id="alerta"><?php echo isset($alert) ? $alert : '';  ?></label>
		        <input type="submit" value="INGRESAR" id="ingresar">
	    	</form>
			</div>
		</div>
	</div>
</body>
</html>