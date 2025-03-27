<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
</div>
<div id="productosModalBody">
    <label for="filtrarFamilia" style="margin-bottom:10px;">Filtrar por Familia:</label>
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
                    <tr data-familia="<?= esc($producto['id_familia']) ?>">
                        <td><?= esc($producto['nombre_producto']) ?></td>
                        <td>
                            <?php if ($producto['id_producto'] == $id_producto_venta): ?>
                                <button type="button" class="boton btn btn-danger btn-select" data-id="<?= esc($producto['id_producto']) ?>" data-action="deselect">
                                    Desactivar <i class="bi bi-check-square ms-1"></i>
                                </button>
                            <?php else: ?>
                                <button type="button" class="boton btn btn-success btn-select" data-id="<?= esc($producto['id_producto']) ?>" data-action="select">
                                    Activar <i class="bi bi-square ms-1"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>


<script>
    $(document).ready(function() {
        $('#productoModal').modal('show');
        $('#buscarProducto').on('keyup', function() {
            filtrarProductos();
        });
        $('#filtrarFamilia').on('change', function() {
            $('#buscarProducto').val('');
            filtrarProductos();
        });

        function filtrarProductos() {
            var searchValue = $('#buscarProducto').val().toLowerCase();
            var selectedFamilia = $('#filtrarFamilia').val();

            $('#tablaProductos tbody tr').each(function() {
                var productoText = $(this).text().toLowerCase();
                var productoFamilia = $(this).data('familia');
                var matchesFamilia = selectedFamilia === "" || selectedFamilia == productoFamilia;
                var matchesSearch = productoText.indexOf(searchValue) > -1;
                $(this).toggle(matchesFamilia && matchesSearch);
            });
        }
        $('.btn-select').on('click', function() {
    var productoId = $(this).data('id');
    var action = $(this).data('action');
    var nombreProducto = $(this).closest('tr').find('td:first').text();
    var idProductoNecesidad = <?= esc($id_producto) ?>;
    var idProductoVenta = action === 'select' ? productoId : null;

    $.post('<?= base_url('productos_necesidad/actualizarProductoVenta') ?>', {
        id_producto_necesidad: idProductoNecesidad,
        id_producto_venta: idProductoVenta
    }, function(response) {
        if (response.success) {
            if (action === 'select') {
                alert("Has seleccionado " + nombreProducto);
                $('.btn-select').removeClass('btn-danger').addClass('btn-success')
                    .html('Activar <i class="bi bi-square ms-1"></i>')
                    .data('action', 'select');
                $('button[data-id="' + productoId + '"]').removeClass('btn-success')
                    .addClass('btn-danger')
                    .html('Desactivar <i class="bi bi-check-square ms-1"></i>')
                    .data('action', 'deselect');
            } else {
                alert("Has deseleccionado " + nombreProducto);
                $('button[data-id="' + productoId + '"]').removeClass('btn-danger')
                    .addClass('btn-success')
                    .html('Activar <i class="bi bi-square ms-1"></i>')
                    .data('action', 'select');
            }
        } else {
            alert('Error al realizar la acción.');
        }
    }, 'json');
});

    });
</script>