<!-- Modal para añadir una nueva línea de pedido -->
<div class="modal fade" id="addLineaPedidoModal" tabindex="-1" aria-labelledby="addLineaPedidoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLineaPedidoLabel">Añadir Línea de Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para añadir una nueva línea de pedido -->
                <form id="addLineaPedidoForm" action="<?= base_url('pedidos/addLineaPedido') ?>" method="post">
                <input type="hidden" name="id_pedido" value="<?= esc($pedido->id_pedido) ?>">
                    <div class="form-group">
                        <label for="id_producto">Producto <span class="text-danger">*</span>:</label>
                        <select name="id_producto" class="form-control" required>
                            <option value="">Selecciona un producto</option>
                            <?php if (!empty($productos)): ?>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= esc($producto['id_producto']) ?>"><?= esc($producto['nombre_producto']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No hay productos disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Campos opcionales -->
                    <div class="form-group">
                        <label for="n_piezas">Cantidad:</label>
                        <input type="number" name="n_piezas" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="precio_venta">Precio Venta:</label>
                        <input type="number" step="0.01" name="precio_venta" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nom_base">Base:</label>
                        <input type="text" name="nom_base" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="med_inicial">Medida Inicial:</label>
                        <input type="text" name="med_inicial" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="med_final">Medida Final:</label>
                        <input type="text" name="med_final" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="lado">Lado:</label>
                        <input type="text" name="lado" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="distancia">Distancia:</label>
                        <input type="text" name="distancia" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones:</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="fecha_entrada">Fecha de Entrada:</label>
                        <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fecha_entrega">Fecha de Entrega:</label>
                        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
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
    $(document).ready(function () {
        $('#addLineaPedidoForm').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                type: 'post',
                data: formData,
                success: function (response) {
                    location.reload();
                },
            });
        });
    });
</script>
