<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="comparador">
    <h2>Comparador de Productos</h2>
    <?php if (empty($comparador)): ?>
        <p>No hay productos disponibles para comparar.</p>
    <?php else: ?>
        <?php foreach ($comparador as $item): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><?= esc($item['producto']['nombre_producto']) ?></h5>
                </div>
                <div class="card-body">
                    <?php if (empty($item['ofertas'])): ?>
                        <p>No hay ofertas disponibles para este producto.</p>
                    <?php else: ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Referencia Producto</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($item['ofertas'] as $oferta): ?>
                                    <tr id="producto-<?= $item['producto']['id_producto'] ?>-oferta-<?= $oferta['id'] ?>"
                                        class="selectable-row <?= $oferta['seleccion_mejor'] == 1 ? 'table-success' : '' ?>"
                                        data-producto-index="<?= $item['producto']['id_producto'] ?>">
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
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.selectable-row').on('click', function() {
            var productoIndex = $(this).data('producto-index');
            var ofertaIndex = $(this).attr('id').split('-').pop();
            var isSelected = $(this).hasClass('table-success');

            if (isSelected) {
                $(this).removeClass('table-success');
                $.ajax({
                    url: '/comparadorProductos/deseleccionarMejor',
                    method: 'POST',
                    data: {
                        productoIndex: productoIndex,
                        ofertaIndex: ofertaIndex
                    },
                    success: function(response) {
                        console.log('Proveedor deseleccionado exitosamente');
                        alert('Has deseleccionado la mejor oferta para este producto.');
                    },
                    error: function() {
                        console.error('Error al deseleccionar el proveedor');
                    }
                });
            } else {
                $('tr[data-producto-index="' + productoIndex + '"]').removeClass('table-success');
                $(this).addClass('table-success');

                $.ajax({
                    url: '/comparadorProductos/seleccionarMejor',
                    method: 'POST',
                    data: {
                        productoIndex: productoIndex,
                        ofertaIndex: ofertaIndex
                    },
                    success: function(response) {
                        console.log('Proveedor seleccionado exitosamente');
                        alert('Has seleccionado una mejor oferta para este producto.');
                    },
                    error: function() {
                        console.error('Error al seleccionar el proveedor');
                    }
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>