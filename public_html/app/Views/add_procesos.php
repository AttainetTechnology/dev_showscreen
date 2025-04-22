<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="modal fade show" id="addProcessModal" tabindex="-1" role="dialog" aria-labelledby="addProcessModalLabel" aria-hidden="true" style="display: block;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProcessModalLabel">Añadir Nuevo Proceso</h5>
                <button type="button" class="btn-close" onclick="window.location.href='<?= base_url('procesos'); ?>'">&times;</button>
            </div>
            <form action="<?= base_url('procesos/create'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_proceso">Nombre del Proceso</label>
                        <input type="text" class="form-control" id="nombre_proceso" name="nombre_proceso" required>
                    </div>
                    <div class="form-group">
                        <label for="maq_preasignada">Máquina preasignada</label>
                        <select class="form-control" id="maq_preasignada" name="maq_preasignada">
                            <option value="">Selecciona una máquina</option>
                            <?php foreach ($maquinas as $maquina): ?>
                                <option value="<?= $maquina['id_maquina'] ?>"><?= esc($maquina['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="estado_proceso" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= base_url('procesos'); ?>'">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    document.getElementById('addProcessModal').addEventListener('submit', function(event) {
        const nombreProcesoInput = document.getElementById('nombre_proceso');
        let nombreProceso = nombreProcesoInput.value; 
        if (nombreProceso.includes('.')) {
            alert('No se permite el uso de puntos en el nombre del proceso.');
            event.preventDefault(); 
        }
        nombreProceso = nombreProceso.toUpperCase(); 
        nombreProcesoInput.value = nombreProceso;
    });
    $(document).ready(function() {
        $('#addProcessModal').modal('show');
        $('#addProcessModal').on('hidden.bs.modal', function() {
            window.location.href = '<?= base_url('procesos'); ?>';
        });
        $(document).on('click', function(event) {
            var clickInsideModal = $(event.target).closest('.modal-content').length;
            if (!clickInsideModal) {
                $('#addProcessModal').modal('hide');
            }
        });
    });
</script>
<?= $this->endSection() ?>