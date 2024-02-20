		<nav>
			<ul>
				<li><a href="index.php">Inicio</a></li>
				<?php
					if($_SESSION['rol'] == 1){
				?>
				<li class="principal">
					<a href="#">Usuarios</a>
					<ul>
						<li><a href="registro_usuario.php">Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php">Lista de Usuarios</a></li>
					</ul>
				</li>
				<?php } ?>
				<li class="principal">
					<a href="#">Destinatario</a>
					<ul>
						<li><a href="registro_cliente.php">Nuevo Destinatario</a></li>
						<li><a href="lista_clientes.php">Lista de Destinatarios</a></li>
					</ul>
				</li>
				<li class="principal">
				<?php
					if($_SESSION['rol'] == 1){
				?>
					<a href="#">Proveedores</a>
					<ul>
						<li><a href="registro_proveedor.php">Nuevo Proveedor</a></li>
						<li><a href="lista_proveedor.php">Lista de Proveedores</a></li>
					</ul>
				</li>
				<?php } ?>
                <li class="principal">
                    <a href="#">Productos</a>
                    <ul>
                        <?php
                        if($_SESSION['rol'] == 1){
                            ?>
                            <li><a href="registro_producto.php">Nuevo Producto</a></li>
                        <?php } ?>
                        <li><a href="lista_producto.php">Lista de Productos</a></li>
                    </ul>
                </li>
                <li class="principal">
                    <a href="#">Recetas</a>
                    <ul>
                        <?php
                        if($_SESSION['rol'] == 1){
                            ?>
                            <li><a href="registro_receta.php">Nueva Receta</a></li>
                        <?php } ?>
                        <li><a href="lista_recetas.php">Lista de Recetas</a></li>
                    </ul>
                </li>
				<li class="principal">
					<a href="#">Órdenes</a>
					<ul>
						<li><a href="crear_orden.php">Nueva Orden</a></li>
						<li><a href="lista_ordenes.php">Lista de órdenes</a></li>
					</ul>
				</li>
				<!-- <li class="principal">
					<a href="#">Inventario</a>
					<ul>
					<?php
						if($_SESSION['rol']==1){
					?>
						<li><a href="nueva_utilidad.php">Nueva Utilidad</a></li>
					<?php } ?>
						<li><a href="lista_utilidad.php">Lista de Utilidades</a></li>
					</ul>
				</li> -->
			</ul>
		</nav>