<!-- Cargar primero los estilos -->
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery primero, ya que algunos scripts pueden depender de él -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap después de jQuery -->
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script> <!-- ag-Grid al final -->

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<!-- Modal para mostrar las rutas relacionadas con un pedido -->
<div class="modal fade" id="rutasModal" tabindex="-1" aria-labelledby="rutasModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title tituloRutas" id="rutasModalLabel">Rutas del Pedido <?= esc($id_pedido) ?></h5>
            </div>
            <div class="modal-body">
                <div id="rutasContainer">
                    <?php if (!empty($rutas)): ?>
                        <!-- Contenedor para ag-Grid -->
                        <div id="gridRutas" class="ag-theme-alpine" style="height: 400px; width: 100%;">
                            <div class="d-flex justify-content-between align-items-center botoneseditPedido">
                                <button type="button" class="btn btnAddRuta" id="openAddRuta" style="flex-grow: 0;">
                                    + Añadir Ruta
                                </button>
                                <button id="clear-filters" class="btn btnEliminarfiltrosRuta" style="flex-grow: 0;">
                                    Eliminar Filtros
                                </button>
                            </div> <br>
                        </div>
                    <?php else: ?>
                        <p>No hay rutas para este pedido.</p>
                    <?php endif; ?>
                </div>
                <!-- Formulario para añadir una nueva ruta, inicialmente oculto -->
                <div id="addRutaForm" style="display:none;">
                    <form id="formNuevaRuta" method="POST" action="<?= base_url('Ruta_pedido/guardarRuta') ?>">
                        <input type="hidden" name="id_pedido" value="<?= esc($id_pedido) ?>" /> <!-- ID del pedido -->
                        <input type="hidden" name="id_cliente" value="<?= esc($id_cliente) ?>" /> <!-- ID del cliente -->
                        <input type="hidden" name="id_ruta" id="id_ruta" /> <!-- ID de la ruta (solo para editar) -->

                        <div class="mb-3">
                            <label for="poblacion" class="form-label">Población</label>
                            <select class="form-control" id="poblacion" name="poblacion" required>
                                <option value="">Selecciona una población</option>
                                <?php foreach ($poblaciones as $poblacion): ?>
                                    <option value="<?= esc($poblacion['id_poblacion']) ?>"><?= esc($poblacion['poblacion']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="lugar" class="form-label">Lugar</label>
                            <input type="text" class="form-control" id="lugar" name="lugar">
                        </div>
                        <div class="mb-3">
                            <label for="recogida_entrega" class="form-label">Recogida/Entrega</label>
                            <select class="form-control" id="recogida_entrega" name="recogida_entrega" required>
                                <option value="1">Recogida</option>
                                <option value="2">Entrega</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="transportista" class="form-label">Transportista</label>
                            <select class="form-control" id="transportista" name="transportista" required>
                                <?php foreach ($transportistas as $id => $nombre): ?>
                                    <option value="<?= esc($id) ?>"><?= esc($nombre) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_ruta" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha_ruta" name="fecha_ruta" required>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>

                        <div class="mb-3" id="estadoRutaDiv" style="display:none;">
                            <label for="estado_ruta" class="form-label">Estado</label>
                            <select class="form-control" id="estado_ruta" name="estado_ruta">
                                <option value="1">No preparado</option>
                                <option value="2">Recogido</option>
                                <option value="0">Pendiente</option>
                            </select>
                        </div>


                        <button type="submit" class="btn btn-primary">Guardar Ruta</button>
                        <button type="button" class="btn btn-secondary" id="volverTabla">Volver</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var idPedidoGlobal = '<?= esc($id_pedido) ?>';
        var today = new Date().toISOString().split('T')[0];
        $('#fecha_ruta').val(today);
        <?php if (!empty($rutas)): ?>
            var gridDiv = document.querySelector('#gridRutas');
            if (gridDiv) {
                gridDiv.style.display = 'block'; // Solo intenta acceder si el elemento existe
            } else {
                console.error('El elemento #gridRutas no está disponible en el DOM.');
            }
            var columnDefs = [{
                    headerName: "Acciones",
                    field: "acciones",
                    cellRenderer: function(params) {
                        var editBtn = `<button class="btn btnEditarRuta" data-id="${params.data.id_ruta}" onclick="editarRuta(${params.data.id_ruta})">
        <span class="material-symbols-outlined icono ">edit</span>Editar</button>`;
                        var deleteBtn = `<button class="btn btnEliminarRuta" onclick="eliminarRuta(${params.data.id_ruta})">
        <span class="material-symbols-outlined icono">delete</span>Eliminar</button>`;
                        return `${editBtn} ${deleteBtn}`;
                    },

                    cellClass: 'acciones-col',
                    minWidth: 190,
                    filter: false
                },
                {
                    headerName: "Población",
                    field: "poblacion",
                    flex: 1,
                    filter: 'agTextColumnFilter'
                },
                {
                    headerName: "Lugar",
                    field: "lugar",
                    flex: 1,
                    filter: 'agTextColumnFilter'
                },
                {
                    headerName: "Recogida/Entrega",
                    field: "recogida_entrega",
                    minWidth: 190,
                    flex: 1,
                    filter: 'agTextColumnFilter'
                },
                {
                    headerName: "Transportista",
                    field: "transportista",
                    minWidth: 150,
                    flex: 1,
                    filter: 'agTextColumnFilter'
                },
                {
                    headerName: "Fecha",
                    field: "fecha_ruta",
                    flex: 1,
                    filter: 'agDateColumnFilter',
                },
                {
                    headerName: "Estado",
                    field: "estado_ruta",
                    flex: 1,
                    filter: 'agTextColumnFilter'
                }
            ];

            var rowData = [
                <?php foreach ($rutas as $ruta): ?> {
                        id_ruta: <?= $ruta['id_ruta'] ?>,
                        poblacion: "<?= esc($poblacionesMap[$ruta['poblacion']] ?? 'Desconocido') ?>",
                        lugar: "<?= esc($ruta['lugar']) ?>",
                        recogida_entrega: "<?= esc($ruta['recogida_entrega'] == 1 ? 'Recogida' : 'Entrega') ?>",
                        transportista: "<?= esc($transportistas[$ruta['transportista']] ?? 'No asignado') ?>",
                        fecha_ruta: "<?= esc($ruta['fecha_ruta']) ?>",
                        estado_ruta: "<?= esc($ruta['estado_ruta'] == 1 ? 'No preparado' : ($ruta['estado_ruta'] == 2 ? 'Recogido' : 'Pendiente')) ?>",
                    },
                <?php endforeach; ?>
            ];

            var gridOptions = {
                columnDefs: columnDefs,
                rowData: rowData,
                pagination: true,
                paginationPageSize: 10,
                defaultColDef: {
                    sortable: true,
                    filter: true,
                    floatingFilter: true,
                    resizable: true
                },
                rowHeight: 70,
                domLayout: 'autoHeight',
                onGridReady: function(params) {
                    gridApi = params.api;
                    params.api.sizeColumnsToFit();
                    setTimeout(function() {
                        params.api.sizeColumnsToFit();
                    }, 100);

                }
            };
            new agGrid.Grid(gridDiv, gridOptions);
        <?php endif; ?>
        $('#formNuevaRuta').on('submit', function(event) {
            event.preventDefault();
            console.log({
                poblacion: $('#poblacion').val(),
                lugar: $('#lugar').val(),
                recogida_entrega: $('#recogida_entrega').val(),
                transportista: $('#transportista').val(),
                fecha_ruta: $('#fecha_ruta').val(),
                observaciones: $('#observaciones').val(),
                id_pedido: $('input[name="id_pedido"]').val()
            });

            $(this).unbind('submit').submit();
        });
        $('#openAddRuta').on('click', function() {
            $('#formNuevaRuta')[0].reset(); // Restablecer todos los campos del formulario
            $('#id_ruta').val('');
            $('#estadoRutaDiv').hide();
            // Autorrellenar el campo de fecha con la fecha actual
            var today = new Date().toISOString().split('T')[0];
            $('#fecha_ruta').val(today);
            $('#gridRutas').hide();
            $('#addRutaForm').show();
            $('#poblacion').focus();
            $('#rutasModalLabel').text('Añadir ruta Pedido Id:' + idPedidoGlobal);
        });

        // Manejar el envío del formulario
        $('#formNuevaRuta').on('submit', function(event) {
            event.preventDefault();
        });
        $('#volverTabla').on('click', function() {
            $('#addRutaForm').hide();
            $('#gridRutas').show();
            $('#rutasModalLabel').text('Rutas del Pedido ' + idPedidoGlobal);
        });
    });

    function editarRuta(id_ruta) {
        $.ajax({
            url: '<?= base_url('Ruta_pedido/obtenerRuta') ?>/' + id_ruta,
            type: 'GET',
            success: function(response) {
                $('#poblacion').val(response.poblacion);
                $('#lugar').val(response.lugar);
                $('#recogida_entrega').val(response.recogida_entrega);
                $('#transportista').val(response.transportista);
                $('#fecha_ruta').val(response.fecha_ruta);
                $('#observaciones').val(response.observaciones);
                $('#id_ruta').val(response.id_ruta);
                // Mostrar el campo "Estado" solo en modo edición
                $('#estadoRutaDiv').show();
                // Actualizar el valor del campo de selección de estado
                let estadoRuta = response.estado_ruta;
                $('#estado_ruta').val(estadoRuta);
                $('#rutasModalLabel').text('Editar Ruta');
                // Cambiar el formulario a modo de edición
                $('#addRutaForm').show();
                $('#gridRutas').hide();

            },
            error: function() {
                alert("Error al cargar los datos de la ruta.");
            }
        });
    }

    function eliminarRuta(id_ruta) {
        if (confirm('¿Estás seguro de que deseas eliminar esta ruta?')) {
            $.ajax({
                url: '<?= base_url('Ruta_pedido/delete') ?>/' + id_ruta,
                type: 'DELETE',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error al eliminar la ruta: ' + xhr.responseJSON.error);
                }
            });
        }
    }
</script>