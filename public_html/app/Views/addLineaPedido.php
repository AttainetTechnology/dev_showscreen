<!-- Enlaces a Bootstrap, jQuery y Select2 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<style>
    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px); 
        padding: 0.375rem 0.75rem; 
        line-height: 1.5;
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        box-shadow: none; 
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
    }
    .form-group select {
        width: 100%;
    }
    .select2-container {
        width: 100% !important;
    }
</style>

<!-- Formulario dentro del modal -->
<form id="addLineaPedidoForm" action="<?= base_url('pedidos/addLineaPedido') ?>" method="post">
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Guardar Línea Pedido</button>
    </div>

    <input type="hidden" name="id_pedido" value="<?= esc($pedido['id_pedido']) ?>">
    
    <div class="form-group">
        <label for="n_piezas">Cantidad:</label>
        <input type="number" name="n_piezas" class="form-control">
    </div>

    <div class="form-group">
        <label for="id_producto">Producto <span class="text-danger">*</span>:</label>
        <select id="id_producto" name="id_producto" class="form-control" required>
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
        <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control" value="<?= esc($fecha_entrada) ?>">
    </div>

    <div class="form-group">
        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" value="<?= esc($fecha_entrega) ?>">
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar Línea Pedido</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#id_producto').select2({
            placeholder: "Selecciona un producto",
            dropdownParent: $('#addLineaPedidoForm') 
        });
    });
</script>
