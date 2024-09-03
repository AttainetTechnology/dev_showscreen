<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="modal fade" id="productosModal" tabindex="-1" role="dialog" aria-labelledby="productosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productosModalLabel">Productos de <?= esc($nombre_proveedor) ?></h5>
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
                                <th>Nombre del Producto</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= esc($producto['ref_producto']) ?></td>
                                    <td><?= esc($producto['nombre_producto']) ?></td>
                                    <td><?= esc($producto['precio']) ?></td>
                                    <td>
                                        <!-- Formulario para eliminar el producto -->
                                        <form action="<?= base_url('proveedores/eliminarProducto') ?>" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                            <input type="hidden" name="id_proveedor" value="<?= esc($id_proveedor) ?>">
                                            <input type="hidden" name="id_producto_necesidad" value="<?= esc($producto['id_producto_necesidad']) ?>">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <!-- Añadir Productos -->
                <h5 class="mt-4">Añadir Productos</h5>
                <form action="<?= base_url('proveedores/agregarProducto') ?>" method="post">
                    <input type="hidden" name="id_proveedor" value="<?= esc($id_proveedor) ?>">

                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <select name="id_producto_necesidad" id="producto" class="form-control" <?= empty($productos_necesidad) ? 'disabled' : '' ?>>
                            <option value="" disabled selected>Selecciona producto</option>
                            <?php if (empty($productos_necesidad)): ?>
                                <option value="">No hay productos para añadir</option>
                            <?php else: ?>
                                <?php foreach ($productos_necesidad as $producto): ?>
                                    <option value="<?= esc($producto['id_producto']) ?>"><?= esc($producto['nombre_producto']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ref_producto">Referencia del Producto</label>
                        <input type="text" name="ref_producto" id="ref_producto" class="form-control" <?= empty($productos_necesidad) ? 'disabled' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" name="precio" id="precio" class="form-control" <?= empty($productos_necesidad) ? 'disabled' : '' ?>>
                    </div>
                    <button type="submit" class="btn btn-success mt-2" <?= empty($productos_necesidad) ? 'disabled' : '' ?>>Añadir Producto</button>
                </form>

                <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#productosModal').modal('show');

                        $('#productosModal').on('show.bs.modal', function(event) {
                            var button = $(event.relatedTarget);
                            var idProveedor = button.data('id-proveedor');
                            $('input[name="id_proveedor"]').val(idProveedor);
                        });

                        $('#productosModal').on('hidden.bs.modal', function() {
                            window.location.href = '<?= base_url('proveedores/') ?>';
                        });

                        // Filtrar productos en el select
                        $('#searchProducto').on('keyup', function() {
                            var value = $(this).val().toLowerCase();
                            $('#producto option').filter(function() {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                            });
                        });
                    });
                </script>

                <?= $this->endSection() ?>