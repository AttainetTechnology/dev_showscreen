<!-- Modal para editar línea de pedido -->
<div class="modal fade" id="editarLineaModal<?= $linea['id_lineapedido'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Línea de Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar la línea del pedido -->
                <form class="editLineaForm" action="<?= base_url('pedidos/updateLineaPedido/' . $linea['id_lineapedido']) ?>" method="post" data-linea-id="<?= $linea['id_lineapedido'] ?>">
                    <input type="hidden" name="id_pedido" value="<?= esc($pedido->id_pedido) ?>">

                    <div class="form-group">
                        <label for="id_producto">Producto:</label>
                        <input type="text" name="id_producto" class="form-control" value="<?= esc($linea['id_producto']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="n_piezas">Cantidad:</label>
                        <input type="number" name="n_piezas" class="form-control" value="<?= esc($linea['n_piezas']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="precio_venta">Precio Venta:</label>
                        <input type="number" step="0.01" name="precio_venta" class="form-control" value="<?= esc($linea['precio_venta']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="nom_base">Base:</label>
                        <input type="text" name="nom_base" class="form-control" value="<?= esc($linea['nom_base']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="med_inicial">Medida Inicial:</label>
                        <input type="text" name="med_inicial" class="form-control" value="<?= esc($linea['med_inicial']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="med_final">Medida Final:</label>
                        <input type="text" name="med_final" class="form-control" value="<?= esc($linea['med_final']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="lado">Lado:</label>
                        <input type="text" name="lado" class="form-control" value="<?= esc($linea['lado']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="distancia">Distancia:</label>
                        <input type="text" name="distancia" class="form-control" value="<?= esc($linea['distancia']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="observaciones">Observaciones:</label>
                        <textarea name="observaciones" class="form-control" rows="2"><?= esc($linea['observaciones']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <input type="text" name="estado" class="form-control" value="<?= esc($linea['estado']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_entrada">Fecha de Entrada:</label>
                        <input type="date" name="fecha_entrada" class="form-control" value="<?= esc($linea['fecha_entrada']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="fecha_entrega">Fecha de Entrega:</label>
                        <input type="date" name="fecha_entrega" class="form-control" value="<?= esc($linea['fecha_entrega']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Línea Pedido</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Añade el evento a los formularios que usan la clase "editLineaForm"
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