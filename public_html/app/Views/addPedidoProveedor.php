
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h2 class="mb-4">Añadir Pedido</h2>
    <form action="<?= base_url('Pedidos_proveedor/save') ?>" method="post">
    <div class="form-group mb-3">
            <label for="id_cliente">Empresa:</label>
            <select id="id_cliente" name="id_proveedor" class="form-control" required>
                <option value="" selected disabled hidden>Seleccione empresa</option>
                <?php foreach ($proveedores as $proveedor) : ?>
                    <option value="<?= $proveedor['id_proveedor'] ?>">
                        <?= $proveedor['nombre_proveedor'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <div class="form-group mb-3">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label for="fecha_salida">Fecha de Salida:</label>
            <input type="date" id="fecha_salida" name="fecha_salida" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control"></textarea>
        </div>

        <div class="form-group mb-4">
            <label for="id_usuario">Hace el pedido:</label>
            <?= $usuario_html; ?>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Pedido</button>
    </form>
</div>

<?= $this->endSection() ?>
