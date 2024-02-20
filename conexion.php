<?php

	$host = 'localhost';
	$user = 'admin';
	$password ='admin';
	$db = 'facturacion';

	$conection = @mysqli_connect($host, $user, $password, $db);

	if (!$conection) {
		die("Error en la conexión");
	}