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
                <a href="<?= base_url("/gallery/{$id_empresa}/" . urlencode($folder)) ?>">
                    <?= esc(basename($folder)) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Mostrar imágenes -->
<?php if (!empty($images)): ?>
    <h2><?= esc(ucfirst(strtolower($current_folder))) ?></h2>


    <div class="gallery-container">
        <?php foreach ($images as $image): ?>
            <div class="gallery-item">
                <img src="<?= esc($image['url']) ?>" alt="<?= esc($image['name']) ?>">
                <br> <br>
                <p><?= esc($image['name']) ?></p> <!-- Mostrar el nombre de la imagen -->
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>

<?php endif; ?>



<?= $this->endSection() ?>