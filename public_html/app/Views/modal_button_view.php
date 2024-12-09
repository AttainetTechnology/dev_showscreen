<!-- Enlace o botón que dispara la carga del modal -->
<button type="button" class="btn btn-primary" onclick="abrirModal(<?= $id_producto ?>)">Ver Procesos</button>

<!-- Contenedor donde se cargará el modal -->
<div id="modalContainer">
</div>
<!-- Incluir jQuery si aún no está incluido en tu proyecto -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Script para abrir el modal -->
<script>
    // Variable global para la URL base
    var baseUrl = '<?= base_url() ?>';
    // Función para abrir el modal y cargarlo dinámicamente
    function abrirModal(idProducto) {
        $.ajax({
            url: baseUrl + 'productos/cargarModal/' + idProducto,
            type: 'GET',
            success: function (response) {
                // Cargar el contenido del modal en el contenedor
                $('#modalContainer').html(response);
                // Mostrar el modal
                $('#procesosModal').modal('show');
            },
            error: function () {
                alert('Error al cargar el modal.');
            }
        });
    }
</script>
