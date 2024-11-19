<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>?v=<?= time() ?>">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<style>
    .star-icon {
        cursor: pointer;
        fill: none;
        stroke-width: 1;
        stroke: #C8CBCB;
    }

    .star-icon.selected {
        fill: #F59A19;
        stroke: #F59A19;
    }
</style>
<div class="comparador">
    <h2 class="titleComparador">Comparador de precios</h2>
    <?php if (empty($comparador)): ?>
        <p>No hay productos disponibles para comparar.</p>
    <?php else: ?>
        <?php foreach ($comparador as $item): ?>
            <div class="card mb-4 comparador">
                <div class="card-header">
                    <h5 class="mb-0"><?= esc($item['producto']['nombre_producto']) ?></h5>
                </div>
                <div class="card-body">
                    <button class="boton btnAdd btn-elegir-proveedor" data-id-producto="<?= $item['producto']['id_producto'] ?>" style="margin-bottom:10px;">
                        Añadir Producto
                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="32" viewBox="0 0 26 27" fill="none">
                            <path d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z" fill="white" />
                        </svg>
                    </button>
                    <?php if (empty($item['ofertas'])): ?>
                        <p>No hay ofertas disponibles para este producto.</p>
                    <?php else: ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 6vw;">Acciones</th>
                                    <th>Proveedor</th>
                                    <th>Referencia Proveedor</th>
                                    <th>Precio</th>
                                    <th class="text-center">Favorito</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($item['ofertas'] as $oferta): ?>
                                    <tr id="producto-<?= $item['producto']['id_producto'] ?>-oferta-<?= $oferta['id'] ?>"
                                        class="selectable-row"
                                        data-producto-index="<?= $item['producto']['id_producto'] ?>">

                                        <td class="actions">
                                            <div class="top-buttons">
                                                <button class="btn botonTabla btnEditarTabla btn-editar" data-id="<?= $oferta['id'] ?>" data-id-producto="<?= $item['producto']['id_producto'] ?>">Editar
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                                                        <path d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z" fill="white" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z" fill="white" />
                                                    </svg>
                                                </button>
                                                <button class="btn botonTabla btnAddtabla btn-nuevo-pedido"
                                                    data-id-proveedor="<?= $oferta['id_proveedor'] ?>"
                                                    data-id-producto="<?= $item['producto']['id_producto'] ?>"
                                                    data-id-registro="<?= $oferta['id'] ?>">
                                                    Nuevo pedido
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="31" height="32" viewBox="0 0 26 27" fill="none">
                                                        <path d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z" fill="white" />
                                                    </svg>
                                                </button>
                                                <button class="btn botonTabla btnEliminarTabla btn-eliminar" data-id="<?= $oferta['id'] ?>" data-id-producto="<?= $item['producto']['id_producto'] ?>">Eliminar
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                                        <path d="M7.66753 6.776C7.41733 6.776 7.17737 6.87593 7.00045 7.05379C6.82353 7.23166 6.72414 7.47289 6.72414 7.72443V8.67286C6.72414 8.9244 6.82353 9.16563 7.00045 9.3435C7.17737 9.52136 7.41733 9.62129 7.66753 9.62129H8.13923V18.1571C8.13923 18.6602 8.33802 19.1427 8.69186 19.4984C9.0457 19.8541 9.52561 20.054 10.026 20.054H15.6864C16.1868 20.054 16.6667 19.8541 17.0206 19.4984C17.3744 19.1427 17.5732 18.6602 17.5732 18.1571V9.62129H18.0449C18.2951 9.62129 18.5351 9.52136 18.712 9.3435C18.8889 9.16563 18.9883 8.9244 18.9883 8.67286V7.72443C18.9883 7.47289 18.8889 7.23166 18.712 7.05379C18.5351 6.87593 18.2951 6.776 18.0449 6.776H14.743C14.743 6.52446 14.6436 6.28323 14.4667 6.10536C14.2898 5.9275 14.0498 5.82758 13.7996 5.82758H11.9128C11.6626 5.82758 11.4227 5.9275 11.2457 6.10536C11.0688 6.28323 10.9694 6.52446 10.9694 6.776H7.66753ZM10.4977 10.5697C10.6228 10.5697 10.7428 10.6197 10.8313 10.7086C10.9197 10.7975 10.9694 10.9182 10.9694 11.0439V17.6829C10.9694 17.8087 10.9197 17.9293 10.8313 18.0182C10.7428 18.1072 10.6228 18.1571 10.4977 18.1571C10.3726 18.1571 10.2526 18.1072 10.1642 18.0182C10.0757 17.9293 10.026 17.8087 10.026 17.6829V11.0439C10.026 10.9182 10.0757 10.7975 10.1642 10.7086C10.2526 10.6197 10.3726 10.5697 10.4977 10.5697ZM12.8562 10.5697C12.9813 10.5697 13.1013 10.6197 13.1898 10.7086C13.2782 10.7975 13.3279 10.9182 13.3279 11.0439V17.6829C13.3279 17.8087 13.2782 17.9293 13.1898 18.0182C13.1013 18.1072 12.9813 18.1571 12.8562 18.1571C12.7311 18.1571 12.6111 18.1072 12.5227 18.0182C12.4342 17.9293 12.3845 17.8087 12.3845 17.6829V11.0439C12.3845 10.9182 12.4342 10.7975 12.5227 10.7086C12.6111 10.6197 12.7311 10.5697 12.8562 10.5697ZM15.6864 11.0439V17.6829C15.6864 17.8087 15.6367 17.9293 15.5482 18.0182C15.4598 18.1072 15.3398 18.1571 15.2147 18.1571C15.0896 18.1571 14.9696 18.1072 14.8812 18.0182C14.7927 17.9293 14.743 17.8087 14.743 17.6829V11.0439C14.743 10.9182 14.7927 10.7975 14.8812 10.7086C14.9696 10.6197 15.0896 10.5697 15.2147 10.5697C15.3398 10.5697 15.4598 10.6197 15.5482 10.7086C15.6367 10.7975 15.6864 10.9182 15.6864 11.0439Z" fill="white" />
                                                    </svg>
                                                </button>

                                            </div>
                                        </td>

                                        <td><?= esc($oferta['nombre_proveedor']) ?></td>
                                        <td><?= esc($oferta['ref_producto']) ?></td>
                                        <td><?= esc($oferta['precio']) ?></td>
                                        <td class="star-column text-center" style="border-top: none; border-bottom: none; border-left: none;">
                                            <svg class="star-icon <?= $oferta['es_mejor'] ? 'selected' : '' ?>" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z" />
                                            </svg>
                                        </td>


                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>


                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-end">
    <button type="button" class="boton volverButton" id="volverButton" style="margin-right: 1vw;">
        Volver
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 13C19.5 13.2155 19.4144 13.4221 19.262 13.5745C19.1096 13.7269 18.903 13.8125 18.6875 13.8125H9.27386L12.7627 17.2997C12.8383 17.3753 12.8982 17.465 12.9391 17.5637C12.98 17.6624 13.001 17.7682 13.001 17.875C13.001 17.9818 12.98 18.0876 12.9391 18.1863C12.8982 18.285 12.8383 18.3747 12.7627 18.4502C12.6872 18.5258 12.5975 18.5857 12.4988 18.6266C12.4001 18.6675 12.2943 18.6885 12.1875 18.6885C12.0807 18.6885 11.9749 18.6675 11.8762 18.6266C11.7775 18.5857 11.6878 18.5258 11.6122 18.4502L6.73724 13.5752C6.66157 13.4998 6.60154 13.4101 6.56058 13.3114C6.51962 13.2127 6.49854 13.1069 6.49854 13C6.49854 12.8931 6.51962 12.7873 6.56058 12.6886C6.60154 12.5899 6.66157 12.5002 6.73724 12.4247L11.6122 7.54974C11.7648 7.39717 11.9717 7.31146 12.1875 7.31146C12.4032 7.31146 12.6102 7.39717 12.7627 7.54974C12.9153 7.7023 13.001 7.90923 13.001 8.12499C13.001 8.34075 12.9153 8.54767 12.7627 8.70024L9.27386 12.1875H18.6875C18.903 12.1875 19.1096 12.2731 19.262 12.4255C19.4144 12.5778 19.5 12.7845 19.5 13Z" fill="white" />
        </svg>
    </button>
</div>
<!-- Modal para elegir proveedor -->
<div class="modal fade" id="elegirProveedorModal" tabindex="-1" role="dialog" aria-labelledby="elegirProveedorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modalContent">
        </div>
    </div>
</div>
<!-- Modal para añadir pedido  -->
<div class="modal fade" id="addPedidoModal" tabindex="-1" role="dialog" aria-labelledby="addPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="pedidoModalContent">
        </div>
    </div>
</div>
<!-- Modal para editar oferta -->
<div class="modal fade" id="editarOfertaModal" tabindex="-1" role="dialog" aria-labelledby="editarOfertaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modalEditarContent">
            <!-- El contenido se cargará aquí desde editarOferta.php -->
        </div>
    </div>
</div>

<!-- Modal para editar oferta -->
<div class="modal fade" id="editarOfertaModal" tabindex="-1" role="dialog" aria-labelledby="editarOfertaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarOfertaModalLabel">Editar Oferta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarOferta">
                    <input type="hidden" name="id" id="idOferta">
                    <input type="hidden" name="id_producto" id="idProducto">

                    <div class="mb-3">
                        <label for="nombreProveedor" class="form-label">Proveedor</label>
                        <input type="text" class="form-control" id="nombreProveedor" name="nombre_proveedor" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="refProducto" class="form-label">Referencia Producto</label>
                        <input type="text" class="form-control" id="refProducto" name="ref_producto">
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="text" class="form-control" id="precio" name="precio">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Actualiza el código para limpiar el contenido antes de cargar
        $('.btn-editar').on('click', function() {
            var idOferta = $(this).data('id');
            var idProducto = $(this).data('id-producto');

            // Cargar el contenido del formulario en el modal
            $('#modalEditarContent').load(`<?= base_url("comparadorProductos/editarOferta") ?>/${idProducto}/${idOferta}`, function(response, status, xhr) {
                if (status === "error") {
                    console.error("Error al cargar el contenido: " + xhr.status + " " + xhr.statusText);
                    alert("Error al cargar el contenido del modal. Inténtalo más tarde.");
                } else {
                    $('#editarOfertaModal').modal('show');
                }
            });
        });
        $('#precio').on('input', function() {
            var value = $(this).val();
            $(this).val(value.replace(',', '.'));
        });
        // Enviar el formulario para actualizar la oferta
        $(document).on('submit', '#formEditarOferta', function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario
            // Asegurar que cualquier coma en el campo de precio se convierta en un punto antes de enviar
            var precioField = $('#precio');
            var value = precioField.val();
            precioField.val(value.replace(',', '.'));

            var formData = $(this).serialize();

            // Enviar solicitud POST al controlador
            $.post('<?= base_url("comparadorProductos/editarOferta") ?>', formData, function(response) {
                if (response.status === 'success') {
                    $('#editarOfertaModal').modal('hide');
                    location.reload(); // Recargar la página para mostrar los cambios
                } else {
                    alert('Error al actualizar la oferta');
                }
            }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + " - " + errorThrown);
                alert("Error al actualizar la oferta. Inténtalo más tarde.");
            });
        });
        $('.btn-eliminar').on('click', function() {
            var idOferta = $(this).data('id');
            var idProducto = $(this).data('id-producto');

            if (confirm("¿Estás seguro de que deseas eliminar esta oferta?")) {
                $.ajax({
                    url: '<?= base_url("ofertas/eliminar") ?>/' + idProducto + '/' + idOferta,
                    method: 'POST',
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            console.error("Error en la respuesta: " + (response.message || "respuesta inesperada"));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud de eliminación: " + error);
                        alert("Error al eliminar la oferta. Inténtalo más tarde.");
                    }
                });
            }
        });
        $('.btn-nuevo-pedido').on('click', function() {
            var idProveedor = $(this).data('id-proveedor');
            var idProducto = $(this).data('id-producto');
            var idRegistro = $(this).data('id-registro');
            var url = `<?= base_url("Pedidos_proveedor/add") ?>?id_proveedor=${idProveedor}&id_producto=${idProducto}&id_registro=${idRegistro}`;
            $('#pedidoModalContent').load(url, function(response, status, xhr) {
                if (status === "error") {
                    console.error("Error al cargar el contenido: " + xhr.status + " " + xhr.statusText);
                    alert("Error al cargar el contenido del modal. Inténtalo más tarde.");
                } else {
                    $('#addPedidoModal').modal('show');
                    $('#id_producto').val(idProducto);
                    $('#id_registro').val(idRegistro);
                }
            });
        });
        $('.btn-elegir-proveedor').on('click', function() {
            var idProducto = $(this).data('id-producto');
            $('#modalContent').load('<?= base_url("elegirProveedor") ?>/' + idProducto, function() {
                $('#elegirProveedorModal').modal('show');
            });
        });
        $('.star-icon').on('click', function() {
            var $this = $(this);
            var productoIndex = $this.closest('.selectable-row').data('producto-index');
            var ofertaIndex = $this.closest('tr').attr('id').split('-').pop();
            var isSelected = $this.hasClass('selected');

            if (isSelected) {
                $this.removeClass('selected');
                $.ajax({
                    url: '/comparadorProductos/deseleccionarMejor',
                    method: 'POST',
                    data: {
                        productoIndex: productoIndex,
                        ofertaIndex: ofertaIndex
                    },
                    success: function(response) {
                        alert('Proveedor deseleccionado exitosamente');
                    }
                });
            } else {
                $('tr[data-producto-index="' + productoIndex + '"] .star-icon').removeClass('selected');
                $this.addClass('selected');
                $.ajax({
                    url: '/comparadorProductos/seleccionarMejor',
                    method: 'POST',
                    data: {
                        productoIndex: productoIndex,
                        ofertaIndex: ofertaIndex
                    },
                    success: function(response) {
                        alert('Proveedor seleccionado exitosamente');
                        if (!$this.hasClass('selected')) {
                            $this.addClass('selected');
                        }
                    }
                });
            }
        });

    });

    document.getElementById('volverButton').addEventListener('click', function() {
        window.location.href = '<?= base_url('productos_necesidad') ?>';
    });
</script>

<?= $this->endSection() ?>