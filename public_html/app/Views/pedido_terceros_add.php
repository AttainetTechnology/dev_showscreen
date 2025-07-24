<!-- filepath: app/Views/pedido_terceros_add.php -->
<form id="formAddProveedorPedido"> 
    <input type="hidden" name="id_ped_terceros"  id="id_ped_terceros" value="<?= esc($id_pedido) ?>">
    <div class="mb-3">
        <label>Proveedor</label>
       <select name="id_proveedor" id="id_proveedor" class="form-control" required>
        <option value="">Selecciona un proveedor</option>
        <?php foreach ($proveedores as $prov): ?>
            <option value="<?= esc($prov['id_proveedor']) ?>"><?= esc($prov['nombre_proveedor']) ?></option>
        <?php endforeach; ?>
    </select>
    </div>
    <div class="mb-3">
        <label>Producto</label>
        <select name="id_producto" id="id_producto" class="form-control" required>
            <option value="">Selecciona un producto</option>
        </select>
    </div>
    </script>
   
    <div class="mb-3">
        <label>Cantidad</label>
        <input type="number" name="cantidad" class="form-control" min="1" required>
    </div>
    <div class="mb-3">
        <label>Observaciones</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
<script>
$('#formAddProveedorPedido').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: '<?= base_url("Pedidos_terceros/creaPedidoTerceros") ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(){
            $('#pedidoTercerosModal').modal('hide');
            location.reload(); // <-- Esto recarga la página actual
        }
    });
});
</script>
<script>
$('#id_proveedor').on('change', function() {
    var idProveedor = $(this).val();
    var $productoSelect = $('#id_producto');
    $productoSelect.html('<option value="">Cargando productos...</option>');

    if (idProveedor) {
        $.ajax({
            url: '<?= base_url("Pedidos_terceros/productosPorProveedor") ?>/' + idProveedor,
            type: 'GET',
            success: function(productos) {
                var options = '<option value="">Selecciona un producto</option>';
                productos.forEach(function(prod) {
                    options += '<option value="' + prod.id + '">' + prod.ref_producto + '</option>';
                });
                $productoSelect.html(options);
            },
            error: function() {
                $productoSelect.html('<option value="">Error al cargar productos</option>');
            }
        });
    } else {
        $productoSelect.html('<option value="">Selecciona un producto</option>');
    }
});
</script>