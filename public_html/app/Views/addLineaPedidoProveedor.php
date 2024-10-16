<form id="addLineaPedidoForm">
    <input type="hidden" name="id_pedido" value="<?= $id_pedido ?>">
    <div class="form-group">
        <label for="id_producto">Producto</label>
        <select name="id_producto" id="id_producto" class="form-control" required>
            <option value="">Seleccione un producto</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['id_producto'] ?>"><?= $producto['nombre_producto'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="n_piezas">Número de Piezas</label>
        <input type="number" name="n_piezas" id="n_piezas" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="observaciones">Observaciones</label>
        <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
    </div>
</form>
