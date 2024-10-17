<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">

<div class="container mt-5 editpedido">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <h2>Editar Pedido</h2>
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
                            <div class="text-center" id="loading">
                                <p>Cargando...</p>
                            </div>
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
        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control" value="<?= esc($pedido->referencia) ?>">
        </div>
        <br>
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
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control" rows="3"><?= esc($pedido->observaciones) ?></textarea>
        </div>
        <div class="form-group" style="font-size:20px;">
            <label>ID del Pedido:</label>
            <strong><?= esc($pedido->id_pedido) ?></strong>
        </div>


        <br>
        <div class="btnsEditPedido">
            <button type="submit" class="btn btn-primary btnGuardar">Guardar Pedido</button>
            <a href="<?= base_url('/pedidos/enmarcha') ?>" class="btn volverButton">Volver</a>
        </div>


    </form>
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
        <h3 style="margin-left: 5px;">Líneas del Pedido</h3>
        <hr style="border: 5px solid #FFCC32; margin-top: 10px; margin-bottom: 20px;">
        <br>
        <div class="d-flex justify-content-between botoneseditPedido">
            <button type="button" class="btn btnAddLinea" id="openAddLineaPedidoModal" data-id-pedido="<?= $pedido->id_pedido ?>">
                + Añadir Línea de Pedido
            </button>
            <div>
                <button id="clear-filters" class="btn btnEliminarfiltros">Eliminar Filtros</button>
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
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div id="lineasPedidoGrid" class="ag-theme-alpine" style="height: 400px; width: 100%;"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const estadosTexto = <?= json_encode($estados_texto) ?>;
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
                const rowData = <?= json_encode($lineas_pedido) ?>;

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
                    rowHeight: 60,
                    localeText: {
                        noRowsToShow: 'No hay registros disponibles.'
                    },
                    onGridReady: function(params) {
                        params.api.sizeColumnsToFit();
                        window.gridApi = params.api;
                    },
                    getRowClass: function(params) {
                        const estadoTexto = estadosTexto[params.data.estado] || 'Estado desconocido';
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
                const eGridDiv = document.querySelector('#lineasPedidoGrid');
                new agGrid.Grid(eGridDiv, gridOptions);
                document.querySelector('#clear-filters').addEventListener('click', function() {
                    if (window.gridApi) {
                        window.gridApi.setFilterModel(null);
                        window.gridApi.onFilterChanged();
                    }
                });
                document.querySelector('#reload-page').addEventListener('click', function() {
                    location.reload();
                });
                window.addEventListener('resize', function() {
                    if (window.gridApi) {
                        window.gridApi.sizeColumnsToFit();
                    }
                });
            });

            // Función para mostrar el modal del parte
            function mostrarParte(id_lineapedido) {
                $.ajax({
                    url: '<?= base_url("partes/print/") ?>' + id_lineapedido,
                    type: 'GET',
                    success: function(data) {
                        $('#modalParteContent').html(data);
                        $('#parteModal').modal('show');
                        // Almacenar en sessionStorage que el modal está abierto y el ID
                        sessionStorage.setItem('modalParteAbierto', 'true');
                        sessionStorage.setItem('modalParteId', id_lineapedido);
                    },
                    error: function() {
                        $('#modalParteContent').html('<p class="text-danger">Error al cargar el parte.</p>');
                        $('#parteModal').modal('show');
                        sessionStorage.setItem('modalParteAbierto', 'true');
                        sessionStorage.setItem('modalParteId', id_lineapedido);
                    }
                });
            }
            // Abrir el modal automáticamente si estaba abierto antes de la recarga y cargar contenido
            $(document).ready(function() {
                if (sessionStorage.getItem('modalParteAbierto') === 'true') {
                    const savedId = sessionStorage.getItem('modalParteId');
                    if (savedId) {
                        mostrarParte(savedId);
                    } else {
                        $('#parteModal').modal('show');
                    }
                }
                $('#parteModal').on('hidden.bs.modal', function() {
                    sessionStorage.removeItem('modalParteAbierto');
                    sessionStorage.removeItem('modalParteId');
                });
            });

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
        <script>
            $('#parteModal').on('hidden.bs.modal', function() {
                location.reload();
            });
        </script>
        <div class="modal fade" id="editarLineaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="overflow-y: hidden !important;">
                    <div class="modal-body" id="modalBodyEditarLineaPedido" style="overflow-y: auto !important;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#openModal').on('click', function() {
                var pedidoId = '<?= $pedido->id_pedido ?>';
                var clienteId = '<?= $pedido->id_cliente ?>';
                $('#modalContent').html('<div class="text-center"><p>Cargando...</p></div>');
                $.ajax({
                    url: '<?= base_url('Ruta_pedido/rutas') ?>/' + pedidoId + '/' + clienteId,
                    method: 'GET',
                    success: function(response) {
                        if (response.error) {
                            $('#modalContent').html('<div class="alert alert-danger">' + response.error + '</div>');
                            return;
                        }
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
                        $('#botonesRuta').show();
                        window.gridApiRutas = params.api;
                    },
                    rowHeight: 60,
                    domLayout: 'autoHeight',
                    localeText: {
                        noRowsToShow: 'No hay registros disponibles.'
                    }
                };

                new agGrid.Grid(gridDiv, gridOptions);
                $('#clear-filters-rutas').on('click', function() {
                    if (window.gridApiRutas) {
                        window.gridApiRutas.setFilterModel(null);
                        window.gridApiRutas.onFilterChanged();
                    }
                });
            }

            function setupEventHandlers() {
                $('#formNuevaRuta').on('submit', function(event) {
                    event.preventDefault();
                    $(this).unbind('submit').submit();
                });
                $('#openAddRuta').on('click', function() {
                    var pedidoId = '<?= $pedido->id_pedido ?>';
                    var clienteId = '<?= $pedido->id_cliente ?>';

                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/mostrarFormulario') ?>/' + pedidoId + '/' + clienteId,
                        method: 'GET',
                        success: function(response) {
                            $('#addRutaForm').html(response);
                            $('#addRutaForm').show();
                            $('#gridRutas, #botonesRuta').hide();
                            $('#rutasModalLabel').text('Añadir Ruta');
                        },
                        error: function() {
                            alert('Error al cargar el formulario de ruta.');
                        }
                    });
                });

                window.editarRuta = function(id_ruta) {
                    var pedidoId = '<?= $pedido->id_pedido ?>';
                    var clienteId = '<?= $pedido->id_cliente ?>';

                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/mostrarFormulario') ?>/' + pedidoId + '/' + clienteId,
                        method: 'GET',
                        success: function(response) {
                            $('#addRutaForm').html(response);
                            $('#gridRutas, #botonesRuta').hide();
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
                $('#volverTabla').on('click', function() {
                    $('#addRutaForm').hide();
                    $('#gridRutas').show();
                    $('#rutasModalLabel').text('Rutas del Pedido');
                });
            }

            window.eliminarRuta = function(id_ruta) {
                if (confirm('¿Estás seguro de que deseas eliminar esta ruta?')) {
                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/delete') ?>/' + id_ruta,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                cargarRutasModal();
                            } else {
                                cargarRutasModal();
                            }
                        },
                        error: function(xhr) {
                            alert('Error al eliminar la ruta: ' + xhr.responseText);
                        }
                    });
                }
            };

            function cargarRutasModal() {
                var pedidoId = '<?= $pedido->id_pedido ?>';
                var clienteId = '<?= $pedido->id_cliente ?>';

                $.ajax({
                    url: '<?= base_url('Ruta_pedido/rutas') ?>/' + pedidoId + '/' + clienteId,
                    method: 'GET',
                    success: function(response) {
                        if (response.error) {
                            $('#modalContent').html('<div class="alert alert-danger">' + response.error + '</div>');
                            return;
                        }
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

                        initializeAgGrid(response.rutas, response.poblacionesMap, response.transportistas);
                        setupEventHandlers();
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
                    urlParams.delete('openModal');
                    const newUrl = window.location.pathname + '?' + urlParams.toString();
                    window.history.replaceState({}, '', newUrl);
                }
            }
            abrirModalSiEsNecesario();

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
                        $('#botonesRuta').show();
                    },
                    rowHeight: 60,
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
                $('#clear-filters-rutas').on('click', function() {
                    if (window.gridApiRutas) {
                        window.gridApiRutas.setFilterModel(null);
                        window.gridApiRutas.onFilterChanged();
                    }
                });
            }

        });
        $(document).on('click', '.btnEditar', function() {
            var lineaId = $(this).data('id');

            $.ajax({
                url: '<?= base_url("pedidos/mostrarFormularioEditarLineaPedido") ?>/' + lineaId,
                method: 'GET',
                success: function(response) {
                    $('#modalBodyEditarLineaPedido').html(response);
                    $('#editarLineaModal').modal('show');
                },
                error: function() {
                    alert('Hubo un error al cargar el formulario. Por favor, intenta de nuevo.');
                }
            });
        });
        $(document).ready(function() {
            $('#openAddLineaPedidoModal').click(function() {
                var idPedido = $(this).data('id-pedido');
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
            $(document).on('submit', '#addLineaPedidoForm', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
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