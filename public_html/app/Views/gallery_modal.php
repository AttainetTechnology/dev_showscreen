<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/gallery.css') ?>?v=<?= time() ?>">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<div class="modal-header">
    <h5 class="modal-title">Galería de Imágenes</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
<div class="gallery-container">
    <?php if (!empty($images)): ?>
        <?php foreach ($images as $image): ?>
            <div class="gallery-item" style="cursor: pointer;" onclick="selectImage('<?= esc($image['url']) ?>')">
                <img src="<?= esc($image['url']) ?>" alt="<?= esc($image['name']) ?>">
                <p><?= esc($image['name']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay imágenes disponibles.</p>
    <?php endif; ?>
</div>

</div>
<script>
    function selectImage(imageUrl) {
        const imageName = imageUrl.split('/').pop(); // Extrae el nombre de la imagen desde la URL

        // Actualiza la vista previa y el campo oculto
        $('#imagenSeleccionada').val(imageName);
        $('#imagenSeleccionadaPreview').attr('src', imageUrl).show();

        // Enviar el nombre de la imagen al servidor para asociarlo al producto
        const productoId = <?= $producto['id_producto'] ?>;

        $.ajax({
            url: '<?= base_url('productos_necesidad/asociarImagen') ?>',
            method: 'POST',
            data: { id_producto: productoId, imagen: imageName },
            success: function (response) {
                if (response.success) {
                    alert('Imagen asociada correctamente.');
                } else {
                    alert('Hubo un error al asociar la imagen.');
                }
            },
            error: function () {
                alert('Error al comunicarse con el servidor.');
            }
        });

        // Cierra el modal
        $('#galleryModal').modal('hide');
    }
</script>
