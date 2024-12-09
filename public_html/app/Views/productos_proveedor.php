<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="modal fade" id="productosModal" tabindex="-1" role="dialog" aria-labelledby="productosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productosModalLabel">Productos de <?= esc($nombre_proveedor) ?></h5>
                <a href="<?= base_url('proveedores'); ?>">
                    <button type="button" class="btn-close-custom" aria-label="Close">
                        &times;
                    </button>
                </a>
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
                                    <td>
                                        <span class="ref_producto"><?= esc($producto['ref_producto']) ?></span>
                                        <input type="text" class="form-control edit-ref_producto d-none" value="<?= esc($producto['ref_producto']) ?>">
                                    </td>
                                    <td><?= esc($producto['nombre_producto']) ?></td>
                                    <td>
                                        <span class="precio"><?= esc($producto['precio']) ?></span>
                                        <input type="text" class="form-control edit-precio d-none" value="<?= esc($producto['precio']) ?>">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-edit">Editar</button>
                                        <button type="button" class="btn btn-success btn-save d-none">Guardar</button>
                                        <button type="button" class="btn btn-secondary btn-cancel d-none">Cancelar</button>
                                        <form action="<?= base_url('proveedores/eliminarProducto') ?>" method="post" class="d-inline btn-delete">
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
                <!-- Filtro por Familia -->
                <div class="form-group mb-4">
                    <label for="filter-familia">Filtrar por Familia:</label>
                    <select id="filter-familia" class="form-control">
                        <option value="">Todas las familias</option>
                        <?php foreach ($familias as $familia): ?>
                            <option value="<?= esc($familia['id_familia']) ?>"><?= esc($familia['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <form action="<?= base_url('proveedores/agregarProducto') ?>" method="post">
                    <input type="hidden" name="id_proveedor" value="<?= esc($id_proveedor) ?>">

                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <select name="id_producto_necesidad" id="producto" class="form-control" <?= empty($productos_necesidad) ? 'disabled' : '' ?>>
                            <option value="" disabled selected>Selecciona producto</option>
                            <?php foreach ($productos_necesidad as $producto): ?>
                                <option value="<?= esc($producto['id_producto']) ?>" data-familia="<?= esc($producto['id_familia']) ?>"><?= esc($producto['nombre_producto']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ref_producto">Referencia del Producto</label>
                        <input type="text" name="ref_producto" id="ref_producto" class="form-control" <?= empty($productos_necesidad) ? 'disabled' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" name="precio" id="precio" class="form-control" required <?= empty($productos_necesidad) ? 'disabled' : '' ?>>
                    </div>
                    <button type="submit" class="btn btn-success mt-2" <?= empty($productos_necesidad) ? 'disabled' : '' ?>>Añadir Producto</button>
                </form>

                <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

                <script>
                    $(document).ready(function() {
                        // Filtrar productos por familia
                        $('#filter-familia').on('change', function() {
                            var familiaId = $(this).val();
                            var selectedProduct = $('#producto').val();
                            var selectedProductBelongsToFamily = false;

                            $('#producto option').each(function() {
                                if (familiaId === "" || $(this).data('familia') == familiaId) {
                                    $(this).show();
                                    if ($(this).val() == selectedProduct) {
                                        selectedProductBelongsToFamily = true;
                                    }
                                } else {
                                    $(this).hide();
                                }
                            });
                            // Si el producto seleccionado no pertenece a la familia seleccionada, deseleccionarlo
                            if (!selectedProductBelongsToFamily) {
                                $('#producto').val('').change(); // Deselect
                            }
                        });

                        // Mostrar el modal
                        $('#productosModal').modal('show');

                        // Redirigir al cerrar el modal con clic fuera de él
                        $('#productosModal').on('hidden.bs.modal', function() {
                            window.location.href = '<?= base_url('proveedores') ?>';
                        });

                        // Activar la edición
                        $('.btn-edit').on('click', function() {
                            var row = $(this).closest('tr');
                            row.find('.ref_producto, .precio, .btn-delete').addClass('d-none');
                            row.find('.edit-ref_producto, .edit-precio, .btn-save, .btn-cancel').removeClass('d-none');
                            $(this).addClass('d-none');
                        });

                        // Cancelar la edición
                        $('.btn-cancel').on('click', function() {
                            var row = $(this).closest('tr');
                            row.find('.ref_producto, .precio, .btn-edit, .btn-delete').removeClass('d-none');
                            row.find('.edit-ref_producto, .edit-precio, .btn-save, .btn-cancel').addClass('d-none');
                        });

                        // Guardar los cambios
                        $('.btn-save').on('click', function() {
                            var row = $(this).closest('tr');
                            var refProducto = row.find('.edit-ref_producto').val();
                            var precio = row.find('.edit-precio').val();
                            var idProveedor = "<?= esc($id_proveedor) ?>";
                            var idProductoNecesidad = row.find('input[name="id_producto_necesidad"]').val();

                            $.post("<?= base_url('proveedores/actualizarProducto') ?>", {
                                id_proveedor: idProveedor,
                                id_producto_necesidad: idProductoNecesidad,
                                ref_producto: refProducto,
                                precio: precio
                            }, function(response) {
                                if (response.success) {
                                    row.find('.ref_producto').text(refProducto).removeClass('d-none');
                                    row.find('.precio').text(precio).removeClass('d-none');
                                    row.find('.edit-ref_producto, .edit-precio, .btn-save, .btn-cancel').addClass('d-none');
                                    row.find('.btn-edit, .btn-delete').removeClass('d-none');
                                } else {
                                    alert('Error al actualizar el producto.');
                                }
                            }, 'json');
                        });
                    });
                </script>

                <?= $this->endSection() ?>