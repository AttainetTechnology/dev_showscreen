<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="modal fade" id="productoModal" tabindex="-1" role="dialog" aria-labelledby="productoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productoModalLabel">Selecciona un producto</h5>
                <a href="<?= base_url('productos_necesidad'); ?>">
                    <button type="button" class="btn-close-custom" aria-label="Close">
                        &times;
                    </button>
                </a>
            </div>
            <div class="modal-body" id="productosModalBody">
                <label for="filtrarFamilia">Filtrar por Familia:</label>
                <select id="filtrarFamilia" class="form-control">
                    <option value="">Todas las Familias</option>
                    <?php foreach ($familias as $familia): ?>
                        <option value="<?= esc($familia['id_familia']) ?>"><?= esc($familia['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto por nombre">
                <br>
                <?php if (empty($productos)): ?>
                    <p>No hay productos disponibles.</p>
                <?php else: ?>
                    <table class="table table-bordered" id="tablaProductos">
                        <thead>
                            <tr>
                                <th>Nombre del Producto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr data-familia="<?= esc($producto['id_familia']) ?>"> <!-- Asigna el ID de la familia -->
                                    <td><?= esc($producto['nombre_producto']) ?></td>
                                    <td>
                                        <?php if ($producto['id_producto'] == $id_producto_venta): ?>
                                            <button type="button" class="btn btn-danger btn-select" data-id="<?= esc($producto['id_producto']) ?>" data-action="deselect">Deseleccionar</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-success btn-select" data-id="<?= esc($producto['id_producto']) ?>" data-action="select">Seleccionar</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Mostrar el modal
        $('#productoModal').modal('show');

        // Filtro de búsqueda de productos por nombre
        $('#buscarProducto').on('keyup', function() {
            filtrarProductos();
        });

        // Filtro por familia de productos
        $('#filtrarFamilia').on('change', function() {
            // Limpiar el campo de búsqueda cuando se selecciona una familia
            $('#buscarProducto').val('');

            // Llamar a la función de filtrado
            filtrarProductos();
        });

        function filtrarProductos() {
            var searchValue = $('#buscarProducto').val().toLowerCase();
            var selectedFamilia = $('#filtrarFamilia').val();

            $('#tablaProductos tbody tr').each(function() {
                var productoText = $(this).text().toLowerCase();
                var productoFamilia = $(this).data('familia'); // Obtener el data-familia de la fila

                // Filtro por familia y búsqueda de texto
                var matchesFamilia = selectedFamilia === "" || selectedFamilia == productoFamilia;
                var matchesSearch = productoText.indexOf(searchValue) > -1;

                // Mostrar u ocultar la fila si coincide con la búsqueda y la familia
                $(this).toggle(matchesFamilia && matchesSearch);
            });
        }

        // Acción para seleccionar o deseleccionar un producto
        $('.btn-select').on('click', function() {
            var productoId = $(this).data('id');
            var action = $(this).data('action');
            var idProductoNecesidad = <?= esc($id_producto) ?>;

            var idProductoVenta = action === 'select' ? productoId : null;

            $.post('<?= base_url('productos_necesidad/actualizarProductoVenta') ?>', {
                id_producto_necesidad: idProductoNecesidad,
                id_producto_venta: idProductoVenta
            }, function(response) {
                if (response.success) {
                    if (action === 'select') {
                        // Deseleccionar todos los botones
                        $('.btn-select').removeClass('btn-danger').addClass('btn-success').text('Seleccionar').data('action', 'select');

                        // Actualizar el botón seleccionado a "Deseleccionar"
                        $('button[data-id="' + productoId + '"]').removeClass('btn-success')
                            .addClass('btn-danger')
                            .text('Deseleccionar')
                            .data('action', 'deselect');
                    } else {
                        // Volver a marcar el botón como "Seleccionar"
                        $('button[data-id="' + productoId + '"]').removeClass('btn-danger')
                            .addClass('btn-success')
                            .text('Seleccionar')
                            .data('action', 'select');
                    }
                } else {
                    alert('Error al realizar la acción.');
                }
            }, 'json');
        });

        // Si se cierra el modal, redirigir a la página de productos necesidad
        $('#productoModal').on('hidden.bs.modal', function() {
            window.location.href = '<?= base_url('productos_necesidad') ?>';
        });
    });
</script>

<?= $this->endSection() ?>