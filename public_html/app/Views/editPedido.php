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
            <!-- /.modal -->

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

        <!-- Cliente -->
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Empresa</label>
            <select id="id_cliente" name="id_cliente" class="form-select" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente']; ?>" <?= $pedido->id_cliente == $cliente['id_cliente'] ? 'selected' : ''; ?>>
                        <?= $cliente['nombre_cliente']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Referencia -->
        <div class="mb-3">
            <label for="referencia" class="form-label">Referencia</label>
            <input type="text" class="form-control" id="referencia" name="referencia" value="Ref123" required>
        </div>

        <!-- Fecha Entrada -->
        <div class="mb-3">
            <label for="fecha_entrada" class="form-label">Fecha de Entrada</label>
            <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada" value="2024-09-17" required>
        </div>

        <!-- Fecha Entrega -->
        <div class="mb-3">
            <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" value="2024-09-20">
        </div>

        <!-- Observaciones -->
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea id="observaciones" name="observaciones" class="form-control" rows="3"></textarea>
        </div>

        <!-- Id Pedido (No editable) -->
        <div class="mb-3">
            <label for="id_pedido" class="form-label">Id Pedido</label>
            <input type="text" class="form-control" id="id_pedido" name="id_pedido" value="12345" readonly>
        </div>
        <br>
        <!-- Usuario -->
        <div class="mb-3" style="text-align: right;">
            <label for="id_usuario" class="form-label">Hace el pedido</label>
            <input type="hidden" name="id_usuario" value="<?= esc($pedido->id_usuario); ?>">
            <p><b><?= esc($pedido->nombre_usuario) . ' ' . esc($pedido->apellidos_usuario); ?></b></p>
        </div>

        <!-- Botón de Enviar -->
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
<?= $this->endSection() ?>