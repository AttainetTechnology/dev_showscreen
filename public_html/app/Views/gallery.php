<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/gallery.css') ?>?v=<?= time() ?>">

<title>Galería de Imágenes</title>

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

            <!-- Formulario para eliminar imagen -->
            <form action="<?= base_url('/gallery/delete') ?>" method="post" style="margin-top: 15px;">
                <?= csrf_field() ?>
                <input type="hidden" name="image_path" value="<?= esc($image['url']) ?>">
                <input type="hidden" name="record_id" value="<?= esc($image['record_id'] ?? '') ?>">
                <button type="submit" class="btn boton btnEliminar">Eliminar</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<br>
<?= $this->endSection() ?>
