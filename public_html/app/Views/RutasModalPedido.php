
<!-- Modal para mostrar las rutas relacionadas con un pedido -->
<div class="modal fade" id="rutasModal" tabindex="-1" aria-labelledby="rutasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rutasModalLabel">Rutas del Pedido <?= esc($id_pedido) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($rutas)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Población</th>
                                <th>Lugar</th>
                                <th>Recogida/Entrega</th>
                                <th>Transportista</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rutas as $ruta): ?>
                                <tr>
                                    <td><?= esc($ruta['poblacion']) ?></td>
                                    <td><?= esc($ruta['lugar']) ?></td>
                                    <td><?= esc($ruta['recogida_entrega'] == 1 ? 'Recogida' : 'Entrega') ?></td>
                                    <td><?= esc($transportistas[$ruta['transportista']] ?? 'No asignado') ?></td>
                                    <td><?= esc($ruta['fecha_ruta']) ?></td>
                                    <td><?= esc($ruta['estado_ruta'] == 1 ? 'No preparado' : ($ruta['estado_ruta'] == 2 ? 'Recogido' : 'Pendiente')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay rutas para este pedido.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar el modal cuando se carga la vista
    $(document).ready(function() {
        $('#rutasModal').modal('show');
    });
</script>
