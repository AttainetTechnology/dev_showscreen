<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2>Añadir Producto</h2>
<form action="<?= base_url('productos_necesidad/save') ?>" method="post" enctype="multipart/form-data">



    <div class="mb-3">
        <label for="nombre_producto" class="form-label">Nombre del Producto</label>
        <input type="text" name="nombre_producto" id="nombre_producto" class="form-control" required>
    </div>


    <div class="mb-3">
        <label for="id_familia" class="form-label">Familia</label>
        <select name="id_familia" id="id_familia" class="form-select" required>
            <option value="">Selecciona una familia</option>
            <?php foreach ($familias as $familia): ?>
                <option value="<?= $familia['id_familia'] ?>"><?= $familia['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="unidad" class="form-label">Unidad</label>
        <input type="text" name="unidad" id="unidad" class="form-control">
    </div>

    <div class="mb-3">
        <label for="estado_producto" class="form-label">Estado del Producto</label>
        <select name="estado_producto" id="estado_producto" class="form-select" required>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar Producto</button>
</form>


<?= $this->endSection() ?>
