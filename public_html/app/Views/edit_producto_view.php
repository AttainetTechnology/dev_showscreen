<form method="post" action="<?= base_url('productos/update/' . $producto->id_producto); ?>">
    <div class="form-group">
        <label for="nombre_producto">Nombre del producto</label>
        <input type="text" name="nombre_producto" class="form-control" value="<?= esc($producto->nombre_producto) ?>" required>
    </div>

    <div class="form-group">
        <label for="id_familia">Familia</label>
        <input type="text" name="id_familia" class="form-control" value="<?= esc($producto->id_familia) ?>" required>
    </div>

    <div class="form-group">
        <label for="precio">Precio</label>
        <input type="number" name="precio" class="form-control" value="<?= esc($producto->precio) ?>" step="0.01" required>
    </div>

    <div class="form-group">
        <label for="unidad">Unidad</label>
        <input type="text" name="unidad" class="form-control" value="<?= esc($producto->unidad) ?>" required>
    </div>

    <div class="form-group">
        <label for="estado_producto">Estado</label>
        <select name="estado_producto" class="form-control">
            <option value="1" <?= $producto->estado_producto == '1' ? 'selected' : '' ?>>Activo</option>
            <option value="0" <?= $producto->estado_producto == '0' ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <!-- Botón para procesos del producto -->
    <div class="botones_user">
        <a href="<?= base_url('productos/' . $producto->id_producto) ?>" class="btn btn-warning btn-sm">
            <i class="fa fa-box fa-fw"></i> Procesos del producto
        </a>
    </div>

    <button type="submit" class="btn btn-success">Guardar cambios</button>
</form>


<script>
$(document).on('click', '.grocery-crud-table a.edit-button', function (e) {
    e.preventDefault();
    var id = $(this).closest('tr').find('td:first-child').text();
    window.location.href = base_url + "/productos/edit/" + id;
});

</script>