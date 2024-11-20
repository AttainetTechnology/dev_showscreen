<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<br>
<h2>Editar Producto</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
<form action="<?= base_url("productos/editarProducto/{$producto['id_producto']}") ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="nombre_producto" class="form-label">Nombre del Producto</label>
        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" value="<?= esc($producto['nombre_producto']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" class="form-control" id="precio" name="precio" value="<?= esc($producto['precio']) ?>" step="0.01" required>
    </div>
    <div class="mb-3">
        <label for="id_familia" class="form-label">Familia</label>
        <select class="form-control" id="id_familia" name="id_familia" required>
            <option value="">Seleccione una familia</option>
            <?php foreach ($familias as $familia): ?>
                <option value="<?= $familia['id_familia'] ?>" <?= $familia['id_familia'] == $producto['id_familia'] ? 'selected' : '' ?>>
                    <?= esc($familia['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="unidad" class="form-label">Unidad</label>
        <select class="form-control" id="unidad" name="unidad" required>
            <option value="">Seleccione una unidad</option>
            <?php foreach ($unidades as $unidad): ?>
                <option value="<?= $unidad['id_unidad'] ?>" <?= $unidad['id_unidad'] == $producto['unidad'] ? 'selected' : '' ?>>
                    <?= esc($unidad['nombre_unidad']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="estado_producto" class="form-label">Estado</label>
        <select class="form-control" id="estado_producto" name="estado_producto" required>
            <option value="1" <?= $producto['estado_producto'] == 1 ? 'selected' : '' ?>>Activo</option>
            <option value="0" <?= $producto['estado_producto'] == 0 ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="imagen" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="imagen" name="imagen">
        <?php if (!empty($producto['imagen_url'])): ?>
            <div>
                <br>
                <img src="<?= $producto['imagen_url'] ?>" alt="Imagen Producto" style="width: 100px; height: 100px;">
                <button type="button" class="boton btnEliminar" onclick="eliminarImagen(<?= $producto['id_producto'] ?>)">
                    Eliminar Imagen
                </button>
            </div>
        <?php endif; ?>
    </div>
    <div class="buttonsEditProductProveedAbajo">
        <a href="<?= base_url('productos') ?>" class="boton volverButton">Volver</a>
        <button type="submit" class="boton btnGuardar">Guardar Cambios</button>
    </div>
</form>

<script>
    function eliminarImagen(idProducto) {
        if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
            $.ajax({
                url: `<?= base_url("productos/eliminarImagen") ?>/${idProducto}`,
                type: 'DELETE',
                success: function (response) {
                    if (response.success) {
                        alert('Imagen eliminada correctamente');
                        location.reload(); // Recargar para actualizar el formulario
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error en la solicitud para eliminar la imagen.');
                }
            });
        }
    }

</script>

<?= $this->endSection() ?>