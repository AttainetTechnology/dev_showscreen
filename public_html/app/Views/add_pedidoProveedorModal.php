<div class="modal-header">
    <h5 class="modal-title" id="addPedidoModalLabel">Añadir Pedido</h5>
    <button type="button" class="btn-close-custom" aria-label="Close" onclick="$('#addPedidoModal').modal('hide')">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="<?= base_url('Pedidos_proveedor/save') ?>" method="post">
    <div class="form-group">
    <label for="id_cliente">Empresa:</label>
    <select id="id_cliente" name="id_proveedor" class="form-control">
        <option value="" disabled hidden>Seleccione empresa</option>
        <?php foreach ($proveedores as $proveedor) : ?>
            <option value="<?= $proveedor['id_proveedor'] ?>" <?= ($id_proveedor_seleccionado == $proveedor['id_proveedor']) ? 'selected' : '' ?>>
                <?= $proveedor['nombre_proveedor'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
        <br>
        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control">
        </div>
        <br>
        <div class="form-group">
            <label for="fecha_salida">Fecha de Salida:</label>
            <input type="date" id="fecha_salida" name="fecha_salida" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <br>
        <br>
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control"></textarea>
        </div>
        <br>
        <div class="form-group">
            <label for="id_usuario">Hace el pedido:</label>
            <?= $usuario_html; ?>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Guardar Pedido</button>
    </form>
</div>
