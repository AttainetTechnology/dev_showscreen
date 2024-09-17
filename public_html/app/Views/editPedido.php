<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h2>Editar Pedido</h2>

    <!-- Botones de Acción -->
    <div class="mb-3">
        <label for="acciones" class="form-label"></label>
        <div class="d-flex gap-2">
            <a href="imprimir_ruta_pedido" class="btn btn-info btn-sm" target="_blank">
                <i class="fa fa-print"></i> Imprimir Pedido
            </a>
            <a href="parte_complejo_ruta_pedido" class="btn btn-secondary btn-sm" target="_blank">
                <i class="fa fa-truck"></i> Parte Complejo
            </a>
            <a href="entregar_ruta_pedido" class="btn btn-success btn-sm">
                <i class="fa fa-check"></i> Entregar Pedido
            </a>
            <a href="anular_ruta_pedido" class="btn btn-danger btn-sm">
                <i class="fa fa-trash"></i> Anular Pedido
            </a>
        </div>
        <br>
    </div>
    <form action="<?= base_url('pedidos/update/' . $pedido->id_pedido) ?>" method="post">
        <!-- Id Pedido (No editable) -->
        <div class="mb-3">
            <label for="id_pedido" class="form-label">Id Pedido</label>
            <input type="text" class="form-control" id="id_pedido" name="id_pedido" value="12345" readonly>
        </div>

        <!-- Cliente -->
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Empresa (Cliente)</label>
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

        <!-- Usuario -->
        <div class="mb-3">
            <label for="id_usuario" class="form-label">Hace el pedido (Usuario)</label>
            <select id="id_usuario" name="id_usuario" class="form-select" required>
            </select>
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

        <!-- Estado -->
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" name="estado" class="form-select" required>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id_estado']; ?>" <?= $pedido->estado == $estado['id_estado'] ? 'selected' : ''; ?>>
                        <?= $estado['nombre_estado']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Observaciones -->
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea id="observaciones" name="observaciones" class="form-control" rows="3"></textarea>
        </div>

        <!-- Botón de Enviar -->
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
<?= $this->endSection() ?>