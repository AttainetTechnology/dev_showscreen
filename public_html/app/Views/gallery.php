<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<title>Galería de Imágenes</title>
<style>
    .gallery-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    .gallery-item, .folder-item {
        flex: 1 1 calc(25% - 15px);
        max-width: calc(25% - 15px);
        box-sizing: border-box;
        text-align: center;
    }
    .gallery-item img, .folder-item img {
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

<!-- Mostrar carpetas -->
<?php if (!empty($folders)): ?>
    <h2>Carpetas</h2>
    <div class="gallery-container">
        <?php foreach ($folders as $folder): ?>
            <div class="folder-item">
                <img src="<?= base_url('public/assets/icons/folder.png') ?>" alt="Carpeta">
                <a href="<?= base_url("/gallery/{$id_empresa}/" . urlencode($folder)) ?>">
                    <?= esc(basename($folder)) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<!-- Mostrar imágenes -->
<?php if (!empty($images)): ?>
    <h2>Imágenes</h2>
    <div class="gallery-container">
        <?php foreach ($images as $image): ?>
            <div class="gallery-item">
                <img src="<?= $image ?>" alt="Imagen">
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No hay imágenes disponibles en este directorio.</p>
<?php endif; ?>


<?= $this->endSection() ?>
