<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">

<h2 class="titleEditProductoProveedor">Editar Producto</h2>
<div class="buttonsEditProductProveed">
    <button type="button" class="btn mb-3 btnVendemosProd" id="abrirModalProducto">¿Vendemos este producto?</button>
</div>
<form action="<?= base_url('productos_necesidad/update/' . $producto['id_producto']) ?>" method="post" enctype="multipart/form-data" class="fromEditProductProveed">
    <div class="mb-3">
        <label for="nombre_producto" class="form-label">Nombre del Producto</label>
        <input type="text" name="nombre_producto" id="nombre_producto" class="form-control" value="<?= $producto['nombre_producto'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="id_familia" class="form-label">Familia</label>
        <select name="id_familia" id="id_familia" class="form-select" required>
            <option value="">Selecciona una familia</option>
            <?php foreach ($familias as $familia): ?>
                <option value="<?= $familia['id_familia'] ?>" <?= $producto['id_familia'] == $familia['id_familia'] ? 'selected' : '' ?>><?= $familia['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
    <label for="imagen" class="form-label">Imagen</label>
    <input type="file" name="imagen" id="imagen" class="form-control">
    <?php if ($producto['imagen']): ?>
        <p>Imagen actual: 
            <img src="<?= base_url("public/assets/uploads/files/{$id_empresa}/productos_necesidad/{$producto['id_producto']}/" . $producto['imagen']) ?>" height="60">
        </p>
        <button type="button" class="btn btn-danger mt-2" id="eliminarImagenButton">Eliminar Imagen</button>
    <?php endif; ?>
</div>

    <div class="mb-3">
        <label for="unidad" class="form-label">Unidad</label>
        <input type="text" name="unidad" id="unidad" class="form-control" value="<?= $producto['unidad'] ?>">
    </div>

    <div class="mb-3">
        <label for="estado_producto" class="form-label">Estado del Producto</label>
        <select name="estado_producto" id="estado_producto" class="form-select" required>
            <option value="Activo" <?= $producto['estado_producto'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
            <option value="Inactivo" <?= $producto['estado_producto'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Producto que vendemos</label>
        <input type="text" class="form-control" value="<?= $productoVentaNombre ?>" readonly>
    </div>
    <div class="buttonsEditProductProveedAbajo">
    <button type="submit" class="btn btn-primary" >Guardar Cambios</button>
    <button type="button" class="btn mb-3" id="volverButton" style="margin-top:15px;">Volver</button>
</div>
</form>
<!-- Modal HTML -->
<div class="modal fade" id="productoModal" tabindex="-1" role="dialog" aria-labelledby="productoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content" style="height: 100vh;">
            <div class="modal-header">
                <h5 class="modal-title" id="productoModalLabel">Selecciona un producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productosModalBody" style="overflow-y: auto; max-height: calc(100vh - 150px);">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#eliminarImagenButton').on('click', function() {
            if (confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
                $.ajax({
                    url: '<?= base_url('productos_necesidad/eliminarImagen/' . $producto['id_producto']) ?>',
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload(); 
                        } else {
                            location.reload(); 
                        }
                    },
                    error: function() {
                        alert("Error al intentar eliminar la imagen.");
                    }
                });
            }
        });
        $('#abrirModalProducto').on('click', function() {
            $.ajax({
                url: '<?= base_url('productos_necesidad/verProductos/' . $producto['id_producto']) ?>',
                method: 'GET',
                success: function(response) {
                    $('#productosModalBody').html(response); 
                    $('#productoModal').modal('show');
                },
                error: function() {
                    alert('Error al cargar el modal.');
                }
            });
        });

        $('#productoModal').on('hidden.bs.modal', function() {
            window.location.href = '<?= base_url('productos_necesidad/edit/' . $producto['id_producto']) ?>';
        });
    });
    document.getElementById('volverButton').addEventListener('click', function() {
        window.location.href = '<?= base_url('productos_necesidad') ?>';
    });
</script>
<?= $this->endSection() ?>