<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>ATTAINET - INTRANET</title>
	<!-- Attainet partes CSS -->
	<?php $ahora = time(); ?>

	<link href="<?php echo base_url("public/assets/css/partes.css?v='$ahora'"); ?>" rel="stylesheet">

	<!-- Bootstrap Core CSS -->
	<link href="<?php echo base_url("vendor/grocery-crud/css/bootstrap-v5/bootstrap.min.css"); ?>" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
		crossorigin="anonymous"></script>
	<script type="text/javascript">
		function printDiv(divName) {
			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
	</script>

</head>

<body>

	<div id="wrapper">
		<?= $this->include('partials/top_menu_transporte') ?>

		<!-- CARGO EL CONTENIDO DINÁMICO -->
		<div class="container-sm">
			<div class="row">
				<div class="col-12">
					<?= $this->renderSection('content') ?>
				</div>
			</div>
		</div>
		<!-- FIN DEL CONTENIDO DINÁMICO -->
	</div>

</body>

</html>