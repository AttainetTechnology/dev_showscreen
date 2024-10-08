<div class="modal-header">
    <h5 class="modal-title" id="elegirProveedorModalLabel">Elegir Proveedor para el Producto</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
</div>
<div class="modal-body">
    <form action="<?= base_url('proveedores/asociarProveedor') ?>" method="post" id="formElegirProveedor">
        <input type="hidden" name="id_producto" value="<?= esc($id_producto) ?>">

        <div class="form-group mb-3">
            <label for="proveedor">Proveedor</label>
            <select name="id_proveedor" id="proveedor" class="form-control" required>
                <option value="" disabled selected>Selecciona un proveedor</option>
                <?php foreach ($proveedores as $proveedor): ?>
                    <option value="<?= esc($proveedor['id_proveedor']) ?>"><?= esc($proveedor['nombre_proveedor']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="ref_producto">Referencia del Producto</label>
            <input type="text" name="ref_producto" id="ref_producto" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="precio">Precio</label>
            <input type="text" name="precio" id="precio" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Asociar Proveedor</button>
    </form>
</div>
