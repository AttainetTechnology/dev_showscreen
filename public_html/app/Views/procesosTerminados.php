<!-- Modal -->
<div class="modal fade" id="modalProcesos" tabindex="-1" aria-labelledby="modalProcesosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProcesosLabel">Procesos Terminados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Línea Pedido</th>
                            <th>Proceso</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProcesos">
                        <!-- Aquí se llenarán los datos dinámicamente con AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
