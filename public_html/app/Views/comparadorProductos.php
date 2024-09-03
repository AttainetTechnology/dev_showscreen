<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h2>Comparador de Productos</h2>

<!-- Filtro de productos -->
<div class="form-group mb-4">
    <label for="filter-producto"></label>
    <input type="text" id="filter-producto" class="form-control" placeholder="Buscador de productos">
</div>

<?php if (empty($comparador)): ?>
    <p>No hay productos disponibles para comparar.</p>
<?php else: ?>
    <?php foreach ($comparador as $index => $item): ?>
        <div class="card mb-4 producto-item" data-producto-nombre="<?= strtolower(esc($item['producto']['nombre_producto'])) ?>">
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
                            <?php foreach ($item['ofertas'] as $ofertaIndex => $oferta): ?>
                                <tr id="producto-<?= $index ?>-oferta-<?= $ofertaIndex ?>" class="selectable-row" data-producto-index="<?= $index ?>">
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

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Filtrar productos
        $('#filter-producto').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.producto-item').filter(function() {
                $(this).toggle($(this).data('producto-nombre').indexOf(value) > -1);
            });
        });

        $('.selectable-row').on('click', function() {
            var productoIndex = $(this).data('producto-index');

            // Si la fila ya está seleccionada, deseleccionarla
            if ($(this).hasClass('table-success')) {
                $(this).removeClass('table-success');
            } else {
                // Eliminar la selección previa en el mismo producto
                $('tr[data-producto-index="' + productoIndex + '"]').removeClass('table-success');

                // Añadir la clase de selección a la fila clicada
                $(this).addClass('table-success');
            }
        });
    });
</script>

<?= $this->endSection() ?>
