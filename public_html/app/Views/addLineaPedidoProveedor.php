<form id="addLineaPedidoForm">
    <!-- Campo oculto para el ID del pedido -->
    <input type="hidden" name="id_pedido" value="<?= $id_pedido ?>">

    <!-- Campo para seleccionar el producto -->
    <div class="form-group">
        <label for="id_producto">Producto</label>
        <select name="id_producto" id="id_producto" class="form-control" required>
            <option value="">Seleccione un producto</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['id_producto'] ?>"><?= $producto['nombre_producto'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Campo para ingresar el número de piezas -->
    <div class="form-group">
        <label for="n_piezas">Número de Piezas</label>
        <input type="number" name="n_piezas" id="n_piezas" class="form-control" required>
    </div>

    <!-- Campo para agregar observaciones -->
    <div class="form-group">
        <label for="observaciones">Observaciones</label>
        <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
    </div>
</form>
