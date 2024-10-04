<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<div class="container mt-5">
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
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="fa fa-truck fa-fw"></i> Rutas de transporte
            </button>
          <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Cambié modal-dialog a modal-lg para hacer el modal más ancho -->
        <div class="modal-content" style="height: 80vh;"> <!-- Hacer que el modal tenga el 80% de la altura de la ventana -->
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Rutas de transporte</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body" style="padding: 0; height: 65vh;"> <!-- Elimina el padding y ocupa toda la altura -->
                <iframe src="<?= base_url('Ruta_pedido/rutas/' . $pedido->id_pedido . '/' . $pedido->id_cliente) ?>" 
                        frameborder="0" 
                        width="100%" 
                        height="65vh" 
                        style="border: none; height: 100%;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Cerrar</button>
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
            "3" => "En proceso",
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
    const columnDefs = [
        {
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
            minWidth:130,
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
            <span class="material-symbols-outlined icono">edit</span>Editar</button>
            <a href="<?= base_url('pedidos/imprimir_parte/') ?>${id}" class="btn btnImprimirParte btn-sm" target="_blank">
               <span class="material-symbols-outlined icono">print</span>Parte</a>
            <a href="<?= base_url('pedidos/deleteLinea/') ?>${id}" class="btn btnEliminar btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta línea?');">
             <span class="material-symbols-outlined icono"> delete </span>Eliminar</a>
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
        onGridReady: function(params) {
            // Ajusta el tamaño de las columnas al tamaño del contenedor
            params.api.sizeColumnsToFit();
            // Guardamos el gridApi para usarlo más tarde
            window.gridApi = params.api;
        },
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

        </script>
        <!-- Modal HTML (Definido una sola vez en la página) -->
        <div class="modal fade" id="editarLineaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" id="modalBodyEditarLineaPedido">
                        <!-- El contenido del formulario se cargará aquí mediante AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Limpiar filtros individuales
            $('.clear-filter').on('click', function() {
                const filterId = $(this).data('filter');
                const element = document.getElementById(filterId);

                if (element.tagName === 'SELECT') {
                    element.selectedIndex = 0;
                } else {
                    element.value = '';
                }

                applyFilters();
            });

            // Limpiar todos los filtros
            $('#clear-filters').on('click', function() {
                Object.keys(filters).forEach(filterId => {
                    const element = document.getElementById(filterId);
                    if (element.tagName === 'SELECT') {
                        element.selectedIndex = 0;
                    } else {
                        element.value = '';
                    }
                });

                applyFilters();
            });
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