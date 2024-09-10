<?php
// Obtener el id_empresa desde la sesión
$id_empresa = session()->get('id');

// Inicializamos el modelo para obtener el NIF de la empresa
$dbConnectionsModel = new \App\Models\DbConnectionsModel();
$nif = $dbConnectionsModel->getNIF($id_empresa);

// Si no se encuentra el NIF, mostramos un error
if ($nif === null) {
    die('No se encontró un NIF para el id_empresa proporcionado');
}

// Recargo la página cada 15 segundos para salir de ausentes, añadiendo el NIF en la URL
echo "<script>
function redireccionarPagina() {
    window.location = '" . base_url('presentes/' . $nif) . "';
}
setTimeout(redireccionarPagina, 15000); // 15 segundos = 15000 milisegundos
</script>";
?>
