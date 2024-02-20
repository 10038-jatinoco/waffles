<?php
	session_start();
	include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de órdenes</title>
</head>

<style>
	#container h1{
		font-size: 35px;
		display: inline-block;
	}

	.btn_new{
		display: inline-block;
		background: #239baa;
		color: #fff;
		padding: 5px 25px;
		border-radius: 4px;
		margin: 20px;
	}

	form{
		background: #fff;
		margin: auto;
		padding: 20px 50px;
		border: 1px solid #d1d1d1;
	}

	input, select{
		display: block;
		width: 100%;
		font-size: 13pt;
		padding: 5px;
		border: 1px solid #85929e;
		border-radius: 5px;
	}


	table{
		border-collapse: collapse;
		font-size: 12pt;
		font-family: 'GothamBook';
		width: 100%;
	}

	table th{
		text-align: left;
		padding: 10px;
		background: #3d7ba8;
		color: #fff;
	}

	table tr:nth-child(odd){
		background: #fff;
	}

	table td{
		padding: 10px;
	}

	.link_edit{
		color: #0ca4ce;
	}

	.link_delete{
		color: #f26b6b;
	}

	.paginador ul{
		padding: 15px;
		list-style: none;
		background: #fff;
		margin-top: 15px;
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: flex;
		justify-content: flex-end;
	}

	.paginador a, .pageSelected{
		color: #428bca;
		border: 1px solid #ddd;
		display: 5px;
		display: inline-block;
		font-size: 14px;
		text-align: center;
		width: 35px;
	}

	.paginador a:hover{
		background: #ddd;
	}

	.pageSelected{
		color: #fff;
		background: #428bca;
		border: 1px solid #428bca;
	}

	.form_search{
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: -o-flex;
		display: flex;
		float: right;
		background: initial;
		padding: 10px;
		border-radius: 10px;
	}

	.form_search .btn_search{
		background: #1faac8;
		color: #fff;
		padding: 0 20px;
		border: 0;
		cursor: pointer;
		margin-left: 10px;
	}

	.form_search_date{
		padding: 10px;
		display: flex;
		justify-content: flex-start;
		align-items: center;
		border-radius: 10px;
		margin: 10px auto;
	}

	.form_search_date label{
		margin: 0 10px;
	}

	.form_search_date input{
		width: auto;
	}

	.form_search_date .btn_view{
		padding: 8px;
	}

	.btn_view{
		background-color: #1faac8;
		border: 0;
		border-radius: 5px;
		cursor: pointer;
		padding: 10px;
		margin: 0 3px;
		color: #fff;
	}

	.btn_anular{
		background-color: #F93811;
		border: 0;
		border-radius: 5px;
		cursor: pointer;
		padding: 10px;
		margin: 0 3px;
		color: #fff;
	}

	.div_acciones{
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: -o-flex;
		display: flex;
		justify-content: center;
	}

	.totalfactura{
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: -o-flex;
		display: flex;
		justify-content: space-between;
	}

	.pagada, .anulada{
		color: #fff;
		background: #60a756;
		text-align: center;
		border-radius: 5px;
		padding: 4px 15px;
	}

	.anulada{
		background: #f36a6a;
	}

	.inactive{
		background-color: #a4a4a4;
		color: #ccc;
		cursor: default;
	}

</style>

<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<h1>Lista de órdenes</h1>
		<a href="nueva_venta.php" class="btn_new">Nueva Orden</a>

		<form action="buscar_venta.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="No. Orden">
			<input type="submit" class="btn_search">
		</form>

		<div>
			<h5>Buscar por fecha</h5>
			<form action="buscar_venta.php" method="get" class="form_search_date">
				<label>De: </label>
				<input type="date" name="fecha_de" id="fecha_de" required>
				<label>A: </label>
				<input type="date" name="fecha_a" id="fecha_a" required>
				<button type="submit" class="btn_view">Buscar</button>
				
			</form>

		<table>
			<tr>
				<th>No.</th>
				<th>Fecha/Hora</th>
				<th>Destinatario</th>
				<th>Emisor</th>
				<th>Estado</th>
				<th>Total de la orden</th>
				<th>Acciones</th>
			</tr>
		<?php

			//paginador
			$sql_registe = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM factura WHERE estatus != 10 ");
			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 10;

			if(empty($_GET['pagina'])){
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde= ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conection, "SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, 
													u.nombre as vendedor, 
													cl.nombre as cliente 
													FROM factura f 
													INNER JOIN usuario u 
													ON f.usuario = u.idusuario 
													INNER JOIN cliente cl 
													ON f.codcliente = cl.idcliente 
													WHERE f.estatus != 10 
													ORDER BY f.fecha DESC LIMIT $desde,$por_pagina");

			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if($result>0){
				while ($data = mysqli_fetch_array($query)){
					if($data["estatus"] == 1){
						$estado = '<span class="pagada">En proceso</span>';
					}else{
						$estado = '<span class="anulada">Anulada</span>';
					}
		?>
			<tr id="row_<?php echo $data["nofactura"]; ?>">
				<td><?php echo $data["nofactura"]; ?></td>
				<td><?php echo $data["fecha"]; ?></td>
				<td><?php echo $data["cliente"]; ?></td>
				<td><?php echo $data["vendedor"]; ?></td>
				<td class="estado"><?php echo $estado; ?></td>
				
				<td class="textright totalfactura"><span>$</span><?php echo $data["totalfactura"]; ?></td>
				<td>
				<div class="div_acciones">
					<div>
						<button class="btn_view view_factura" type="button" cl="<?php echo $data['codcliente']; ?>" f="<?php echo $data['nofactura']; ?>">Reporte</button>
					</div>

				</div>
				</td>				
			</tr>
		<?php
				}
			}
		?>
			
		</table>

		<div class="paginador">
			<ul>
				<?php
					if($pagina != 1){
				?>
					<li><a href="?pagina=<?php echo 1; ?>">|<</a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>"><<</a></li>
				<?php
					}
					for ($i=1; $i <= $total_paginas; $i++){
						if($i==$pagina){
							echo '<li class="pageSelected">'.$i.'</li>';
						}else{
							echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
						}
					}

					if($pagina != $total_paginas){
				?>	
					<li><a href="?pagina=<?php echo $pagina + 1; ?>">>></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?> ">>|</a></li>
				<?php } ?>
			</ul>
		</div>

	</section>
	<script>

		// Anular factura

		$('.anular_factura').click(function(e){
			e.preventDefault();
			var nofactura = $(this).attr('fac');
			var action = 'infoFactura';

			$.ajax({
				url: 'ajax.php',
				type: 'POST',
				async: true,
				data: {action:action,nofactura:nofactura},

				success: function(response){
					if(response != 'error'){
						var info = JSON.parse(response);

						$('.bodyModal').html('<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">'+
							'<h1>Anular Factura</h1>' +
							'<p>¿Realmente desea anular la factura?</p>'+
							'<p><strong>No. '+info.nofactura+'</strong></p>'+
							'<p><strong>Monto. '+info.totalfactura+'</strong></p>'+
							'<p><strong>Fecha. '+info.fecha+'</strong></p>'+
							'<input type="hidden" name="action" value="anularFactura">'+
							'<input type="hidden" name="no_factura" id="no_factura" value=" '+info.nofactura+' " required>'+
							'<div class="alert alertAddProduct"></div'>+
							'<button type="submit" class="btn_ok"> Anular</button>'+
							'<a href="#" class="btn_cancel" onclick="coloseModal();"> Cerrar</a>'+
							'</form>');	
					}
				},
				error: function(error){
					console.log(error);
				}

			});

			$('.modal').fadeIn();
		
		});

		function coloseModal(){
			$('.modal').fadeOut();
		}

		function anularFactura(){
		
			var noFactura = $('#no_factura').val();
			var action = 'anularFactura';

			$.ajax({
				url: 'ajax.php',
				type: "POST",
				async : true,
				data: {action:action,noFactura:noFactura},

				success: function(response){
					if(response == 'error'){
						$('.alertAddProduct').html('<p class="Error al anular"</p>');
					}else{
						$('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
						$('#form_anular_factura .btn_ok').remove();
						$('#row_'+noFactura+'div.factura').html('<button type="button" class="btn_anular inactive" >');
						$('.alertAddProduct').html('<p>Factura anulada.</p>');
					}
				},
				error: function(error){

				}
			});
		}

		// ver factura
		$('.view_factura').click(function(e){
			e.preventDefault();
			var codCliente = $(this).attr('cl');
			var noFactura = $(this).attr('f');
			generarPDF(codCliente,noFactura);
		});

		function generarPDF(cliente, factura){
	    	var ancho = 1000;
	    	var alto = 800;

	    	var x = parseInt((window.screen.width/2) - (ancho/2));
	    	var y = parseInt((window.screen.height/2) - (alto/2));
	    	$url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
	    	window.open($url, "Factura", "left="+x+",top="+y+"height="+alto+"width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
	    }

	</script>

	<?php include "includes/footer.php"; ?>
</body>
</html>