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
        <label for="imagen" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="imagen" name="imagen">
        <?php if ($producto['imagen_url'] && $producto['imagen_url'] !== base_url('public/assets/images/default.png')): ?>
            <small>Imagen actual:</small>
            <img src="<?= $producto['imagen_url'] ?>" alt="Imagen Producto" style="width: 100px; height: 100px;">
        <?php endif; ?>
    </div>
    <div class="modal-footer">
        <a href="<?= base_url('productos') ?>" class="btn btn-secondary">Volver</a>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </div>
</form>

<?= $this->endSection() ?>
