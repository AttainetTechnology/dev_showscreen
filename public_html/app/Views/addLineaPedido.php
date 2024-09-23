<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery debe cargarse primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Luego carga Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal para añadir una nueva línea de pedido -->
<form id="addLineaPedidoForm" action="<?= base_url('pedidos/addLineaPedido') ?>" method="post">
    <input type="hidden" name="id_pedido" value="<?= esc($pedido['id_pedido']) ?>">
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
        <input type="number" name="n_piezas" class="form-control" >
    </div>

    <div class="form-group">
        <label for="precio_venta">Precio Venta:</label>
        <input type="number" step="0.01" name="precio_venta" class="form-control" >
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
        <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control" value="<?= esc($fecha_entrada) ?>">
    </div>

    <div class="form-group">
        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" value="<?= esc($fecha_entrega) ?>">
    </div>

    <button type="submit" class="btn btn-primary">Guardar Línea Pedido</button>
</form>
</div>
</div>
</div>
</div>
<script>
    $(document).ready(function() {
        // Cuando se hace clic en el botón para abrir el modal
        $('#openAddLineaPedidoModal').click(function() {
            var idPedido = $(this).data('id-pedido');

            // Hacer la solicitud AJAX para cargar el contenido del modal
            $.ajax({
                url: '<?= base_url("pedidos/mostrarFormularioAddLineaPedido") ?>/' + idPedido,
                method: 'GET',
                success: function(response) {
                    // Cargar el contenido en el cuerpo del modal
                    $('#modalBodyAddLineaPedido').html(response);
                    // Mostrar el modal
                    $('#addLineaPedidoModal').modal('show');
                }
            });
        });

        // Gestión del envío del formulario
        $('#addLineaPedidoForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                type: 'post',
                data: formData,
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('No se pudo guardar la línea de pedido.');
                }
            });
        });
    });

</script>
