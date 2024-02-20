<?php
    require_once(__DIR__ . '/functions.php');

    if (!isset($_SESSION)) {
        session_start();
    }

	if(empty($_SESSION['active'])){
		header('location: ../');
	}

?>

	<style>
		.modal{
			position: fixed;
			width:  100%;
			height: 100vh;
			background: rgba(0,0,0,0.81);
			display: none;
		}

		.bodyModal{
			width: 100%;
			height: 100%;
			display: -webkit-inline-flex;
			display: -moz-inline-flex;
			display: -ms-inline-flex;
			display: -o-inline-flex;
			justify-content: center;
			align-items: center;
		}

		.modal h1{
			color: #0e7250;
			text-transform: uppercase;
			text-align: center;
		}

		.modal h3{
			text-transform: uppercase;
			margin-top: 15px;
			text-align: center;
		}

		.modal .btn_ok, .modal .btn_cancel{
			margin-top: 3rem;
			padding: 1rem;
			border: none;
			border-radius: 0.3rem;
			color: #fff;
			padding: 0.7rem;
			font-size: 1rem;
		}

		.modal .btn_ok{
			background-color: #F93811;
			margin-left: 8rem;
			cursor: pointer;
		}

		.modal .btn_cancel{
			background-color: #11A8F9;
			cursor: pointer;
			margin-left: 2rem;
		}


	</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<header>
		<div class="header">
			
			<h1>DIEGO'S WAFFLES ASSOCIATION</h1>
			<div class="optionsBar">
				<p>Ecuador, <?php echo fechaC(); ?></p>
				<span>|</span>
				<span class="user"> <?php echo $_SESSION['user'].' -'.$_SESSION['rol']. '-'.$_SESSION['email']; ?></span>
				<img class="photouser" src="img/user.png" alt="Usuario">
				<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
		<?php include "nav.php"; ?>
	</header>

	<div class="modal">
		<div class="bodyModal">
			<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">
				<h1 style="font_size: 45pt;" ><br> Anular Factura</h1>
				<h3>¿Realmente desea anular la factura?</h3>
				<button type="submit" class="btn_ok"> Anular</button>
				<button href="#" class="btn_cancel" onclick="coloseModal();"> Cancelar</button>
			</form>
		</div>
	</div>

