<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>?v=<?= time() ?>">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<style>
    .star-icon {
        cursor: pointer;
        fill: none;
        stroke: black;
    }

    .star-icon.selected {
        fill: yellow;
        stroke: yellow;
    }
</style>
<div class="comparador">
    <h2 class="titleComparador">Comparador de Productos</h2>
    <?php if (empty($comparador)): ?>
        <p>No hay productos disponibles para comparar.</p>
    <?php else: ?>
        <?php foreach ($comparador as $item): ?>
            <div class="card mb-4 comparador">
                <div class="card-header">
                    <h5 class="mb-0"><?= esc($item['producto']['nombre_producto']) ?></h5>
                </div>
                <div class="card-body">
                    <button class="boton btnEditar btn-elegir-proveedor" data-id-producto="<?= $item['producto']['id_producto'] ?>" style="margin-bottom:10px;">
                        Añadir Proveedor
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
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
                                    <th>Referencia Producto</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($item['ofertas'] as $oferta): ?>
                                    <tr id="producto-<?= $item['producto']['id_producto'] ?>-oferta-<?= $oferta['id'] ?>"
                                        class="selectable-row"
                                        data-producto-index="<?= $item['producto']['id_producto'] ?>">
                                        <td class="star-column actions">
                                            <svg class="star-icon <?= $oferta['seleccion_mejor'] == 1 ? 'selected' : '' ?>" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 17.27L18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27Z" stroke="#000" stroke-width="2" />
                                            </svg>
                                            <button class="btn botonTabla btnAddtabla btn-nuevo-pedido" data-id-proveedor="<?= $oferta['id_proveedor'] ?>">
                                                Nuevo pedido
                                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                                                    <path d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z" fill="white" />
                                                </svg>
                                            </button>
                                        </td>

                                        <td><?= esc($oferta['nombre_proveedor']) ?></td>
                                        <td><?= esc($oferta['ref_producto']) ?></td>
                                        <td><?= esc($oferta['precio']) ?></td>
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
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.btn-nuevo-pedido').on('click', function() {
            var idProveedor = $(this).data('id-proveedor');
            $('#pedidoModalContent').load('<?= base_url("Pedidos_proveedor/add") ?>' + '?id_proveedor=' + idProveedor, function(response, status, xhr) {
                if (status === "error") {
                    console.error("Error al cargar el contenido: " + xhr.status + " " + xhr.statusText);
                    alert("Error al cargar el contenido del modal. Inténtalo más tarde.");
                } else {
                    $('#addPedidoModal').modal('show');
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