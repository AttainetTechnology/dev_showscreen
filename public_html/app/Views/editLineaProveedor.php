<form id="editLineaPedidoForm" method="post">
    <input type="hidden" name="id_lineapedido" value="<?= $lineaPedido['id_lineapedido'] ?>">
    <input type="hidden" name="id_pedido" value="<?= $lineaPedido['id_pedido'] ?>"> 
    <div class="form-group">
        <label for="id_producto">Producto</label>
        <select name="id_producto" class="form-control" required>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['id_producto'] ?>" <?= $producto['id_producto'] == $lineaPedido['id_producto'] ? 'selected' : '' ?>>
                    <?= $producto['nombre_producto'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="n_piezas">Cantidad (Uds.)</label>
        <input type="number" name="n_piezas" class="form-control" value="<?= $lineaPedido['n_piezas'] ?>" required>
    </div>
    <div class="form-group">
        <label for="precio_compra">Precio de Compra (€)</label>
        <input type="text" name="precio_compra" class="form-control" value="<?= $lineaPedido['precio_compra'] ?>" required>
    </div>
    <div class="form-group">
        <label for="estado">Estado</label>
        <select name="estado" class="form-control" required>
            <option value="0" <?= $lineaPedido['estado'] == 0 ? 'selected' : '' ?>>Pendiente de realizar</option>
            <option value="1" <?= $lineaPedido['estado'] == 1 ? 'selected' : '' ?>>Pendiente de recibir</option>
            <option value="2" <?= $lineaPedido['estado'] == 2 ? 'selected' : '' ?>>Recibido</option>
            <option value="6" <?= $lineaPedido['estado'] == 6 ? 'selected' : '' ?>>Anulado</option>
        </select>
    </div>
    <div class="form-group">
        <label for="observaciones">Observaciones</label>
        <textarea name="observaciones" class="form-control"><?= $lineaPedido['observaciones'] ?></textarea>
    </div>
</form>
