<div class="cabecera">
	<div class="pestanas-navegacion">
</div>
<div class="hora">
	<script>
	function startTime() {
	  const today = new Date();
	  let h = today.getHours();
	  let m = today.getMinutes();
	  let s = today.getSeconds();
	  m = checkTime(m);
	  s = checkTime(s);
	  document.getElementById('txt').innerHTML =  h + ":" + m + ":" + s;
	  setTimeout(startTime, 1000);
	}
	
	function checkTime(i) {
	  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
	  return i;
	}
	</script>
	<div id="txt"></div>
</div>
<div class="logo">
	<!-- Cargo FAVICON  -->
		<img src="<?php
		$session = session();
		$nif = $session->get('NIF'); 
		if ($nif !== null) {
			// Carga el modelo de la base de datos
			$db = \Config\Database::connect();
			// Realiza la consulta a la base de datos
			$query = $db->table('dbconnections')->getWhere(['NIF' => $nif]);

			$result = $query->getRow();
			$logo_empresa = $result->logo_empresa;
			echo base_url('public/assets/uploads/files/' . $logo_empresa);
		}
		?>" class="logo_app">

</div>
</div>

<?php 
/* Oculto las alertas
if (session('error')){?>
	<div class="alert-danger" role="alert">
		<?php 
		echo session('error');
		?>
	</div>
<? }?>
<?php if (session('exito')){?>
	<div class="alert-success" role="alert">
		<?php 
		echo session('exito');
		?>
	</div>
<? }
*/
?>