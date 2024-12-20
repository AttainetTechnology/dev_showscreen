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
                <button type="button" class="btn boton volverButton" data-bs-dismiss="modal">Cerrar
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 13C19.5 13.2155 19.4144 13.4221 19.262 13.5745C19.1096 13.7269 18.903 13.8125 18.6875 13.8125H9.27386L12.7627 17.2997C12.8383 17.3753 12.8982 17.465 12.9391 17.5637C12.98 17.6624 13.001 17.7682 13.001 17.875C13.001 17.9818 12.98 18.0876 12.9391 18.1863C12.8982 18.285 12.8383 18.3747 12.7627 18.4502C12.6872 18.5258 12.5975 18.5857 12.4988 18.6266C12.4001 18.6675 12.2943 18.6885 12.1875 18.6885C12.0807 18.6885 11.9749 18.6675 11.8762 18.6266C11.7775 18.5857 11.6878 18.5258 11.6122 18.4502L6.73724 13.5752C6.66157 13.4998 6.60154 13.4101 6.56058 13.3114C6.51962 13.2127 6.49854 13.1069 6.49854 13C6.49854 12.8931 6.51962 12.7873 6.56058 12.6886C6.60154 12.5899 6.66157 12.5002 6.73724 12.4247L11.6122 7.54974C11.7648 7.39717 11.9717 7.31146 12.1875 7.31146C12.4032 7.31146 12.6102 7.39717 12.7627 7.54974C12.9153 7.7023 13.001 7.90923 13.001 8.12499C13.001 8.34075 12.9153 8.54767 12.7627 8.70024L9.27386 12.1875H18.6875C18.903 12.1875 19.1096 12.2731 19.262 12.4255C19.4144 12.5778 19.5 12.7845 19.5 13Z" fill="white"/>
                </svg>
                </button>
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