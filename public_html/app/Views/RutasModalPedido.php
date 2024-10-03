<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<!-- Cargar jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<!-- Cargar Bootstrap 5 sin jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal para mostrar las rutas relacionadas con un pedido -->
<div class="modal fade" id="rutasModal" tabindex="-1" aria-labelledby="rutasModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rutasModalLabel">Rutas del Pedido <?= esc($id_pedido) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-flex justify-content-between botoneseditPedido">
                    <button type="button" class="btn btnAddRuta" id="openAddRuta">
                        + Añadir Ruta
                    </button>
                    <button id="clear-filters" class="btn ">Eliminar Filtros</button>
                </div>
            </div>

            <div class="modal-body">
                <div id="rutasContainer">
                    <?php if (!empty($rutas)): ?>
                        <!-- Contenedor para ag-Grid -->
                        <div id="gridRutas" class="ag-theme-alpine" style="height: 400px; width: 100%;"></div>
                    <?php else: ?>
                        <p>No hay rutas para este pedido.</p>
                    <?php endif; ?>
                </div>

                <!-- Formulario para añadir una nueva ruta, inicialmente oculto -->
                <div id="addRutaForm" style="display:none;">
                    <form id="formNuevaRuta" method="POST" action="<?= base_url('Ruta_pedido/guardarRuta') ?>">
                        <input type="hidden" name="id_pedido" value="<?= esc($id_pedido) ?>" /> <!-- ID del pedido -->
                        <input type="hidden" name="id_cliente" value="<?= esc($id_cliente) ?>" /> <!-- ID del cliente -->

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
                            <input type="text" class="form-control" id="lugar" name="lugar" required>
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
        // Setear la fecha por defecto al día de hoy en el campo fecha_ruta
        var today = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
        $('#fecha_ruta').val(today);
        // Inicializar ag-Grid si hay rutas
        <?php if (!empty($rutas)): ?>
            var gridDiv = document.querySelector('#gridRutas');
            var columnDefs = [{
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: function(params) {
                    var editBtn = `<a href="<?= base_url('pedidos/edit/') ?>${params.data.id_pedido}" class="btn btnEditar">
                    <span class="material-symbols-outlined icono">edit</span>Editar</a>`;
                    var deleteBtn = `<a href="<?= base_url('pedidos/delete/') ?>${params.data.id_pedido}" class="btn btnEliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">
                    <span class="material-symbols-outlined icono"> delete </span>Eliminar</a>`;

                    return `${editBtn}${deleteBtn} `;
                },
                cellClass: 'acciones-col',
                minWidth: 250,
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
                flex: 1,
                filter: 'agTextColumnFilter'
                },
                {
                    headerName: "Transportista",
                    field: "transportista",
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
                //las columnas se ajusten al tamaño del contenedor
                params.api.sizeColumnsToFit();
                setTimeout(function() {
                    params.api.sizeColumnsToFit();
                }, 100);
                document.getElementById('pedidoTable').style.display = 'block';
            }
            };

            new agGrid.Grid(gridDiv, gridOptions);
        <?php endif; ?>
        $('#formNuevaRuta').on('submit', function(event) {
            event.preventDefault();

            // Revisar los datos antes de enviarlos
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

        // Mostrar el formulario y ocultar la tabla y los botones al hacer clic en "Añadir Ruta"
        $('#openAddRuta').on('click', function() {
            $('#gridRutas').hide(); // Ocultar la tabla
            $('#addRutaForm').show(); // Mostrar el formulario
            $('.botoneseditPedido').hide(); // Ocultar los botones "Añadir Ruta" y "Eliminar Filtros"
            $('#poblacion').focus(); // Enfocar el primer campo

            // Cambiar el título del modal a "Añadir ruta al pedido"
            $('#rutasModalLabel').text('Añadir ruta al pedido <?= esc($id_pedido) ?>');
        });

        // Manejar el envío del formulario
        $('#formNuevaRuta').on('submit', function(event) {
            event.preventDefault();
            console.log("Formulario enviado.");
            // Aquí puedes añadir la lógica para enviar el formulario vía AJAX si lo deseas
        });

        // Botón "Volver" para regresar a la tabla de rutas
        $('#volverTabla').on('click', function() {
            $('#addRutaForm').hide(); // Ocultar el formulario
            $('#gridRutas').show(); // Mostrar la tabla
            $('.botoneseditPedido').show(); // Mostrar los botones "Añadir Ruta" y "Eliminar Filtros"

            // Cambiar el título del modal de vuelta a "Rutas del Pedido"
            $('#rutasModalLabel').text('Rutas del Pedido <?= esc($id_pedido) ?>');
        });
    });
</script>