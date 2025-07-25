<!-- filepath: app/Views/pedidosProveedor/pedido_terceros_edit.php -->
 <form id="formEditPedidoTerceros">
 <div id="erroresPedidoTerceros" class="alert alert-danger" style="display:none"></div>

    <div class="mb-3">
        <input type="hidden" name="id_pedido_cliente" id="id_pedido_cliente" value="<?= esc($id_pedido ?? $pedido['id_pedido_cliente'] ?? '') ?>">
        <input type="hidden" name="id_ped_terceros" value="<?= esc($pedido['id_ped_terceros'] ?? '') ?>">
        <label>Proveedor</label>
        <select name="id_proveedor" id="id_proveedor" class="form-control" required>
            <option value="">Selecciona un proveedor</option>
            <?php foreach ($proveedores as $prov): ?>
            <option value="<?= esc($prov['id_proveedor']) ?>" <?= isset($pedido) && $pedido['id_proveedor'] == $prov['id_proveedor'] ? 'selected' : '' ?>>
                <?= esc($prov['nombre_proveedor']) ?>
            </option>
            <?php endforeach; ?>
        </select> 
    </div> 
    <div class="mb-3">
       <label>Producto</label>
    <select name="id_producto" id="id_producto" class="form-control" required>
        <option value="">Selecciona un producto</option>
        <?php if (isset($productos_actuales) && is_array($productos_actuales)): ?>
            <?php foreach ($productos_actuales as $prod): ?>
                <option value="<?= esc($prod['id']) ?>" <?= isset($pedido) && $pedido['id_producto'] == $prod['id'] ? 'selected' : '' ?>>
                    <?= esc($prod['ref_producto']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    </div>
    <div class="mb-3">
        <label>Estado</label>
        <select name="estado" id="estado" class="form-control" required>
            <option value="0" <?= isset($pedido) && $pedido['estado'] == 0 ? 'selected' : '' ?>>Pendiente</option>
            <option value="1" <?= isset($pedido) && $pedido['estado'] == 1 ? 'selected' : '' ?>>Enviado</option>
            <option value="2" <?= isset($pedido) && $pedido['estado'] == 2 ? 'selected' : '' ?>>Recibido</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Cantidad</label>
        <input type="number" name="cantidad" class="form-control" min="1" required value="<?= isset($pedido) ? esc($pedido['cantidad']) : '' ?>">
    </div>
    <div class="mb-3">
        <label>Fecha de creación</label>
        <input type="date" name="fecha_creacion" class="form-control" required value="<?= isset($pedido) ? esc($pedido['fecha_creacion']) : '' ?>">
    </div>
    <div class="mb-3">
        <label>Fecha de recepción</label>
        <input type="date" name="fecha_recepcion" class="form-control" value="<?= isset($pedido) ? esc($pedido['fecha_recepcion']) : '' ?>">
    </div>
    <div class="mb-3">
        <label>Observaciones</label>
        <textarea name="observaciones" class="form-control"><?= isset($pedido) ? esc($pedido['observaciones']) : '' ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
<script>
$('#formEditPedidoTerceros').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: '<?= base_url("Pedidos_terceros/guardar") ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(){
            $('#pedidoTercerosModal').modal('hide');
            location.reload(); // <-- Esto recarga la página actual
        }
    });
});
</script>
