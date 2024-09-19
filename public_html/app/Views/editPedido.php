<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h2>Editar Pedido</h2>
    <!-- Botones de Acción -->
    <div class="mb-3">
        <label for="acciones" class="form-label"></label>
        <div class="d-flex gap-2">
            <a href="<?= base_url('pedidos/print/' . $pedido->id_pedido . '?volver=' . urlencode(current_url())) ?>" class="btn btn-info btn-sm" target="_blank">
                <i class="fa fa-print"></i> Imprimir Pedido
            </a>
            <a href="<?= base_url('pedidos/parte_complejo/' . $pedido->id_pedido . '?volver=' . urlencode(current_url())) ?>" class="btn btn-secondary btn-sm" target="_blank">
                <i class="fa fa-truck"></i> Parte Complejo
            </a>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="fa fa-truck fa-fw"></i> Rutas de transporte
            </button>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Rutas de transporte</h4>
                        </div>
                        <div class="modal-body">
                            <iframe src="<?= base_url('Ruta_pedido/rutas/' . $pedido->id_pedido . '/' . $pedido->id_cliente) ?>" frameborder="0" width="100%" height="400px"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <a href="<?= base_url('pedidos/entregar/' . $pedido->id_pedido) ?>" class="btn btn-success btn-sm">
                <i class="fa fa-check fa-fw"></i> Entregar Pedido
            </a>
            <a href="<?= base_url('pedidos/anular/' . $pedido->id_pedido) ?>" class="btn btn-danger btn-sm btn_anular">
                <i class="fa fa-trash fa-fw"></i> Anular Pedido
            </a>
        </div>
        <br>
    </div>
    <form action="<?= base_url('pedidos/update/' . $pedido->id_pedido) ?>" method="post">
        <!-- Empresa -->
        <div class="form-group">
            <label for="id_cliente">Empresa:</label>
            <select id="id_cliente" name="id_cliente" class="form-control" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>" <?= $pedido->id_cliente == $cliente['id_cliente'] ? 'selected' : '' ?>>
                        <?= $cliente['nombre_cliente'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>
        <!-- Referencia -->
        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control" value="<?= esc($pedido->referencia) ?>">
        </div>
        <br>
        <!-- Fechas -->
        <div class="form-group">
            <label for="fecha_entrada">Fecha de Entrada:</label>
            <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" value="<?= esc($pedido->fecha_entrada) ?>" required>
        </div>
        <br>
        <div class="form-group">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?= esc($pedido->fecha_entrega) ?>" required>
        </div>
        <br>
        <!-- Observaciones -->
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control" rows="3"><?= esc($pedido->observaciones) ?></textarea>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Guardar Pedido</button>
        <br> <br>
    </form>
    <!-- Líneas del Pedido -->
    <div class="form-group">
        <h3>Líneas del Pedido</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Línea</th>
                    <th>Cantidad</th>
                    <th>Base</th>
                    <th>Producto</th>
                    <th>Estado</th>
                    <th>Medida Inicial</th>
                    <th>Medida Final</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lineas_pedido)): ?>
                    <?php foreach ($lineas_pedido as $linea): ?>
                        <tr>
                            <td><?= esc($linea['id_lineapedido']) ?></td>
                            <td><?= esc($linea['n_piezas']) ?></td>
                            <td><?= esc($linea['nom_base']) ?></td>
                            <td><?= esc($linea['id_producto']) ?></td>
                            <td><?= esc($linea['estado']) ?></td>
                            <td><?= esc($linea['med_inicial']) ?></td>
                            <td><?= esc($linea['med_final']) ?></td>
                            <td><?= esc($linea['total_linea']) ?> €</td>
                            <td>
                                <!-- Botón para abrir el modal de edición de la línea de pedido -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarLineaModal<?= $linea['id_lineapedido'] ?>">
                                    Editar
                                </button>
                                <!-- Aquí cargamos el modal desde otra vista -->
                                <?= view('editLineaPedido', ['linea' => $linea]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No se encontraron líneas de pedido.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= $this->endSection() ?>