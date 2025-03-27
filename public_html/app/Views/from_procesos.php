<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2>Editar Proceso</h2>

    <form action="<?= base_url('procesos/edit/' . $proceso['id_proceso']); ?>" method="post">
        <div class="form-group">
            <label for="nombre_proceso">Nombre del Proceso</label>
            <input type="text" class="form-control" id="nombre_proceso" name="nombre_proceso" value="<?= esc($proceso['nombre_proceso']); ?>" required>
        </div>
        <div class="form-group">
            <label for="estado_proceso">Estado del Proceso</label>
            <select class="form-control" id="estado_proceso" name="estado_proceso" required>
                <option value="activo" <?= $proceso['estado_proceso'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="inactivo" <?= $proceso['estado_proceso'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="<?= base_url('procesos'); ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?= $this->endSection() ?>
