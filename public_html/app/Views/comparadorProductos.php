<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
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
                    <button class="btn mb-3 btn-elegir-proveedor" data-id-producto="<?= $item['producto']['id_producto'] ?>">
                        Elegir Proveedor
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
                                            <button class="btn-nuevo-pedido btn" data-id-proveedor="<?= $oferta['id_proveedor'] ?>">
                                                + Nuevo pedido
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
    <button type="button" class="btn btn-info mb-3" id="volverButton" style="margin-right: 2vw;">Volver</button>
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