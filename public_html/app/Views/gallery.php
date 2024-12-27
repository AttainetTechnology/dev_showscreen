<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<title>Galería de Imágenes</title>
<br>
<style>
    .gallery-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .gallery-item,
    .folder-item {
        flex: 1 1 calc(25% - 15px);
        max-width: calc(25% - 15px);
        box-sizing: border-box;
        text-align: center;
    }

    .gallery-item img,
    .folder-item img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .folder-item a {
        text-decoration: none;
        color: inherit;
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }
</style>

<h1>Galería de Imágenes</h1>
<br> <br>
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

<div class="gallery-container">
    <?php foreach ($images as $image): ?>
        <div class="gallery-item">
            <img src="<?= esc($image['url']) ?>" alt="<?= esc($image['name']) ?>">
            <br><br>
            <p><?= esc($image['name']) ?></p>
            <!-- Formulario para eliminar imagen -->
            <form action="<?= base_url('/gallery/delete') ?>" method="post" style="margin-top: 10px;">
    <?= csrf_field() ?>
    <input type="hidden" name="image_path" value="<?= esc($image['url']) ?>">
    <input type="hidden" name="record_id" value="<?= esc($image['record_id'] ?? '') ?>"> <!-- Agrega el ID del registro -->
    <button type="submit" class="btn btn-danger">Eliminar</button>
</form>


        </div>
    <?php endforeach; ?>
</div>



<?= $this->endSection() ?>