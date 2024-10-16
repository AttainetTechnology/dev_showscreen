<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<div class="modal-header">
    <h5 class="modal-title" id="addLineaPedidoLabel" style="margin-left: 0px;">Editar Línea de Pedido</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="content-body">
    <form class="editLineaForm" action="<?= base_url('pedidos/updateLineaPedido/' . $linea_pedido['id_lineapedido']) ?>" method="post" data-linea-id="<?= $linea_pedido['id_lineapedido'] ?>">
        <input type="hidden" name="id_pedido" value="<?= esc($linea_pedido['id_pedido']) ?>">
        <br>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary ">Guardar Cambios</button>
        </div>
        <div class="form-group">
            <label for="id_producto">Producto:</label>
            <select name="id_producto" class="form-control" required>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?= esc($producto['id_producto']) ?>" <?= ($producto['id_producto'] == $linea_pedido['id_producto']) ? 'selected' : '' ?>>
                        <?= esc($producto['nombre_producto']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="n_piezas">Cantidad:</label>
            <input type="number" name="n_piezas" class="form-control" value="<?= esc($linea_pedido['n_piezas']) ?>" required>
        </div>
        <div class="form-group">
            <label for="precio_venta">Precio Venta:</label>
            <input type="number" step="0.01" name="precio_venta" class="form-control" value="<?= esc($linea_pedido['precio_venta']) ?>" required>
        </div>
        <div class="form-group">
            <label for="nom_base">Base:</label>
            <input type="text" name="nom_base" class="form-control" value="<?= esc($linea_pedido['nom_base']) ?>">
        </div>
        <div class="form-group">
            <label for="med_inicial">Medida Inicial:</label>
            <input type="text" name="med_inicial" class="form-control" value="<?= esc($linea_pedido['med_inicial']) ?>">
        </div>
        <div class="form-group">
            <label for="med_final">Medida Final:</label>
            <input type="text" name="med_final" class="form-control" value="<?= esc($linea_pedido['med_final']) ?>">
        </div>
        <div class="form-group">
            <label for="lado">Lado:</label>
            <input type="text" name="lado" class="form-control" value="<?= esc($linea_pedido['lado']) ?>">
        </div>
        <div class="form-group">
            <label for="distancia">Distancia:</label>
            <input type="text" name="distancia" class="form-control" value="<?= esc($linea_pedido['distancia']) ?>">
        </div>
        <div class="form-group">
            <label for="estado">Estado:</label>
            <select name="estado" class="form-control" required>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= esc($estado['id_estado']) ?>" <?= ($estado['id_estado'] == $linea_pedido['estado']) ? 'selected' : '' ?>>
                        <?= esc($estado['nombre_estado']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fecha_entrada">Fecha de Entrada:</label>
            <input type="date" name="fecha_entrada" class="form-control" value="<?= esc($linea_pedido['fecha_entrada']) ?>">
        </div>
        <div class="form-group">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" name="fecha_entrega" class="form-control" value="<?= esc($linea_pedido['fecha_entrega']) ?>">
        </div>
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea name="observaciones" class="form-control" rows="3"><?= esc($linea_pedido['observaciones']) ?></textarea>
        </div>
        <br>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <button type="button" class="btn btn-secondary volverButton" data-bs-dismiss="modal">Cerrar</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.editLineaForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var lineaId = form.data('linea-id');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editarLineaModal' + lineaId).modal('hide');
                        location.reload();
                    } else {
                        alert(response.error || 'Hubo un error al actualizar la línea de pedido.');
                    }
                },
                error: function(response) {
                    console.error('Error al enviar la solicitud:', response);
                    alert('Hubo un error al actualizar la línea de pedido.');
                }
            });
        });
    });
</script>