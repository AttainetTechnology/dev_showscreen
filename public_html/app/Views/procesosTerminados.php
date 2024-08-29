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
                            <th>
                                ID Línea Pedido
                                <input type="text" id="filtroIdLinea" class="form-control form-control-sm" placeholder="Filtrar">
                            </th>
                            <th>
                                Proceso
                                <input type="text" id="filtroProceso" class="form-control form-control-sm" placeholder="Filtrar">
                            </th>
                            <th>
                                Producto
                                <input type="text" id="filtroProducto" class="form-control form-control-sm" placeholder="Filtrar">
                            </th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProcesos">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filtroIdLinea = document.getElementById('filtroIdLinea');
        const filtroProceso = document.getElementById('filtroProceso');
        const filtroProducto = document.getElementById('filtroProducto');
        const tablaProcesos = document.getElementById('tablaProcesos');

        function filtrarTabla() {
            const filtroIdLineaValue = filtroIdLinea.value.toLowerCase();
            const filtroProcesoValue = filtroProceso.value.toLowerCase();
            const filtroProductoValue = filtroProducto.value.toLowerCase();

            Array.from(tablaProcesos.getElementsByTagName('tr')).forEach(function(fila) {
                const celdaIdLinea = fila.getElementsByTagName('td')[0].textContent.toLowerCase();
                const celdaProceso = fila.getElementsByTagName('td')[1].textContent.toLowerCase();
                const celdaProducto = fila.getElementsByTagName('td')[2].textContent.toLowerCase();

                const mostrarFila =
                    celdaIdLinea.includes(filtroIdLineaValue) &&
                    celdaProceso.includes(filtroProcesoValue) &&
                    celdaProducto.includes(filtroProductoValue);

                fila.style.display = mostrarFila ? '' : 'none';
            });
        }

        filtroIdLinea.addEventListener('input', filtrarTabla);
        filtroProceso.addEventListener('input', filtrarTabla);
        filtroProducto.addEventListener('input', filtrarTabla);
    });
</script>