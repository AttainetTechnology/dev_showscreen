<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/gallery.css') ?>?v=<?= time() ?>">

<title>Galería de Imágenes</title>
<br>
<h1>Galería de Imágenes</h1>

<!-- Mostrar carpetas -->
<?php if (!empty($folders)): ?>
    <div class="gallery-container">
        <?php foreach ($folders as $folder): ?>
            <div class="folder-item">
                <img src="<?= base_url('public/assets/uploads/files/carpeta.png') ?>" alt="Carpeta">
                <a href="<?= base_url("/gallery/" . urlencode($folder)) ?>">
                    <?= esc(basename($folder)) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Mostrar imágenes -->
<div class="gallery-container">
    <?php foreach ($images as $image): ?>
        <div class="gallery-item">
            <img src="<?= esc($image['url']) ?>" alt="<?= esc($image['name']) ?>">
            <p><?= esc($image['name']) ?></p>

            <form class="deleteForm" method="post" action="<?= base_url('gallery/delete') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="image_path" value="<?= esc($image['url']) ?>">
                <button type="button" class="btn boton btnEliminar"
                    data-associated="<?= isset($image['is_associated']) && $image['is_associated'] ? 'true' : 'false' ?>">
                    Eliminar
                </button>
            </form>

        </div>
    <?php endforeach; ?>
</div>

<br>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.btnEliminar');

        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const isAssociated = button.getAttribute('data-associated') === 'true';
                const form = button.closest('.deleteForm');
                const message = isAssociated
                    ? 'IMAGEN ASOCIADA A UN REGISTRO. ¿Desea eliminarla?'
                    : '¿Está seguro de eliminar esta imagen?';

                if (confirm(message)) {
                    form.submit();
                }
            });
        });
    });

</script>
<?= $this->endSection() ?>