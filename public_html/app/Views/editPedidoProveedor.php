<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2>Editar Pedido del Proveedor</h2>

<form action="<?= base_url('pedidos_proveedor/update/' . $pedido['id_pedido']) ?>" method="post" class="formEditPedido">
    <div class="mb-3">
        <label for="id_proveedor" class="form-label">Proveedor</label>
        <select name="id_proveedor" id="id_proveedor" class="form-select" required>
            <option value="">Seleccione un proveedor</option>
            <?php foreach ($proveedores as $prov): ?>
                <option value="<?= $prov['id_proveedor'] ?>" <?= $prov['id_proveedor'] == $pedido['id_proveedor'] ? 'selected' : '' ?>>
                    <?= $prov['nombre_proveedor'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="referencia" class="form-label">Referencia</label>
        <input type="text" name="referencia" id="referencia" class="form-control" value="<?= $pedido['referencia'] ?>" >
    </div>

    <div class="mb-3">
        <label for="observaciones" class="form-label">Observaciones</label>
        <textarea name="observaciones" id="observaciones" class="form-control"><?= $pedido['observaciones'] ?></textarea>
    </div>

    <div class="mb-3">
        <label for="fecha_salida" class="form-label">Fecha de Salida</label>
        <input type="date" name="fecha_salida" id="fecha_salida" class="form-control" value="<?= $pedido['fecha_salida'] ?>" required>
    </div>


    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" id="estado" class="form-select" required>
            <?php foreach ($estados as $key => $estado): ?>
                <option value="<?= $key ?>" <?= $key == $pedido['estado'] ? 'selected' : '' ?>><?= $estado ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="buttonsEditPedido">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="<?= base_url('pedidos_proveedor') ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?= $this->endSection() ?>
