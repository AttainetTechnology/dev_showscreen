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
    // Comprobamos los fichajes antes de recargar la página
    fetch('" . base_url('Fichar/CerrarFichajesAbiertos') . "', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Comprobación de fichajes completada:', data);
        // Después de comprobar los fichajes, recargamos la página
        window.location = '" . base_url('presentes/' . $nif) . "';
    })
    .catch(error => {
        console.error('Error al comprobar los fichajes:', error);
        // En caso de error, recargamos la página de todas formas
        window.location = '" . base_url('presentes/' . $nif) . "';
    });
}

// Recarga la página cada 30 minutos (1800000 milisegundos)
setTimeout(redireccionarPaginaHora, 1800000);
</script>";
?>
