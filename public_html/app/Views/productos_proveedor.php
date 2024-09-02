<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="modal fade" id="productosModal" tabindex="-1" role="dialog" aria-labelledby="productosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productosModalLabel">Productos del Proveedor</h5>
                <button type="button" class="btn-close-custom" aria-label="Close" onclick="window.location.href='<?= base_url() ?>'">
                    &times;
                </button>
            </div>
            <div class="modal-body" id="productosModalBody">
            <?php if (empty($productos)): ?>
    <p>No hay productos asociados a este proveedor.</p>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Referencia Producto</th>
                <th>ID Producto Necesidad</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= esc($producto['ref_producto']) ?></td>
                    <td><?= esc($producto['id_producto_necesidad']) ?></td>
                    <td><?= esc($producto['precio']) ?></td>
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

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    var idProveedor = window.location.pathname.split('/').pop();
    if (idProveedor) {
        $.get('<?= base_url('proveedores/getProductos/') ?>' + idProveedor, function(data) {
            $('#productosModalBody').html(data);
        }).fail(function(error) {
            console.error("Error al cargar los productos del proveedor", error);
        });
    }

    $('#productosModal').on('hidden.bs.modal', function(e) {
        window.location.href = '<?= base_url('proveedores/') ?>';
    });

    $('#productosModal').modal('show');
});
</script>

<?= $this->endSection() ?>
