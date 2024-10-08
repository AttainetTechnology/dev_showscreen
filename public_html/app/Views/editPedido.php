<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">

<div class="container mt-5 editpedido">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery debe cargarse primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Luego carga Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <h2>Editar Pedido</h2>
    <!-- Botones de Acción -->
    <div class="mb-3">
        <label for="acciones" class="form-label"></label>
        <div class="d-flex gap-2">
            <a href="<?= base_url('pedidos/print/' . $pedido->id_pedido . '?volver=' . urlencode(current_url())) ?>" class="btn btn-info btn-sm" target="_blank">
                <i class="fa fa-print"></i> Imprimir Pedido
            </a>
            <a href="<?= base_url('pedidos/parte_complejo/' . $pedido->id_pedido . '?volver=' . urlencode(current_url())) ?>" class="btn btn-secondary btn-sm" target="_blank">
                <i class="fa fa-truck"></i> Parte Complejo
            </a>
            <button type="button" class="btn btn-warning" id="openModal" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="fa fa-truck fa-fw"></i> Rutas de transporte
            </button>

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Rutas de transporte</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body" id="modalContent" style="padding: 0;">
                            <!-- Contenido cargado por AJAX -->
                            <div class="text-center" id="loading">
                                <p>Cargando...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Cerrar</button> -->
                        </div>
                    </div>
                </div>
            </div>


            <a href="<?= base_url('pedidos/entregar/' . $pedido->id_pedido) ?>" class="btn btn-success btn-sm">
                <i class="fa fa-check fa-fw"></i> Entregar Pedido
            </a>
            <a href="<?= base_url('pedidos/anular/' . $pedido->id_pedido) ?>" class="btn btn-danger btn-sm btn_anular">
                <i class="fa fa-trash fa-fw"></i> Anular Pedido
            </a>
        </div>
        <br>
    </div>
    <form action="<?= base_url('pedidos/update/' . $pedido->id_pedido) ?>" method="post">
        <!-- Empresa -->
        <div class="form-group">
            <label for="id_cliente">Empresa:</label>
            <select id="id_cliente" name="id_cliente" class="form-control" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>" <?= $pedido->id_cliente == $cliente['id_cliente'] ? 'selected' : '' ?>>
                        <?= $cliente['nombre_cliente'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>
        <!-- Referencia -->
        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control" value="<?= esc($pedido->referencia) ?>">
        </div>
        <br>
        <!-- Fechas -->
        <div class="form-group">
            <label for="fecha_entrada">Fecha de Entrada:</label>
            <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" value="<?= esc($pedido->fecha_entrada) ?>" required>
        </div>
        <br>
        <div class="form-group">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?= esc($pedido->fecha_entrega) ?>" required>
        </div>
        <br>
        <!-- Observaciones -->
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control" rows="3"><?= esc($pedido->observaciones) ?></textarea>
        </div>
        <br>
        <div class="button-container" style=" text-align: right;">
            <button type="submit" class="btn btn-primary btnGuardar">Guardar Pedido</button>
        </div>
        <br>
    </form>
    <!-- Líneas del Pedido -->
    <div class="form-group">
        <?php
        $estados_texto = [
            "0" => "Pendiente de material",
            "1" => "Falta Material",
            "2" => "Material recibido",
            "3" => "En Máquinas",
            "4" => "Terminado",
            "5" => "Entregado",
            "6" => "Anulado"
        ];
        ?>
        <br>
        <h3 style="margin-left: 5px;">Líneas del Pedido</h3>
        <hr style="border: 5px solid #FFCC32; margin-top: 10px; margin-bottom: 20px;">
        <br>
        <div class="d-flex justify-content-between botoneseditPedido">
            <button type="button" class="btn btnAddLinea" id="openAddLineaPedidoModal" data-id-pedido="<?= $pedido->id_pedido ?>">
                + Añadir Línea de Pedido
            </button>
            <div>
                <button id="clear-filters" class="btn btnEliminarfiltros">Eliminar Filtros</button>
                <button id="reload-page" class="btn btnrecarga ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal para añadir una nueva línea de pedido -->
        <div class="modal fade" id="addLineaPedidoModal" tabindex="-1" aria-labelledby="addLineaPedidoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLineaPedidoLabel">Añadir Línea de Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBodyAddLineaPedido">
                        <!-- El contenido del formulario se cargará aquí mediante AJAX -->
                    </div>
                </div>
            </div>
        </div>

        <br><br>

        <div id="lineasPedidoGrid" class="ag-theme-alpine" style="height: 400px; width: 100%;"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Array de estados desde PHP
                const estadosTexto = <?= json_encode($estados_texto) ?>;

                // Definición de columnas para ag-Grid
                const columnDefs = [{
                        headerName: 'Acciones',
                        field: 'acciones',
                        cellRenderer: renderActions,
                        cellClass: 'acciones-col',
                        minWidth: 250,
                        filter: false,
                    },
                    {
                        headerName: 'ID Línea',
                        field: 'id_lineapedido',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Uds.',
                        field: 'n_piezas',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Base',
                        field: 'nom_base',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Producto',
                        field: 'nombre_producto',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Estado',
                        field: 'estado',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                        valueGetter: function(params) {
                            return estadosTexto[params.data.estado] || 'Estado desconocido';
                        },
                        valueFormatter: function(params) {
                            return estadosTexto[params.data.estado] || 'Estado desconocido';
                        }
                    },
                    {
                        headerName: 'Med. Inicial',
                        field: 'med_inicial',
                        flex: 1,
                        minWidth: 130,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Med. Final',
                        field: 'med_final',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Total',
                        field: 'total_linea',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                        valueFormatter: params => `${params.value} €`,
                    },
                ];

                // Datos desde PHP
                const rowData = <?= json_encode($lineas_pedido) ?>;

                // Función para renderizar acciones en cada fila
                function renderActions(params) {
                    const id = params.data.id_lineapedido;
                    return `
        <button class="btn btnEditar btn-sm" data-id="${id}" data-bs-toggle="modal" data-bs-target="#editarLineaModal">
            <span class="material-symbols-outlined icono">edit</span>Editar
        </button>
        <button class="btn btnImprimirParte btn-sm" onclick="mostrarParte(${id})">
            <span class="material-symbols-outlined icono">print</span>Parte
        </button>
        <a href="<?= base_url('pedidos/deleteLinea/') ?>${id}" class="btn btnEliminar btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta línea?');">
            <span class="material-symbols-outlined icono">delete</span>Eliminar
        </a>
    `;
                }



                // Opciones de la tabla ag-Grid
                const gridOptions = {
                    columnDefs: columnDefs,
                    rowData: rowData,
                    pagination: true,
                    paginationPageSize: 10,
                    defaultColDef: {
                        sortable: true,
                        filter: true,
                        floatingFilter: true,
                        resizable: true,
                    },
                    domLayout: 'autoHeight',
                    rowHeight: 70,
                    localeText: {
                        noRowsToShow: 'No hay registros disponibles.'
                    },
                    onGridReady: function(params) {
                        // Ajusta el tamaño de las columnas al tamaño del contenedor
                        params.api.sizeColumnsToFit();
                        // Guardamos el gridApi para usarlo más tarde
                        window.gridApi = params.api;
                    },
                    getRowClass: function(params) {
                        // Obtener el estado en formato texto
                        const estadoTexto = estadosTexto[params.data.estado] || 'Estado desconocido';

                        // Asignar la clase CSS en función del estado
                        switch (estadoTexto) {
                            case "Pendiente de material":
                                return 'estado0';
                            case "Falta Material":
                                return 'estado1';
                            case "Material recibido":
                                return 'estado2';
                            case "En Máquinas":
                                return 'estado3';
                            case "Terminado":
                                return 'estado4';
                            case "Entregado":
                                return 'estado5';
                            case "Anulado":
                                return 'estado6';
                            default:
                                return '';
                        }
                    }

                };
                // Inicializar la tabla ag-Grid
                const eGridDiv = document.querySelector('#lineasPedidoGrid');
                new agGrid.Grid(eGridDiv, gridOptions);

                // Botón para eliminar filtros
                document.querySelector('#clear-filters').addEventListener('click', function() {
                    if (window.gridApi) {
                        window.gridApi.setFilterModel(null); // Eliminar todos los filtros
                        window.gridApi.onFilterChanged(); // Forzar la actualización de los datos
                    }
                });

                // Botón para recargar la página
                document.querySelector('#reload-page').addEventListener('click', function() {
                    location.reload();
                });

                // Ajustar las columnas al redimensionar la ventana
                window.addEventListener('resize', function() {
                    if (window.gridApi) {
                        window.gridApi.sizeColumnsToFit();
                    }
                });
            });

            function mostrarParte(id_lineapedido) {
                $.ajax({
                    url: '<?= base_url("partes/print/") ?>' + id_lineapedido,
                    type: 'GET',
                    success: function(data) {
                        $('#modalParteContent').html(data);
                        $('#parteModal').modal('show');
                    },
                    error: function() {
                        $('#modalParteContent').html('<p class="text-danger">Error al cargar el parte.</p>');
                        $('#parteModal').modal('show');
                    }
                });
            }

            function printDiv(divId) {
                var printContents = document.getElementById(divId).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>

        <!-- Modal para mostrar el Parte -->
        <div class="modal fade" id="parteModal" tabindex="-1" aria-labelledby="parteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="parteModalLabel">Parte de Trabajo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalParteContent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal HTML (Definido una sola vez en la página) -->
        <div class="modal fade" id="editarLineaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="overflow-y: hidden !important;">
                    <div class="modal-body" id="modalBodyEditarLineaPedido" style="overflow-y: auto !important;">
                        <!-- El contenido del formulario se cargará aquí mediante AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Al hacer clic en el botón, cargar el contenido dentro del modal
            $('#openModal').on('click', function() {
                var pedidoId = '<?= $pedido->id_pedido ?>';
                var clienteId = '<?= $pedido->id_cliente ?>';

                // Mostrar mensaje de carga mientras se obtiene el contenido
                $('#modalContent').html('<div class="text-center"><p>Cargando...</p></div>');

                // Hacer la solicitud AJAX para obtener las rutas
                $.ajax({
                    url: '<?= base_url('Ruta_pedido/rutas') ?>/' + pedidoId + '/' + clienteId,
                    method: 'GET',
                    success: function(response) {
                        if (response.error) {
                            $('#modalContent').html('<div class="alert alert-danger">' + response.error + '</div>');
                            return;
                        }

                        // Limpiar el contenido anterior del modal y mostrar la estructura
                        $('#modalContent').html(`
                    <div id="rutasContainer">
                        <div id="botonesRuta" class="d-flex justify-content-between align-items-center botoneseditRuta botonesRuta">
                            <button type="button" class="btn btnAddRuta" id="openAddRuta" style="flex-grow: 0;">+ Añadir Ruta</button>
                <!-- Botón "Eliminar Filtros" dentro del modal -->
                <button id="clear-filters-rutas" class="btn btnEliminarfiltrosRuta" style="flex-grow: 0;">Eliminar Filtros</button>
                        </div>
                        <br>
                        <div id="gridRutas" class="ag-theme-alpine"  style="width: 100%;"></div>
                    </div>
                    <div id="addRutaForm" style="display:none;"></div>
                `);

                        // Inicializar ag-Grid con los datos de la respuesta
                        initializeAgGrid(response.rutas, response.poblacionesMap, response.transportistas);
                        setupEventHandlers();
                    },
                    error: function() {
                        $('#modalContent').html('<div class="alert alert-danger">Error al cargar las rutas.</div>');
                    }
                });
            });

            // Función para inicializar ag-Grid
            function initializeAgGrid(rutas, poblacionesMap, transportistasMap) {
                var estadoMap = {
                    1: 'No preparado',
                    2: 'Recogido',
                    0: 'Pendiente'
                };

                var columnDefs = [{
                        headerName: "Acciones",
                        field: "acciones",
                        cellRenderer: function(params) {
                            var editBtn = `<button class="btn btnEditarRuta" data-id="${params.data.id_ruta}" onclick="editarRuta(${params.data.id_ruta})">
                <span class="material-symbols-outlined icono">edit</span>Editar</button>`;
                            var deleteBtn = `<button class="btn btnEliminarRuta" data-id="${params.data.id_ruta}" onclick="eliminarRuta(${params.data.id_ruta})">
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
                        filter: 'agDateColumnFilter'
                    },
                    {
                        headerName: "Estado",
                        field: "estado_ruta",
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    }
                ];

                var rowData = rutas.map(function(ruta) {
                    return {
                        id_ruta: ruta.id_ruta,
                        poblacion: poblacionesMap[ruta.poblacion] || 'Desconocido',
                        lugar: ruta.lugar,
                        recogida_entrega: ruta.recogida_entrega == 1 ? 'Recogida' : 'Entrega',
                        transportista: transportistasMap[ruta.transportista] || 'No asignado',
                        fecha_ruta: ruta.fecha_ruta,
                        estado_ruta: estadoMap[ruta.estado_ruta] || 'Desconocido'
                    };
                });

                var gridDiv = document.querySelector('#gridRutas');
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
                    getRowStyle: function(params) {
                        // Cambiar el fondo a verde si el estado es "Recogido" (estado = 2)
                        if (params.data && params.data.estado_ruta === 'Recogido') {
                            return {
                                backgroundColor: '#dff0d8',
                                color: 'black'
                            };
                        }
                        return null;
                    },
                    onGridReady: function(params) {
                        params.api.sizeColumnsToFit();
                        $('#botonesRuta').show(); // Asegura que los botones se muestren cuando la tabla esté lista
                        window.gridApiRutas = params.api;
                    },
                    rowHeight: 70,
                    domLayout: 'autoHeight',
                    localeText: {
                        noRowsToShow: 'No hay registros disponibles.'
                    }
                };

                new agGrid.Grid(gridDiv, gridOptions);

                // Configurar el botón "Eliminar Filtros" para la tabla de rutas
                $('#clear-filters-rutas').on('click', function() {
                    if (window.gridApiRutas) {
                        window.gridApiRutas.setFilterModel(null); // Eliminar todos los filtros en la tabla de rutas
                        window.gridApiRutas.onFilterChanged(); // Actualizar la tabla
                    }
                });
            }


            // Configurar eventos para "Añadir" y "Editar" Ruta
            function setupEventHandlers() {
                $('#formNuevaRuta').on('submit', function(event) {
                    event.preventDefault();
                    $(this).unbind('submit').submit();
                });

                // Botón para añadir una nueva ruta
                // Botón para añadir una nueva ruta
                $('#openAddRuta').on('click', function() {
                    var pedidoId = '<?= $pedido->id_pedido ?>';
                    var clienteId = '<?= $pedido->id_cliente ?>';

                    // Mostrar formulario de añadir ruta
                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/mostrarFormulario') ?>/' + pedidoId + '/' + clienteId,
                        method: 'GET',
                        success: function(response) {
                            $('#addRutaForm').html(response);
                            $('#addRutaForm').show();
                            $('#gridRutas, #botonesRuta').hide(); // Oculta #botonesRuta y #gridRutas
                            $('#rutasModalLabel').text('Añadir Ruta');
                        },
                        error: function() {
                            alert('Error al cargar el formulario de ruta.');
                        }
                    });
                });



                // Botón para editar una ruta existente
                // Botón para editar una ruta existente
                window.editarRuta = function(id_ruta) {
                    var pedidoId = '<?= $pedido->id_pedido ?>';
                    var clienteId = '<?= $pedido->id_cliente ?>';

                    // Mostrar formulario de edición de ruta
                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/mostrarFormulario') ?>/' + pedidoId + '/' + clienteId,
                        method: 'GET',
                        success: function(response) {
                            $('#addRutaForm').html(response);
                            $('#gridRutas, #botonesRuta').hide(); // Oculta #botonesRuta y #gridRutas
                            $('#addRutaForm').show();
                            $('#rutasModalLabel').text('Editar Ruta');

                            $.ajax({
                                url: '<?= base_url('Ruta_pedido/obtenerRuta') ?>/' + id_ruta,
                                method: 'GET',
                                success: function(rutaResponse) {
                                    $('#poblacion').val(rutaResponse.poblacion);
                                    $('#lugar').val(rutaResponse.lugar);
                                    $('#recogida_entrega').val(rutaResponse.recogida_entrega);
                                    $('#transportista').val(rutaResponse.transportista);
                                    $('#fecha_ruta').val(rutaResponse.fecha_ruta);
                                    $('#observaciones').val(rutaResponse.observaciones);
                                    $('#id_ruta').val(rutaResponse.id_ruta);
                                    $('#estadoRutaDiv').show();
                                    $('#estado_ruta').val(rutaResponse.estado_ruta);
                                },
                                error: function() {
                                    alert('Error al cargar los datos de la ruta.');
                                }
                            });
                        },
                        error: function() {
                            alert('Error al cargar el formulario de ruta.');
                        }
                    });
                };


                // Botón para volver a la tabla de rutas
                $('#volverTabla').on('click', function() {
                    $('#addRutaForm').hide();
                    $('#gridRutas').show();
                    $('#rutasModalLabel').text('Rutas del Pedido');
                });
            }

            // Función para eliminar una ruta
            window.eliminarRuta = function(id_ruta) {
                if (confirm('¿Estás seguro de que deseas eliminar esta ruta?')) {
                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/delete') ?>/' + id_ruta,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message); // Mostrar el mensaje de éxito
                                // Recargar solo el contenido del modal, no toda la página
                                cargarRutasModal(); // Función que recarga las rutas dentro del modal
                            } else {
                                cargarRutasModal(); // Función que recarga las rutas dentro del modal
                            }
                        },
                        error: function(xhr) {
                            alert('Error al eliminar la ruta: ' + xhr.responseText); // Manejo de errores
                        }
                    });
                }
            };

            function cargarRutasModal() {
                var pedidoId = '<?= $pedido->id_pedido ?>';
                var clienteId = '<?= $pedido->id_cliente ?>';

                // Hacer la solicitud AJAX para obtener las rutas actualizadas
                $.ajax({
                    url: '<?= base_url('Ruta_pedido/rutas') ?>/' + pedidoId + '/' + clienteId,
                    method: 'GET',
                    success: function(response) {
                        if (response.error) {
                            $('#modalContent').html('<div class="alert alert-danger">' + response.error + '</div>');
                            return;
                        }

                        // Actualizar el contenido del modal con las rutas
                        $('#modalContent').html(`
                <div id="rutasContainer">
                    <div id="botonesRuta"  class="d-flex justify-content-between align-items-center botoneseditRuta botonesRuta">
                        <button type="button" class="btn btnAddRuta" id="openAddRuta" style="flex-grow: 0;">+ Añadir Ruta</button>
                        <button id="clear-filters-rutas" class="btn btnEliminarfiltrosRuta" style="flex-grow: 0;">Eliminar Filtros</button>
                    </div>
                    <br>
                    <div id="gridRutas" class="ag-theme-alpine"  style="width: 100%;"></div>
                </div>
                <div id="addRutaForm" style="display:none;"></div>
            `);

                        // Inicializar de nuevo la tabla de rutas con los datos actualizados
                        initializeAgGrid(response.rutas, response.poblacionesMap, response.transportistas);
                        setupEventHandlers(); // Volver a configurar los eventos para "Añadir" y "Editar"
                    },
                    error: function() {
                        $('#modalContent').html('<div class="alert alert-danger">Error al cargar las rutas.</div>');
                    }
                });
            }

        });
        $(document).ready(function() {
            function abrirModalSiEsNecesario() {
                const urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('openModal')) {
                    $('#openModal').click();
                    // Remover el parámetro 'openModal' de la URL sin recargar la página
                    urlParams.delete('openModal');
                    const newUrl = window.location.pathname + '?' + urlParams.toString();
                    window.history.replaceState({}, '', newUrl);
                }
            }
            abrirModalSiEsNecesario();
            // Función para inicializar ag-Grid
            function initializeAgGrid(rutas, poblacionesMap, transportistasMap) {
                var estadoMap = {
                    1: 'No preparado',
                    2: 'Recogido',
                    0: 'Pendiente'
                };

                var columnDefs = [{
                        headerName: "Acciones",
                        field: "acciones",
                        cellRenderer: function(params) {
                            var editBtn = `<button class="btn btnEditarRuta" data-id="${params.data.id_ruta}" onclick="editarRuta(${params.data.id_ruta})">
                    <span class="material-symbols-outlined icono">edit</span>Editar</button>`;
                            var deleteBtn = `<button class="btn btnEliminarRuta" data-id="${params.data.id_ruta}" onclick="eliminarRuta(${params.data.id_ruta})">
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
                        filter: 'agDateColumnFilter'
                    },
                    {
                        headerName: "Estado",
                        field: "estado_ruta",
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    }
                ];

                var rowData = rutas.map(function(ruta) {
                    return {
                        id_ruta: ruta.id_ruta,
                        poblacion: poblacionesMap[ruta.poblacion] || 'Desconocido',
                        lugar: ruta.lugar,
                        recogida_entrega: ruta.recogida_entrega == 1 ? 'Recogida' : 'Entrega',
                        transportista: transportistasMap[ruta.transportista] || 'No asignado',
                        fecha_ruta: ruta.fecha_ruta,
                        estado_ruta: estadoMap[ruta.estado_ruta] || 'Desconocido'
                    };
                });

                var gridDiv = document.querySelector('#gridRutas');
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
                    onGridReady: function(params) {
                        params.api.sizeColumnsToFit();
                        $('#botonesRuta').show(); // Asegura que los botones se muestren cuando la tabla esté lista
                    },
                    rowHeight: 70,
                    domLayout: 'normal',
                    onGridReady: function(params) {
                        params.api.sizeColumnsToFit();
                        window.gridApi = params.api;
                    },
                    localeText: {
                        noRowsToShow: 'No hay registros disponibles.'
                    }
                };

                var gridDiv = document.querySelector('#gridRutas');
                new agGrid.Grid(gridDiv, gridOptions);

                // Configurar el botón "Eliminar Filtros" para la tabla de rutas
                $('#clear-filters-rutas').on('click', function() {
                    if (window.gridApiRutas) {
                        window.gridApiRutas.setFilterModel(null); // Eliminar todos los filtros en la tabla de rutas
                        window.gridApiRutas.onFilterChanged(); // Actualizar la tabla
                    }
                });
            }

        });
        $(document).on('click', '.btnEditar', function() {
            var lineaId = $(this).data('id'); // Obtener el ID de la línea de pedido

            // Hacer la solicitud AJAX para cargar el formulario de edición
            $.ajax({
                url: '<?= base_url("pedidos/mostrarFormularioEditarLineaPedido") ?>/' + lineaId,
                method: 'GET',
                success: function(response) {
                    // Cargar el contenido del formulario dentro del modal
                    $('#modalBodyEditarLineaPedido').html(response); // Asegúrate de que la respuesta contiene la vista correcta
                    $('#editarLineaModal').modal('show'); // Mostrar el modal
                },
                error: function() {
                    alert('Hubo un error al cargar el formulario. Por favor, intenta de nuevo.');
                }
            });
        });



        $(document).ready(function() {
            // Al hacer clic en el botón para abrir el modal "Añadir Línea de Pedido"
            $('#openAddLineaPedidoModal').click(function() {
                var idPedido = $(this).data('id-pedido');
                // Hacer la solicitud AJAX para cargar el contenido del modal
                $.ajax({
                    url: '<?= base_url("pedidos/mostrarFormularioAddLineaPedido") ?>/' + idPedido,
                    method: 'GET',
                    success: function(response) {
                        $('#modalBodyAddLineaPedido').html(response);
                        $('#addLineaPedidoModal').modal('show');
                    },
                    error: function() {
                        alert('Hubo un error al cargar el formulario. Por favor, intenta de nuevo.');
                    }
                });
            });
            // Gestión del envío del formulario
            $(document).on('submit', '#addLineaPedidoForm', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // alert('Línea de pedido añadida correctamente.');
                        location.reload();
                    },
                    error: function() {
                        alert('No se pudo guardar la línea de pedido.');
                    }
                });
            });
        });
    </script>

    <?= $this->endSection() ?>