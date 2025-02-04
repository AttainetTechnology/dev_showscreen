<!DOCTYPE html>
<html lang="es">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>ATTAINET - INTRANET</title>

	<!-- Cargamos Bootstrap v5.02 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
	<!-- Otros Css -->
	<link href="<?php echo base_url("/assets/css/menu_lateral.css"); ?>" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?= base_url('/assets/css/attainet.css') ?>?v=<?= time() ?>">

	<!-- Cargo FAVICON  -->
	<?php

	helper('controlacceso');
	$data = datos_user();
	$favicon = null;

	if ($data !== null && isset($data['favicon'])) {
		$favicon = $data['favicon'];
	}
	?>
	<link rel="icon" href="<?= base_url("assets/uploads/files/" . $id_empresa . "/favicon/" . $favicon) ?>" type="image/gif">
</head>

<body>
	<div id="visor">
		<iframe src="<?php echo base_url("Welcome"); ?>" name="visor" id="visor_frame"></iframe>
	</div>
	<div id="container">
		<div class="row">
			<!-- Creo este visor para cargar las operaciones fake ajax sobre él -->
			<div class="menu_lateral">
				<!-- Muestra el menú -->
				<?= $this->include('partials/menu_lateral') ?>


				<!-- Fin Menú -->
			</div>
			<div class="contenido">
				<?php
				// Cargamos el contenido del Output (GroceryCrud) 
				if (!empty($output)) {
					echo $output;
				}
				// End Grocery CRUD
				// Cargamos la sección CONTENIDO 
				if (!empty($this->renderSection('content'))) {
					echo $this->renderSection('content');
				}
				// Fin sección CONTENIDO 
				?>
				<!-- FIN DEL CONTENIDO DINÁMICO -->
			</div>
		</div>
	</div>
	<!-- Cargo Scripts -->
	<!-- Bootstrap Bundle (JS) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<!-- jQuery (Opcional, requerido por Bootstrap) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>