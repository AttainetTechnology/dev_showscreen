<!-- edit_incidencias.php -->
<div class="modal fade" id="editIncidenciaModal" tabindex="-1" role="dialog" aria-labelledby="editIncidenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editIncidenciaModalLabel">Editar Incidencia</h5>
                <button type="button" class="btn-close-custom" aria-label="Close" data-dismiss="modal">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <form id="editIncidenciaForm" action="<?= base_url('index/guardar') ?>" method="post">
                    <div class="form-group">
                        <label for="entradaHora">Hora de Entrada</label>
                        <input type="time" class="form-control" id="entradaHora" name="entrada_hora" required>
                    </div>
                    <div class="form-group">
                        <label for="salidaHora">Hora de Salida</label>
                        <input type="time" class="form-control" id="salidaHora" name="salida_hora">
                    <button type="submit" class="btn btn-primary float-end">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
