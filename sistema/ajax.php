<?php
session_start();
include '../conexion.php';


if(!empty($_POST)){

    if ($_POST['action'] == 'getProducts') {
        $id = intval($_POST['id'] ?? 0);
        $query = mysqli_query($conection, "SELECT * FROM producto " . ($id > 0 ? "WHERE codproducto = $id" : ""));
        mysqli_close($conection);
        $result = mysqli_num_rows($query);
        $products = [];

        if ($result > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $products[] = $row;
            }
        }

        echo json_encode($products, JSON_UNESCAPED_UNICODE);
        exit;
    }

	// Extraer datos del producto

    if($_POST['action'] == 'infoProducto'){
    	$producto_id = $_POST['producto'];

    	$query = mysqli_query($conection, "SELECT codproducto, descripcion, existencia, precio FROM producto WHERE codproducto = $producto_id AND estatus = 1");

    	mysqli_query($conection);

    	$result = mysqli_num_rows($query);
    	if($result > 0){
    		$data = mysqli_fetch_assoc($query);
    		echo json_encode($data, JSON_UNESCAPED_UNICODE);
    		exit;
    	}
    	echo 'error';
    	exit;
    }

	// Buscar cliente
	if($_POST['action'] == 'searchCliente'){
		if(!empty($_POST['cliente'])){
			$nit = $_POST['cliente'];

			$query = mysqli_query($conection, "SELECT * FROM cliente WHERE nit LIKE '$nit' AND estatus = 1 ");
			mysqli_close($conection);
			$result = mysqli_num_rows($query);

			$data = '';

			if($result > 0){
				$data = mysqli_fetch_assoc($query);
			}else{
				$data = 0;
			}
			echo json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		exit;

	}

	// Registro cliente - ventas

	if($_POST['action'] == 'addCliente'){
		$nit = $_POST['nit_cliente'];
		$nombre = $_POST['nom_cliente'];
		$telefono = $_POST['tel_cliente'];
		$direccion = $_POST['dir_cliente'];
		$usuario_id = $_SESSION['idUser'];

		$query_insert = mysqli_query($conection, "INSERT INTO cliente(nit, nombre, telefono, direccion, usuario_id) VALUES ('$nit', '$nombre', '$telefono', '$direccion', '$usuario_id')");

		if($query_insert){
			$codCliente = mysqli_insert_id($conection);
			$msg = $codCliente;
		}else{
			$msg = 'error';
		}
		echo $msg;
		exit;
	}


	// Agregar producto al detalle temporal

	if($_POST['action'] == 'addProductoDetalle'){
		if(empty($_POST['producto']) || empty($_POST['cantidad'])){
			echo 'error';
		}else{
			$codproducto = $_POST['producto'];
			$cantidad = $_POST['cantidad'];
			$token = md5($_SESSION['idUser']);

			$query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
			$result_iva = mysqli_num_rows($query_iva);

			$query_detalle_temp = mysqli_query($conection, "CALL add_detalle_temp($codproducto,$cantidad, '$token')");
			$result = mysqli_num_rows($query_detalle_temp);

			$detalleTabla = '';
			$sub_total = 0;
			$iva = 0;
			$total = 0;
			$arrayData = array();

			if($result > 0){
				if($result_iva > 0){
					$info_iva = mysqli_fetch_assoc($query_iva);
					$iva = $info_iva['iva'];
				}

				while ($data = mysqli_fetch_assoc($query_detalle_temp)){
					$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
					$sub_total = round($sub_total + $precioTotal, 2);
					$total = round($total + $precioTotal, 2);

					$detalleTabla .= '<tr>
	 									<td>'.$data['codproducto'].'</td>
	 									<td colspan="2">'.$data['descripcion'].'</td>
	 									<td class="textcenter">'.$data['cantidad'].'</td>
	 									<td class="textright">'.$data['precio_venta'].'</td>
	 									<td class="textright">'.$precioTotal.'</td>
	 									<td class="">
	 										<a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');">Eliminar</a>
	 									</td>
	 								</tr>';
				}

				$impuesto = round($sub_total * ($iva / 100), 2);
				$tl_sniva = round($sub_total - $impuesto, 2);
				$total = round($tl_sniva + $impuesto, 2);

				$detalleTotales =   '<tr>
	 									<td colspan="5" class="textright">SUBTOTAL Q.</td>
	 									<td class="textright">'.$tl_sniva.'</td>
	 								</tr>
	 								<tr>
	 									<td colspan="5" class="textright">('.$iva.'%)</td>
	 									<td class="textright">'.$impuesto.'</td>
	 								</tr>
	 								<tr>
	 									<td colspan="5" class="textright">TOTAL Q.</td>
	 									<td class="textright">'.$total.'</td>
	 								</tr>';

	 			$arrayData['detalle'] = $detalleTabla;
	 			$arrayData['totales'] = $detalleTotales;

	 			echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
			mysqli_close($conection);
		}
		exit;
	}


	// Extrae datos del detalle_temp

	if($_POST['action'] == 'serchForDetalle'){
		if(empty($_POST['user'])){
			echo 'error';
		}else{
			$token = md5($_SESSION['idUser']);

			$query = mysqli_query($conection, "SELECT tmp.correlativo, tmp.token_user, tmp.cantidad, tmp.precio_venta, p.codproducto, p.descripcion FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto WHERE token_user = '$token' ");

			$result = mysqli_num_rows($query);

			$query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
			$result_iva = mysqli_num_rows($query_iva);

			$detalleTabla = '';
			$sub_total = 0;
			$iva = 0;
			$total = 0;
			$arrayData = array();

			if($result > 0){
				if($result_iva > 0){
					$info_iva = mysqli_fetch_assoc($query_iva);
					$iva = $info_iva['iva'];
				}

				while ($data = mysqli_fetch_assoc($query)){
					$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
					$sub_total = round($sub_total + $precioTotal, 2);
					$total = round($total + $precioTotal, 2);

					$detalleTabla .= '<tr>
	 									<td>'.$data['codproducto'].'</td>
	 									<td colspan="2">'.$data['descripcion'].'</td>
	 									<td class="textcenter">'.$data['cantidad'].'</td>
	 									<td class="textright">'.$data['precio_venta'].'</td>
	 									<td class="textright">'.$precioTotal.'</td>
	 									<td class="">
	 										<a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');">Eliminar</a>
	 									</td>
	 								</tr>';
				}

				$impuesto = round($sub_total * ($iva / 100), 2);
				$tl_sniva = round($sub_total - $impuesto, 2);
				$total = round($tl_sniva + $impuesto, 2);

				$detalleTotales =   '<tr>
	 									<td colspan="5" class="textright">SUBTOTAL Q.</td>
	 									<td class="textright">'.$tl_sniva.'</td>
	 								</tr>
	 								<tr>
	 									<td colspan="5" class="textright">('.$iva.'%)</td>
	 									<td class="textright">'.$impuesto.'</td>
	 								</tr>
	 								<tr>
	 									<td colspan="5" class="textright">TOTAL Q.</td>
	 									<td class="textright">'.$total.'</td>
	 								</tr>';

	 			$arrayData['detalle'] = $detalleTabla;
	 			$arrayData['totales'] = $detalleTotales;

	 			echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
			mysqli_close($conection);
		}
		exit;
	}

	if($_POST['action'] == 'delProductoDetalle'){
		if(empty($_POST['id_detalle'])){
			echo 'error';
		}else{
			$id_detalle = $_POST['id_detalle'];
			$token = md5($_SESSION['idUser']);

			$query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
			$result_iva = mysqli_num_rows($query_iva);

			$query_detalle_temp = mysqli_query($conection, "CALL del_detalle_temp($id_detalle, '$token')");
			$result = mysqli_num_rows($query_detalle_temp);

			$detalleTabla = '';
			$sub_total = 0;
			$iva = 0;
			$total = 0;
			$arrayData = array();

			if($result > 0){
				if($result_iva > 0){
					$info_iva = mysqli_fetch_assoc($query_iva);
					$iva = $info_iva['iva'];
				}

				while ($data = mysqli_fetch_assoc($query_detalle_temp)){
					$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
					$sub_total = round($sub_total + $precioTotal, 2);
					$total = round($total + $precioTotal, 2);

					$detalleTabla .= '<tr>
	 									<td>'.$data['codproducto'].'</td>
	 									<td colspan="2">'.$data['descripcion'].'</td>
	 									<td class="textcenter">'.$data['cantidad'].'</td>
	 									<td class="textright">'.$data['precio_venta'].'</td>
	 									<td class="textright">'.$precioTotal.'</td>
	 									<td class="">
	 										<a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');">Eliminar</a>
	 									</td>
	 								</tr>';
				}

				$impuesto = round($sub_total * ($iva / 100), 2);
				$tl_sniva = round($sub_total - $impuesto, 2);
				$total = round($tl_sniva + $impuesto, 2);

				$detalleTotales =   '<tr>
	 									<td colspan="5" class="textright">SUBTOTAL Q.</td>
	 									<td class="textright">'.$tl_sniva.'</td>
	 								</tr>
	 								<tr>
	 									<td colspan="5" class="textright">('.$iva.'%)</td>
	 									<td class="textright">'.$impuesto.'</td>
	 								</tr>
	 								<tr>
	 									<td colspan="5" class="textright">TOTAL Q.</td>
	 									<td class="textright">'.$total.'</td>
	 								</tr>';

	 			$arrayData['detalle'] = $detalleTabla;
	 			$arrayData['totales'] = $detalleTotales;

	 			echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
			mysqli_close($conection);
		}
		exit;
	}

	// Anular venta
	if($_POST['action'] == 'anularVenta'){
		$token = md5($_SESSION['idUser']);
		$query_del = mysqli_query($conection, "DELETE FROM detalle_temp WHERE token_user = '$token' ");
		mysqli_close($conection);
		if($query_del){
			echo 'ok';
		}else{
			echo 'error';
		}
		exit;
	}

	// Procesar venta
	if($_POST['action'] == 'procesarVenta'){
		if(empty($_POST['codcliente'])){
			$codcliente = 1;
		}else{
			$codcliente = $_POST['codcliente'];
		}

		$token = md5($_SESSION['idUser']);
		$usuario = $_SESSION['idUser'];

		$query = mysqli_query($conection, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
		$result = mysqli_num_rows($query);

		if($result > 0){
			$query_procesar = mysqli_query($conection, "CALL procesar_venta($usuario,$codcliente,'$token')");
			$result_detalle = mysqli_num_rows($query_procesar);

			if($result_detalle > 0){
				$data = mysqli_fetch_assoc($query_procesar);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
		}else{
			echo 'error';
		}
		mysqli_close($conection);
		exit;
	}

	// Info de la factura
	if($_POST['action'] == 'infoFactura'){
		if(!empty($_POST['nofactura'])){

			$nofactura = $_POST['nofactura'];

			$query = mysqli_query($conection, "SELECT * FROM factura WHERE nofactura = '$nofactura' AND estatus = 1 ");
			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if(result > 0){
				$data = mysqli_fetch_assoc($query);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
		echo "error";
		exit;
	}

	if($_POST['action'] == 'anularFactura'){
		if(!empty($_POST['noFactura'])){
			$noFactura = $_POST['noFactura'];

			$query_anular = mysqli_query($conection, "CALL anular_factura($noFactura)");
			mysqli_close($conection);
			$result = mysqli_num_rows($query_anular);
			if($result > 0){
				$data = mysqli_fetch_assoc($query_anular);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
		echo "error";
		exit;
	}



 }

exit;

?>