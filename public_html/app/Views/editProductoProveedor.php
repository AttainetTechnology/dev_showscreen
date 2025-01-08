<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<style>
    .asociado-producto {
        font-weight: bold;
        margin-left: 10px;
        color: #333;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">

<h2 class="titleEditProductoProveedor">Editar Producto</h2>
<br>
<br>
<form id="formEditProduct" action="<?= base_url('productos_necesidad/update/' . $producto['id_producto']) ?>"
    method="post" enctype="multipart/form-data" class="fromEditProductProveed">
    <div class="mb-3">
        <label for="nombre_producto" class="form-label">Nombre del Producto</label>
        <input type="text" name="nombre_producto" id="nombre_producto" class="form-control"
            value="<?= $producto['nombre_producto'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="id_familia" class="form-label">Familia</label>
        <select name="id_familia" id="id_familia" class="form-select" required>
            <option value="">Selecciona una familia</option>
            <?php foreach ($familias as $familia): ?>
                <option value="<?= $familia['id_familia'] ?>" <?= $producto['id_familia'] == $familia['id_familia'] ? 'selected' : '' ?>><?= $familia['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="imagen" class="form-label">Imagen</label>
        <input type="file" name="imagen" id="imagen" class="form-control">
        <input type="hidden" name="imagenSeleccionada" id="imagenSeleccionada" value="<?= $producto['imagen'] ?>">
        <input type="hidden" id="estado_producto_hidden" name="estado_producto"
            value="<?= $producto['estado_producto'] ?>">

        <br>
        <button type="button" class="btn boton btnEditar" id="abrirModalGaleria">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-image"
                viewBox="0 0 16 16">
                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                <path
                    d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z" />
            </svg>Seleccionar de la Galería</button>

        <!-- Botón para eliminar la imagen -->
        <?php if ($producto['imagen']): ?>
            <button type="button" class="btn boton btnEliminar" style="margin-left:10px !important;"
                onclick="eliminarImagen(<?= $producto['id_producto'] ?>)"><svg xmlns="http://www.w3.org/2000/svg" width="26"
                    height="27" viewBox="0 0 26 27" fill="none">
                    <path
                        d="M7.66752 7.27601C7.41731 7.27601 7.17736 7.37593 7.00044 7.5538C6.82351 7.73166 6.72412 7.9729 6.72412 8.22444V9.17287C6.72412 9.4244 6.82351 9.66564 7.00044 9.84351C7.17736 10.0214 7.41731 10.1213 7.66752 10.1213H8.13922V18.6571C8.13922 19.1602 8.338 19.6427 8.69184 19.9984C9.04569 20.3542 9.5256 20.554 10.026 20.554H15.6864C16.1868 20.554 16.6667 20.3542 17.0205 19.9984C17.3744 19.6427 17.5732 19.1602 17.5732 18.6571V10.1213H18.0449C18.2951 10.1213 18.535 10.0214 18.712 9.84351C18.8889 9.66564 18.9883 9.4244 18.9883 9.17287V8.22444C18.9883 7.9729 18.8889 7.73166 18.712 7.5538C18.535 7.37593 18.2951 7.27601 18.0449 7.27601H14.743C14.743 7.02447 14.6436 6.78324 14.4667 6.60537C14.2898 6.42751 14.0498 6.32758 13.7996 6.32758H11.9128C11.6626 6.32758 11.4226 6.42751 11.2457 6.60537C11.0688 6.78324 10.9694 7.02447 10.9694 7.27601H7.66752ZM10.4977 11.0697C10.6228 11.0697 10.7428 11.1197 10.8312 11.2086C10.9197 11.2975 10.9694 11.4182 10.9694 11.5439V18.1829C10.9694 18.3087 10.9197 18.4293 10.8312 18.5182C10.7428 18.6072 10.6228 18.6571 10.4977 18.6571C10.3726 18.6571 10.2526 18.6072 10.1642 18.5182C10.0757 18.4293 10.026 18.3087 10.026 18.1829V11.5439C10.026 11.4182 10.0757 11.2975 10.1642 11.2086C10.2526 11.1197 10.3726 11.0697 10.4977 11.0697ZM12.8562 11.0697C12.9813 11.0697 13.1013 11.1197 13.1897 11.2086C13.2782 11.2975 13.3279 11.4182 13.3279 11.5439V18.1829C13.3279 18.3087 13.2782 18.4293 13.1897 18.5182C13.1013 18.6072 12.9813 18.6571 12.8562 18.6571C12.7311 18.6571 12.6111 18.6072 12.5227 18.5182C12.4342 18.4293 12.3845 18.3087 12.3845 18.1829V11.5439C12.3845 11.4182 12.4342 11.2975 12.5227 11.2086C12.6111 11.1197 12.7311 11.0697 12.8562 11.0697ZM15.6864 11.5439V18.1829C15.6864 18.3087 15.6367 18.4293 15.5482 18.5182C15.4598 18.6072 15.3398 18.6571 15.2147 18.6571C15.0896 18.6571 14.9696 18.6072 14.8811 18.5182C14.7927 18.4293 14.743 18.3087 14.743 18.1829V11.5439C14.743 11.4182 14.7927 11.2975 14.8811 11.2086C14.9696 11.1197 15.0896 11.0697 15.2147 11.0697C15.3398 11.0697 15.4598 11.1197 15.5482 11.2086C15.6367 11.2975 15.6864 11.4182 15.6864 11.5439Z"
                        fill="white" />
                </svg></button>
        <?php endif; ?>
        <br> <br>
        <?php if ($producto['imagen']): ?>
            <img id="imagenSeleccionadaPreview"
                src="<?= base_url("public/assets/uploads/files/{$id_empresa}/productos/" . $producto['imagen']) ?>"
                height="60">
        <?php else: ?>
            <img id="imagenSeleccionadaPreview" src="#" alt="Vista previa" height="60" style="display: none;">
        <?php endif; ?>
        <br>
    </div>
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <?= view('gallery_modal', ['images' => $images]) ?>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="unidad" class="form-label">Unidad</label>
        <input type="text" name="unidad" id="unidad" class="form-control" value="<?= $producto['unidad'] ?>">
    </div>

    <div class="mb-3">
        <label for="estado_producto" class="form-label">Estado del Producto</label>
        <select name="estado_producto" id="estado_producto" class="form-select" required>
            <option value="Activo" <?= $producto['estado_producto'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
            <option value="Inactivo" <?= $producto['estado_producto'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <div class="container-buttons">

        <div class="left-button">
            <?php if ($productoVentaNombre && $productoVentaNombre !== 'No hay producto de venta seleccionado'): ?>
                <span class="asociado-producto"> <br> <br> PRODUCTO ASOCIADO: <?= esc($productoVentaNombre) ?></span>
            <?php endif; ?>
            <br> <br>
            <button type="button" class="boton btnEditar svgEditarProducto" id="abrirModalProducto">¿Vendemos este
                producto?</button>
        </div>
        <div class="buttonsEditProductProveedAbajo">
            <button type="button" class="boton volverButton" id="volverButton">
                Volver
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M19.5 13C19.5 13.2155 19.4144 13.4221 19.262 13.5745C19.1096 13.7269 18.903 13.8125 18.6875 13.8125H9.27386L12.7627 17.2997C12.8383 17.3753 12.8982 17.465 12.9391 17.5637C12.98 17.6624 13.001 17.7682 13.001 17.875C13.001 17.9818 12.98 18.0876 12.9391 18.1863C12.8982 18.285 12.8383 18.3747 12.7627 18.4502C12.6872 18.5258 12.5975 18.5857 12.4988 18.6266C12.4001 18.6675 12.2943 18.6885 12.1875 18.6885C12.0807 18.6885 11.9749 18.6675 11.8762 18.6266C11.7775 18.5857 11.6878 18.5258 11.6122 18.4502L6.73724 13.5752C6.66157 13.4998 6.60154 13.4101 6.56058 13.3114C6.51962 13.2127 6.49854 13.1069 6.49854 13C6.49854 12.8931 6.51962 12.7873 6.56058 12.6886C6.60154 12.5899 6.66157 12.5002 6.73724 12.4247L11.6122 7.54974C11.7648 7.39717 11.9717 7.31146 12.1875 7.31146C12.4032 7.31146 12.6102 7.39717 12.7627 7.54974C12.9153 7.7023 13.001 7.90923 13.001 8.12499C13.001 8.34075 12.9153 8.54767 12.7627 8.70024L9.27386 12.1875H18.6875C18.903 12.1875 19.1096 12.2731 19.262 12.4255C19.4144 12.5778 19.5 12.7845 19.5 13Z"
                        fill="white" />
                </svg>
            </button>
            <button id="btnGuardar" type="submit" class="boton btnGuardar">Guardar Cambios
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27" fill="none">
                    <path
                        d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                        fill="white" />
                </svg>
            </button>
        </div>
    </div>
</form>
<!-- Modal HTML -->
<div class="modal fade" id="productoModal" tabindex="-1" role="dialog" aria-labelledby="productoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content" style="height: 100vh;">
            <div class="modal-header">
                <h5 class="modal-title" id="productoModalLabel">Selecciona un producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productosModalBody" style="overflow-y: auto; max-height: calc(100vh - 150px);">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#abrirModalGaleria').on('click', function () {
            $('#galleryModal').modal('show');
        });

        $(document).ready(function () {
            // Sincronizar el valor del select al campo hidden cuando cambie
            $('#estado_producto').on('change', function () {
                $('#estado_producto_hidden').val($(this).val());
            });

            // Asegúrate de sincronizar el valor antes de enviar el formulario
            $('#btnGuardar').on('click', function () {
                $('#estado_producto_hidden').val($('#estado_producto').val()); // Actualiza hidden
                console.log('Estado final enviado:', $('#estado_producto_hidden').val()); // Depuración
                $('#formEditProduct').submit(); // Envía el formulario
            });
        });

        $('#eliminarImagenButton').on('click', function () {
            if (confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
                $.ajax({
                    url: '<?= base_url('productos_necesidad/eliminarImagen/' . $producto['id_producto']) ?>',
                    type: 'POST',
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            location.reload();
                        }
                    },
                    error: function () {
                        alert("Error al intentar eliminar la imagen.");
                    }
                });
            }
        });

        $('#abrirModalProducto').on('click', function () {
            $.ajax({
                url: '<?= base_url('productos_necesidad/verProductos/' . $producto['id_producto']) ?>',
                method: 'GET',
                success: function (response) {
                    $('#productosModalBody').html(response);
                    $('#productoModal').modal('show');
                },
                error: function () {
                    alert('Error al cargar el modal.');
                }
            });
        });

        $('#productoModal').on('hidden.bs.modal', function () {
            window.location.href = '<?= base_url('productos_necesidad/edit/' . $producto['id_producto']) ?>';
        });

        function eliminarImagen(idProducto) {
            if (confirm('¿Desea quitar la imagen asignada?')) {
                $.ajax({
                    url: `<?= base_url("productos_necesidad/eliminarImagen") ?>/${idProducto}`,
                    type: 'POST',
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            document.getElementById('imagenSeleccionadaPreview').style.display = 'none';
                            location.reload();
                        } else {
                            location.reload();
                        }
                    },
                    error: function () {
                        alert('Error en la solicitud para eliminar la imagen.');
                    }
                });
            }
        }

        document.getElementById('volverButton').addEventListener('click', function () {
            window.location.href = '<?= base_url('productos_necesidad') ?>';
        });
    });

</script>
<?= $this->endSection() ?>