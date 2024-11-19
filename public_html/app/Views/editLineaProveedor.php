<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<form id="editLineaPedidoForm" method="post">
    <input type="hidden" name="id_lineapedido" value="<?= isset($lineaPedido['id_lineapedido']) ? $lineaPedido['id_lineapedido'] : '' ?>">
    <input type="hidden" name="id_pedido" value="<?= isset($lineaPedido['id_pedido']) ? $lineaPedido['id_pedido'] : '' ?>">
    <div class="form-group">
        <label for="ref_producto">Producto</label>
        <select name="ref_producto" id="ref_producto" class="form-control" required>
            <option value="">Seleccione un producto</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= esc($producto['ref_producto']) ?>" <?= isset($lineaPedido['ref_producto']) && $lineaPedido['ref_producto'] == $producto['ref_producto'] ? 'selected' : '' ?>>
                    <?= esc($producto['ref_producto']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="n_piezas">Cantidad (Uds.)</label>
        <input type="number" name="n_piezas" class="form-control"
            value="<?= isset($lineaPedido['n_piezas']) ? $lineaPedido['n_piezas'] : '' ?>" required>
    </div>

    <div class="form-group">
        <label for="precio_compra">Precio de Compra (€)</label>
        <input type="text" name="precio_compra" class="form-control"
            value="<?= isset($lineaPedido['precio_compra']) ? $lineaPedido['precio_compra'] : '' ?>" required>
    </div>

    <div class="form-group">
        <label for="unidad_precio">Unidad de Precio</label>
        <input type="text" name="unidad_precio" id="unidad_precio" class="form-control"
            value="<?= isset($lineaPedido['unidad_precio']) ? $lineaPedido['unidad_precio'] : '' ?>">
    </div>
    <div class="form-group">
        <label for="estado">Estado</label>
        <select name="estado" class="form-control" required>
            <option value="0" <?= isset($lineaPedido['estado']) && $lineaPedido['estado'] == 0 ? 'selected' : '' ?>>Pendiente de realizar</option>
            <option value="1" <?= isset($lineaPedido['estado']) && $lineaPedido['estado'] == 1 ? 'selected' : '' ?>>Pendiente de recibir</option>
            <option value="2" <?= isset($lineaPedido['estado']) && $lineaPedido['estado'] == 2 ? 'selected' : '' ?>>Recibido</option>
            <option value="6" <?= isset($lineaPedido['estado']) && $lineaPedido['estado'] == 6 ? 'selected' : '' ?>>Anulado</option>
        </select>
    </div>

    <div class="form-group">
        <label for="observaciones">Observaciones</label>
        <textarea name="observaciones" class="form-control"><?= isset($lineaPedido['observaciones']) ? $lineaPedido['observaciones'] : '' ?></textarea>
    </div>
</form>