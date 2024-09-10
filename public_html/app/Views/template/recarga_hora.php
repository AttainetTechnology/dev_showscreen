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

// Recargo la página cada cierto tiempo, añadiendo el NIF en la URL
echo "<script>
function redireccionarPaginaHora() {
    window.location = '" . base_url('presentes/' . $nif) . "';
}

// Recarga la página cada 30 minutos (1800000 milisegundos)
setTimeout(redireccionarPaginaHora, 1800000);
</script>";
?>
