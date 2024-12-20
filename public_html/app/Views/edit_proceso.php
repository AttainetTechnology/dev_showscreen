<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="modal fade show" id="editProcesoModal" tabindex="-1" role="dialog" aria-labelledby="editProcesoModalLabel" aria-hidden="true" style="display: block;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProcesoModalLabel">Editar Proceso</h5>
                <button type="button" class="btn-close-custom" aria-label="Close" onclick="window.location.href='<?= base_url('procesos') ?>'">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <form id="editProcesoForm" action="<?= base_url('procesos/update') ?>" method="post">
                    <input type="hidden" name="id_proceso" value="<?= isset($proceso) ? $proceso->id_proceso : '' ?>">
                    <div class="form-group">
                        <label for="nombre_proceso">Nombre del Proceso</label>
                        <input type="text" class="form-control" id="nombre_proceso" name="nombre_proceso" value="<?= isset($proceso) ? $proceso->nombre_proceso : '' ?>" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="estado_proceso">Estado del Proceso</label>
                        <select class="form-control" id="estado_proceso" name="estado_proceso">
                            <option value="1" <?= isset($proceso) && $proceso->estado_proceso == '1' ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?= isset($proceso) && $proceso->estado_proceso == '0' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary float-end">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
