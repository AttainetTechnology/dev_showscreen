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
                <?php if (empty($productos)): ?>
                    <p>No hay productos disponibles.</p>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre del Producto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Mostrar el modal
        $('#productoModal').modal('show');

        // Acción para seleccionar o deseleccionar un producto
        $('.btn-select').on('click', function() {
            var productoId = $(this).data('id');
            var action = $(this).data('action');
            var idProductoNecesidad = <?= esc($id_producto) ?>; // Id del producto necesidad que estamos editando

            // Definir el producto_venta a actualizar o nullear si se desea deseleccionar
            var idProductoVenta = action === 'select' ? productoId : null;

            $.post('<?= base_url('productos_necesidad/actualizarProductoVenta') ?>', {
                id_producto_necesidad: idProductoNecesidad,
                id_producto_venta: idProductoVenta
            }, function(response) {
                if (response.success) {
                    alert(action === 'select' ? 'Producto seleccionado: ' + productoId : 'Producto deseleccionado');
                    // Cerrar el modal y posiblemente redirigir o recargar la página
                    $('#productoModal').modal('hide');
                } else {
                    alert('Error al realizar la acción.');
                }
            }, 'json');
        });

        // Redirigir al cerrar el modal
        $('#productoModal').on('hidden.bs.modal', function() {
            window.location.href = '<?= base_url('productos_necesidad') ?>';
        });
    });
</script>

<?= $this->endSection() ?>