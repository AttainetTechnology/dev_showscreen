<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/gallery.css') ?>?v=<?= time() ?>">
<style>
    .modal-body {
        max-height: 70vh;
        /* Limita la altura del cuerpo del modal al 70% del viewport */
        overflow-y: auto;
        /* Habilita el scroll vertical */
    }
    
    .gallery-item:hover {
    border-color: rgba(24, 107, 24, 0.42) !important; /* Cambia el color del borde */
    border: 2px solid; /* Asegura el borde */
    box-shadow: 0 0 8px rgba(24, 107, 24, 0.42); 
}

</style>

<div class="modal-header">
    <h5 class="modal-title">Galería de Imágenes</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <!-- Buscador -->
    <input type="text" id="imageSearch" class="form-control mb-3" placeholder="Escribe el nombre de la imagen ">

    <!-- Galería -->
    <div class="gallery-container">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $image): ?>
                <div class="gallery-item" data-name="<?= esc(strtolower($image['name'])) ?>" style="cursor: pointer;"
                    onclick="selectImage('<?= esc($image['url']) ?>')">
                    <form class="deleteForm" method="post" action="<?= base_url('gallery/delete') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="image_path" value="<?= esc($image['url']) ?>">
                        <button type="submit" class="btnEliminarModal"
                            data-associated="<?= isset($image['is_associated']) && $image['is_associated'] ? 'true' : 'false' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                <path
                                    d="M7.66752 6.776C7.41731 6.776 7.17736 6.87593 7.00044 7.05379C6.82351 7.23166 6.72412 7.47289 6.72412 7.72443V8.67286C6.72412 8.9244 6.82351 9.16563 7.00044 9.3435C7.17736 9.52136 7.41731 9.62129 7.66752 9.62129H8.13922V18.1571C8.13922 18.6602 8.338 19.1427 8.69184 19.4984C9.04569 19.8541 9.5256 20.054 10.026 20.054H15.6864C16.1868 20.054 16.6667 19.8541 17.0205 19.4984C17.3744 19.1427 17.5732 18.6602 17.5732 18.1571V9.62129H18.0449C18.2951 9.62129 18.535 9.52136 18.712 9.3435C18.8889 9.16563 18.9883 8.9244 18.9883 8.67286V7.72443C18.9883 7.47289 18.8889 7.23166 18.712 7.05379C18.535 6.87593 18.2951 6.776 18.0449 6.776H14.743C14.743 6.52446 14.6436 6.28323 14.4667 6.10536C14.2898 5.9275 14.0498 5.82758 13.7996 5.82758H11.9128C11.6626 5.82758 11.4226 5.9275 11.2457 6.10536C11.0688 6.28323 10.9694 6.52446 10.9694 6.776H7.66752ZM10.4977 10.5697C10.6228 10.5697 10.7428 10.6197 10.8312 10.7086C10.9197 10.7975 10.9694 10.9182 10.9694 11.0439V17.6829C10.9694 17.8087 10.9197 17.9293 10.8312 18.0182C10.7428 18.1072 10.6228 18.1571 10.4977 18.1571C10.3726 18.1571 10.2526 18.1072 10.1642 18.0182C10.0757 17.9293 10.026 17.8087 10.026 17.6829V11.0439C10.026 10.9182 10.0757 10.7975 10.1642 10.7086C10.2526 10.6197 10.3726 10.5697 10.4977 10.5697ZM12.8562 10.5697C12.9813 10.5697 13.1013 10.6197 13.1897 10.7086C13.2782 10.7975 13.3279 10.9182 13.3279 11.0439V17.6829C13.3279 17.8087 13.2782 17.9293 13.1897 18.0182C13.1013 18.1072 12.9813 18.1571 12.8562 18.1571C12.7311 18.1571 12.6111 18.1072 12.5227 18.0182C12.4342 17.9293 12.3845 17.8087 12.3845 17.6829V11.0439C12.3845 10.9182 12.4342 10.7975 12.5227 10.7086C12.6111 10.6197 12.7311 10.5697 12.8562 10.5697ZM15.6864 11.0439V17.6829C15.6864 17.8087 15.6367 17.9293 15.5482 18.0182C15.4598 18.1072 15.3398 18.1571 15.2147 18.1571C15.0896 18.1571 14.9696 18.1072 14.8811 18.0182C14.7927 17.9293 14.743 17.8087 14.743 17.6829V11.0439C14.743 10.9182 14.7927 10.7975 14.8811 10.7086C14.9696 10.6197 15.0896 10.5697 15.2147 10.5697C15.3398 10.5697 15.4598 10.6197 15.5482 10.7086C15.6367 10.7975 15.6864 10.9182 15.6864 11.0439Z"
                                    fill="white" />
                            </svg>
                        </button>
                    </form>
                    <img src="<?= esc($image['url']) ?>" alt="<?= esc($image['name']) ?>">
                   
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
            error: function () {
                alert('Error al comunicarse con el servidor.');
            }
        });

        // Cierra el modal
        $('#galleryModal').modal('hide');
    }
    document.getElementById('imageSearch').addEventListener('input', function () {
        const searchText = this.value.toLowerCase();
        const galleryItems = document.querySelectorAll('.gallery-item');

        galleryItems.forEach(item => {
            const imageName = item.getAttribute('data-name');
            if (imageName.includes(searchText)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Prevenir el envío del formulario al presionar "Enter" en el buscador
    document.getElementById('imageSearch').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Previene el comportamiento por defecto de la tecla Enter
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.btnEliminarModal');

        deleteButtons.forEach(button => {
            // Prevenir la propagación del clic al contenedor padre
            button.addEventListener('click', (event) => {
                event.stopPropagation(); // Evita que el clic llegue al contenedor padre
                event.preventDefault(); // Evita el comportamiento predeterminado del botón

                const form = button.closest('.deleteForm');
                const isAssociated = button.getAttribute('data-associated') === 'true';
                const message = isAssociated
                    ? 'IMAGEN ASOCIADA A UN REGISTRO. ¿Desea eliminarla?'
                    : '¿Está seguro de eliminar esta imagen?';

                if (confirm(message)) {
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                    })
                        .then(response => {
                            if (response.ok) {
                                window.location.reload(); // Recargar la página tras eliminar
                            } else {
                                alert('Error al eliminar la imagen.');
                            }
                        })
                        .catch(() => alert('Error al comunicarse con el servidor.'));
                }
            });
        });

        // Manejar la selección de la imagen en el contenedor principal
        const galleryItems = document.querySelectorAll('.gallery-item');
        galleryItems.forEach(item => {
            item.addEventListener('click', () => {
                const imageUrl = item.querySelector('img').getAttribute('src');
                selectImage(imageUrl); // Selecciona la imagen
            });
        });
    });


</script>