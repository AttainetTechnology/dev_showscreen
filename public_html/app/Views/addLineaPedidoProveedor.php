<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<form id="addLineaPedidoForm">
    <input type="hidden" name="id_pedido" value="<?= $id_pedido ?>">

    <div class="form-group">
        <label for="ref_producto">Producto</label>
        <select name="ref_producto" id="ref_producto" class="form-control" required>
            <option value="">Seleccione un producto</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['ref_producto'] ?>">
                    <?= $producto['ref_producto'] ?>
                </option>
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
