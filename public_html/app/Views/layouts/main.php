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
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


	<!-- Cargamos Bootstrap v5.02 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<!-- Otros Css -->

	<!-- Incluir Select2 CSS y JS -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/menu_lateral.css') ?>?v=<?= time() ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/movil.css') ?>?v=<?= time() ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/custom.css') ?>?v=<?= time() ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/attainet.css') ?>?v=<?= time() ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/layout.css') ?>?v=<?= time() ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/ocultar_botones.css') ?>?v=<?= time() ?>">
	<!-- Cargo FAVICON  -->
	<?php

	helper('controlacceso');
	$data = datos_user();
	$favicon = null;

	if ($data !== null && isset($data['favicon'])) {
		$favicon = $data['favicon'];
	}
	?>
	<link rel="icon" href="<?= base_url("public/assets/uploads/files/" . $favicon) ?>" type="image/gif">
</head>

<body>
	<div id="container">
		<div id="menu_lateral">
			<!-- Muestra el menú -->
			<?= $this->include('partials/menu_lateral') ?>
			<!-- Fin Menú -->
		</div>
		<div id="contenido">
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
	<!-- Cargo Scripts -->

	<!-- Bootstrap Bundle (JS) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Js de Grocery CRUD -->
	<?php
	if (!empty($js_files)) {
		foreach ($js_files as $file) { ?>
			<script src="<?php echo $file; ?>"></script>
	<?php }
	}
	?>

</body>

</html>