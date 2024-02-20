<?php
	session_start();
	include "../conexion.php";
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<?php include "includes/scripts.php"; ?>
	<title>Nueva venta</title>
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

	.tbl_venta{
		border-collapse: collapse;
		font-size: 12pt;
		font-family: 'GothamBook';
		width: 100%;
		max-width: 900px;
		margin: auto;
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
		color: #fff;
		border-radius: 3px;
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


	.btn_new{
		display: inline-block;
		background: #239baa;
		color: #fff;
		padding: 5px 25px;
		border-radius: 4px;
		margin: 20px;
	}

	.btn_ok{
		display: inline-block;
		background: #F94723;
		color: #fff;
		padding: 5px 25px;
		border-radius: 4px;
		margin: 20px;
	}

	.datos_cliente, .datos_venta, .title_page{
	width: 100%;
	max-width: 900px;
	margin: auto;
	margin-bottom: 20px;
}
#detalle_venta tr{
	background-color: #FFF !important;
}
#detalle_venta td{
	border-bottom: 1px solid #CCC;
}
.datos{
	background-color: #e3ecef;
	display: -webkit-flex;
	display: -moz-flex;
	display: -ms-flex;
	display: -o-flex;
	display: flex;
	display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    border: 2px solid #78909C;
    padding: 10px;
    border-radius: 10px;
    margin-top: 10px;
}
.action_cliente{
	display: -webkit-flex;
	display: -moz-flex;
	display: -ms-flex;
	display: -o-flex;
	display: flex;
	align-items: center;
}

.datos label{
	margin: 5px auto;
}
.wd20{
	width: 20%;
}
.wd25{
	width: 25%;
}
.wd30{
	width: 30%;
}
.wd40{
	width: 40%;
}
.wd60{
	width: 60%;
}
.wd100{
	width: 100%;
}
/*#div_registro_cliente{
	display: none;
	text-align: center;
}

#add_product_venta{
	display: none;
}*/

#div_registro_cliente, #add_product_venta{
	display: none;
}

.displayN{
	display: none;
}

.tbl_venta tfoot td{
	font-weight: bold;
}
.textright{
	text-align: right;
}
.textcenter{
	text-align: center;
}
.textleft{
	text-align: left;
}
.btn_anular{
	background-color: #f36a6a;
	border: 0;
	border-radius: 5px;
	cursor: pointer;
	padding: 10px;
	margin: 0 3px;
	color: #FFF;
}
</style>

<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="title_page">
			<h1>Nueva Venta</h1>
		</div>
		<div class="datos_cliente">
			<div class="action_cliente">
				<h4>Datos del Destinatario</h4>
				<a href="#" class="btn_new btn_new_cliente">Nuevo Destinatario</a>
			</div>
		<form name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
			<input type="hidden" name="action" value="addCliente">
			<input type="hidden" name="idcliente" id="idcliente" value="" required>
			<div class="wd30">
				<label>Cédula: </label>
				<input type="text" name="nit_cliente" id="nit_cliente" maxlength="10" minlength="10" onkeyup="this.value=Numeros(this.value)">
			</div>
			<div class="wd30">
				<label>Nombre </label>
				<input type="text" name="nom_cliente" id="nom_cliente" onkeyup="this.value=Letras(this.value)" disabled required >
			</div>
			<div class="wd30">
				<label>Teléfono </label>
				<input type="text" name="tel_cliente" id="tel_cliente" onkeyup="this.value=Numeros(this.value)" disabled required>
			</div>
			<div class="wd100">
				<label>Dirección: </label>
				<input type="text" name="dir_cliente" id="dir_cliente" disabled required>
			</div>
			<div id="div_registro_cliente" class="wd100">
				<button type="submit" class="btn_save">Guardar</button>
			</div>
		</form>
		</div>

		<div class="datos_venta">
			<h4>Datos de la orden</h4>
			<div class="datos">
				<div class="wd50">
					<label><strong>Emisor</strong></label>
					<p><?php echo $_SESSION['nombre']; ?></p>
				</div>
				<div class="wd50">
				<label>Acciones</label>
					<div id="acciones_venta">
					<a href="#" class="btn_ok textcenter" id="btn_anular_venta">Anular</a>
					<a href="#" class="btn_new textcenter" id="btn_facturar_venta" style="display: none;">Procesar</a>
					</div>
				</div>
			</div>
		</div>

	<table class="tbl_venta">
		<thead>
			<tr>
				<th width="100px">Código</th>
				<th>Descripción</th>
				<th>Stock</th>
				<th width="100px">Cantidad</th>
				<th class="textright">Precio</th>
				<th class="textright">Precio Total</th>
				<th>Acción</th>
			</tr>
			<tr>
				<td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
				<td id="txt_descripcion">-</td>
				<td id="txt_existencia">-</td>
				<td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
				<td id="txt_precio" class="textright">0.00</td>
				<td id="txt_precio_total" class="textright">0.00</td>
				<td><a href="#" id="add_product_venta" class="link_add">Agregar</a></td>
			</tr>
			<tr>
				<th>Código</th>
				<th colspan="2">Descripción</th>
				<th>Cantidad</th>
				<th class="textright">Precio</th>
				<th class="textright">Precio Total</th>
				<th>Acción</th>
			</tr>
		</thead>
		<tbody id="detalle_venta">
			<!-- contenido AJAX -->

		</tbody>
		<tfoot id="detalle_totales">
			<!-- contenido AJAX -->
		</tfoot>
	</table>

	<script>

		$(document).ready(function(){
	    	var usuarioid = '<?php echo $_SESSION['idUser']; ?>';
	    	serchForDetalle(usuarioid);
    	});

		$('.btn_new_cliente').click(function(e){
        e.preventDefault();
        $('#nom_cliente').removeAttr('disabled');
        $('#tel_cliente').removeAttr('disabled');
        $('#dir_cliente').removeAttr('disabled');

        $('#div_registro_cliente').slideDown();
    	});

    	$('#nit_cliente').keyup(function(e){
    		e.preventDefault();

    		var cl = $(this).val();
    		var action = 'searchCliente';

    		$.ajax({
    			url: 'ajax.php',
    			type: "POST",
    			async : true,
    			data: {action:action,cliente:cl},

    			success: function(response){
 
    				if(response == 0){
    					$('#idcliente').val('');
    					$('#nom_cliente').val('');
    					$('#tel_cliente').val('');
    					$('#dir_cliente').val('');
    					$('.btn_new_cliente').slideDown();
    				}else{
    					var data = $.parseJSON(response);
    					$('#idcliente').val(data.idcliente);
    					$('#nom_cliente').val(data.nombre);
    					$('#tel_cliente').val(data.telefono);
    					$('#dir_cliente').val(data.direccion);

    					$('.btn_new_cliente').slideUp();

    					$('#nom_cliente').attr('disabled', 'disabled');
    					$('#tel_cliente').attr('disabled', 'disabled');
    					$('#dir_cliente').attr('disabled', 'disabled');

    					$('#div_registro_cliente').slideUp();
    				}
    			},
    			error: function(error){
    			}
    		});
    	});

    	// Crear Cliente - Ventas
    	$('#form_new_cliente_venta').submit(function(e){
    		e.preventDefault();
    		$.ajax({
    			url: 'ajax.php',
    			type: "POST",
    			async : true,
    			data: $('#form_new_cliente_venta').serialize(),

    			success: function(response){
 					if(response != 'error'){
 						$('#idcliente').val(response);
 						$('#nom_cliente').attr('disabled', 'disabled');
 						$('#tel_cliente').attr('disabled', 'disabled');
 						$('#dir_cliente').attr('disabled', 'disabled');

 						$('.btn_new_cliente').slideUp();
 						$('#div_registro_cliente').slideUp();
 					}
    			},
    			error: function(error){
    			}
    		});
    	});


    	// Buscar Producto
    	$('#txt_cod_producto').keyup(function(e){
    		e.preventDefault();

    		var producto = $(this).val();
    		var action = 'infoProducto';

    		if(producto != ''){

    		$.ajax({
    			url: 'ajax.php',
    			type: "POST",
    			async : true,
    			data: {action:action,producto:producto},

    			success: function(response){
 					if(response != 'error'){
 						var info = JSON.parse(response);
 						$('#txt_descripcion').html(info.descripcion);
 						$('#txt_existencia').html(info.existencia);
 						$('#txt_cant_producto').val('1');
 						$('#txt_precio').html(info.precio);
 						$('#txt_precio_total').html(info.precio);

 						$('#txt_cant_producto').removeAttr('disabled');
 						$('#add_product_venta').slideDown();
 					}else{
 						$('#txt_descripcion').html('-');
 						$('#txt_existencia').html('-');
 						$('txt_cant_producto').val('0');
 						$('#txt_precio').html('0.00');
 						$('#txt_precio_total').html('0.00');

 						$('#txt_cant_producto').attr('disabled', 'disabled');
 						$('#add_product_venta').slideUp();
 					}
    			},
    			error: function(error){
    			}
    		});
    		}
    	});

    	// Validar cantidad del producto antes de agregar

    	$('#txt_cant_producto').keyup(function(e){
    		e.preventDefault();
    		var precio_total = $(this).val() * $('#txt_precio').html();
    		var existencia = parseInt($('#txt_existencia').html());

    		$('#txt_precio_total').html(precio_total);

    		if(   ($(this).val() < 1 || isNaN($(this).val()))  || ($(this).val() > existencia) ){
    			$('#add_product_venta').slideUp();
    		}else{
    			$('#add_product_venta').slideDown();
    		}
    	});

    	// Agregar producto al detalle
    	$('#add_product_venta').click(function(e){
    		e.preventDefault();

    		if($('#txt_cant_producto').val() > 0){
    			var codproducto = $('#txt_cod_producto').val();
    			var cantidad = $('#txt_cant_producto').val();
    			var action = 'addProductoDetalle';

    			$.ajax({
    				url: 'ajax.php',
    				type: "POST",
    				async : true,
    				data: {action:action,producto:codproducto,cantidad:cantidad},

    				success: function(response){
    					if(response != 'error'){
    						var info = JSON.parse(response);
    						$('#detalle_venta').html(info.detalle);
    						$('#detalle_totales').html(info.totales);

    						$('#txt_cod_producto').val('');
    						$('#txt_descripcion').html('-');
    						$('#txt_existencia').html('-');
    						$('#txt_cant_producto').val('0');
    						$('#txt_precio').html('0.00');
    						$('#txt_precio_total').html('0.00');

    						$('#txt_cant_producto').attr('disabled', 'disabled');

    						$('#add_product_venta').slideUp();
    					}else{
    						console.log('no data');
    					}
    					viewProcesar();
    				},
    				error: function(error){

    				}
    			});
    		}
    	});

	    function del_product_detalle(correlativo){
	    	var action = 'delProductoDetalle';
			var id_detalle = correlativo;

			$.ajax({
	    		url: 'ajax.php',
	    		type: "POST",
	    		async : true,
	    		data: {action:action,id_detalle:id_detalle},

	    		success: function(response){
	    			if(response != 'error'){
	    				var info = JSON.parse(response);
	    				$('#detalle_venta').html(info.detalle);
	    				$('#detalle_totales').html(info.totales);

	    				$('#txt_cod_producto').val('');
	    				$('#txt_descripcion').html('-');
	    				$('#txt_existencia').html('-');
	    				$('#txt_cant_producto').val('0');
	    				$('#txt_precio').html('0.00');
	    				$('#txt_precio_total').html('0.00');

	    				$('#txt_cant_producto').attr('disabled','disabled');

	    				$('#add_product_venta').slideUp();

	    			}else{
	    				$('#detalle_venta').html('');
	    				$('#detalle_totales').html('');
	    			}
	    			viewProcesar();
	    		},
	    		error: function(error){
	    		}
	    	});
	    }

	    //Anular venta
	    $('#btn_anular_venta').click(function(e){
	    	e.preventDefault();

	    	var rows = $('#detalle_venta tr').length;
	    	if(rows > 0){
	    		var action = 'anularVenta';

	    		$.ajax({
	    			url: 'ajax.php',
	    			type: "POST",
	    			async : true,
	    			data: {action:action},

	    			success: function(response){
	    				if(response != 'error'){
	    					location.reload();
	    				}
	    			},
	    			error: function(error){
	    			}
	    		});
	    	}
	    });

	    // Facturar venta
	    $('#btn_facturar_venta').click(function(e){
	    	e.preventDefault();

	    	var rows = $('#detalle_venta tr').length;
	    	if(rows > 0){
	    		var action = 'procesarVenta';
	    		var codcliente = $('#idcliente').val();

	    		$.ajax({
	    			url: 'ajax.php',
	    			type: "POST",
	    			async : true,
	    			data: {action:action,codcliente:codcliente},

	    			success: function(response){
	    				if(response != 'error'){
	    					var info = JSON.parse(response);
	    					// console.log(info);

	    					generarPDF(info.codcliente,info.nofactura);
	    					location.reload();
	    				}else{
	    					console.log('no data');
	    				}
	    			},
	    			error: function(error){
	    			}
	    		});
	    	}
	    });

	    // Generar PDF

	    function generarPDF(cliente, factura){
	    	var ancho = 1000;
	    	var alto = 800;

	    	var x = parseInt((window.screen.width/2) - (ancho/2));
	    	var y = parseInt((window.screen.height/2) - (alto/2));
	    	$url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
	    	window.open($url, "Factura", "left="+x+",top="+y+"height="+alto+"width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
	    }


	    function viewProcesar(){
	    	if($('#detalle_venta tr').length > 0){
	    		$('#btn_facturar_venta').show();
	    	}else{
	    		$('#btn_facturar_venta').hide();
	    	}
	    }

		function serchForDetalle(id){
			var action = 'serchForDetalle';
			var user = id;

			$.ajax({
	    		url: 'ajax.php',
	    		type: "POST",
	    		async : true,
	    		data: {action:action, user:user},

	    		success: function(response){
	    			if(response != 'error'){
	    				var info = JSON.parse(response);
	    				$('#detalle_venta').html(info.detalle);
	    				$('#detalle_totales').html(info.totales);
	    			}else{
	    				console.log('no data');
	    			}
	    			viewProcesar();	
	    		},
	    		error: function(error){
	    		}
	    	});
		}

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

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>