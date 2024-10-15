<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<h2 class="titleAddproducto">Editar Pedido Proveedor</h2>

<div class="mb-3 row btnEditPedido">
    <div class="col-12"> <!-- Cambiamos a col-12 para usar todo el ancho -->
        <div id="gc-form-bt_imprimir" class="d-flex justify-content-start gap-2"> <!-- Añadimos d-flex, justify-content-start, y gap para separar los botones -->
            <input type="hidden" name="bt_imprimir" value="">
            <a href="<?= base_url('pedidos_proveedor/print/' . $pedido['id_pedido']) ?>" class="btn btn-info btn-sm" target="_blanck">
                <i class="fa fa-print fa-fw"></i> Imprimir pedido
            </a>
            <a href="<?= base_url('pedidos_proveedor/anular/' . $pedido['id_pedido']) ?>" class="btn btn-danger btn-sm btn_anular">
                <i class="fa fa-trash fa-fw"></i> Anular todo
            </a>
            <a href="<?= base_url('pedidos_proveedor/pedido_realizado/' . $pedido['id_pedido']) ?>" class="btn btn-primary btn-sm text-white">
                <i class="fa fa-check fa-fw"></i> Pedido realizado
            </a>
            <a href="<?= base_url('pedidos_proveedor/pedido_recibido/' . $pedido['id_pedido']) ?>" class="btn btn-success btn-sm">
                <i class="fa fa-archive fa-fw"></i> Pedido recibido <!-- Cambiado a fa-archive -->
            </a>
        </div>
    </div>
</div>


<form action="<?= base_url('pedidos_proveedor/update/' . $pedido['id_pedido']) ?>" method="post" class="formEditPedido">
    <div class="mb-3">
        <label for="id_proveedor" class="form-label">Proveedor</label>
        <select name="id_proveedor" id="id_proveedor" class="form-select" required>
            <option value="">Seleccione un proveedor</option>
            <?php foreach ($proveedores as $prov): ?>
                <option value="<?= $prov['id_proveedor'] ?>" <?= $prov['id_proveedor'] == $pedido['id_proveedor'] ? 'selected' : '' ?>>
                    <?= $prov['nombre_proveedor'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="referencia" class="form-label">Referencia</label>
        <input type="text" name="referencia" id="referencia" class="form-control" value="<?= $pedido['referencia'] ?>">
    </div>

    <div class="mb-3">
        <label for="observaciones" class="form-label">Observaciones</label>
        <textarea name="observaciones" id="observaciones" class="form-control"><?= $pedido['observaciones'] ?></textarea>
    </div>

    <div class="mb-3">
        <label for="fecha_salida" class="form-label">Fecha de Salida</label>
        <input type="date" name="fecha_salida" id="fecha_salida" class="form-control" value="<?= $pedido['fecha_salida'] ?>" required>
    </div>


    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" id="estado" class="form-select" required>
            <?php foreach ($estados as $key => $estado): ?>
                <option value="<?= $key ?>" <?= $key == $pedido['estado'] ? 'selected' : '' ?>><?= $estado ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="buttonsEditPedido">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="<?= base_url('pedidos_proveedor') ?>" class="btn btn-secondary volverButton">Volver</a>
    </div>

</form>
<br> <br>
<h2 class="titleEditLineas">Lineas del pedido</h2>
<hr style="border: 5px solid #FFCC32; margin-top: 10px; margin-bottom: 20px;">

<div class="d-flex justify-content-between mb-3">
    <button id="addLineaPedidoBtn" class="btn btnAddLinea">+ Añadir Línea de Pedido</button>
    <button id="clear-filters" class="btn btn-secondary btnEliminarfiltros" style="margin-top: 10px;">Eliminar Filtros</button>
</div>

<!-- Modal -->
<div class="modal fade" id="addLineaPedidoModal" tabindex="-1" role="dialog" aria-labelledby="addLineaPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLineaPedidoModalLabel">Agregar Línea de Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Aquí se cargará el contenido del formulario mediante AJAX -->
                <div id="modal-content-placeholder"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveLineaPedido" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar línea de pedido -->
<div class="modal fade" id="editLineaPedidoModal" tabindex="-1" role="dialog" aria-labelledby="editLineaPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLineaPedidoModalLabel">Editar Línea de Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="edit-modal-content-placeholder"></div> <!-- Aquí se cargará el formulario de edición -->
            </div>
            <div class="modal-footer">
             
                <button type="button" id="updateLineaPedido" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
<div id="lineaPedidosGrid" class="ag-theme-alpine"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const estadosTexto = <?= json_encode($estados) ?>;
        const lineasPedido = <?= json_encode($lineasPedido) ?> || [];
        console.log("Datos de lineasPedido:", lineasPedido);
        console.log("Datos de estados:", estadosTexto);

        const columnDefs = [{
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: renderActions,
                cellClass: "acciones-col",
                minWidth: 250,
                filter: false
            },
            {
                headerName: "ID Línea",
                field: "id_lineapedido",
                flex: 1,
                filter: "agTextColumnFilter",
                floatingFilter: true
            },
            {
                headerName: "Uds.",
                field: "n_piezas",
                flex: 1,
                filter: "agTextColumnFilter",
                floatingFilter: true
            },
            {
                headerName: "Producto",
                field: "nombre_producto",
                flex: 1,
                filter: "agTextColumnFilter",
                floatingFilter: true
            },
            {
                headerName: "Estado",
                field: "estado",
                flex: 1,
                filter: "agTextColumnFilter",
                floatingFilter: true,
                valueGetter: function(params) {
                    return estadosTexto[params.data.estado] || "Estado desconocido";
                },
                valueFormatter: function(params) {
                    return estadosTexto[params.data.estado] || "Estado desconocido";
                }
            },
            {
                headerName: "Total (€)",
                field: "total_linea",
                flex: 1,
                filter: "agTextColumnFilter",
                floatingFilter: true,
                valueFormatter: params => `${params.value !== null ? params.value : 0} €` // Mostrar 0 si el valor es null
            }

        ];

        function renderActions(params) {
            const id = params.data.id_lineapedido;
            return `
                <button onclick="editarLinea(${id})" class="btn btnEditar btn-sm"> <span class="material-symbols-outlined icono">edit</span>Editar</button>
                <button onclick="eliminarLinea(${id})" class="btn btnEliminar btn-sm"> <span class="material-symbols-outlined icono">delete</span>Eliminar</button>
            `;
        }

        const gridOptions = {
            columnDefs: columnDefs,
            rowData: lineasPedido, // Inicializar aunque esté vacío
            pagination: true,
            paginationPageSize: 10,
            defaultColDef: {
                sortable: true,
                filter: true,
                floatingFilter: true,
                resizable: true
            },
            domLayout: "autoHeight",
            rowHeight: 60,
            localeText: {
                noRowsToShow: "No hay registros disponibles." // Mensaje que aparecerá si no hay datos.
            },
            onGridReady: function(params) {
                params.api.sizeColumnsToFit();
                window.gridApi = params.api;
            },
            getRowClass: function(params) {
                const estadoTexto = estadosTexto[params.data.estado] || "Estado desconocido";
                switch (estadoTexto) {
                    case "Pendiente de realizar":
                        return "estado0";
                    case "Pendiente de recibir":
                        return "estado1";
                    case "Recibido":
                        return "estado2";
                    case "Anulado":
                        return "estado6";
                    default:
                        return "";
                }
            }
        };

        const eGridDiv = document.querySelector('#lineaPedidosGrid');
        new agGrid.Grid(eGridDiv, gridOptions);

        document.getElementById('addLineaPedidoBtn').addEventListener('click', () => agregarLinea());
        document.getElementById('clear-filters').addEventListener('click', () => {
            gridApi.setFilterModel(null);
            gridApi.onFilterChanged();
        });

    });
</script>
<script>
    // Acción al hacer clic en el botón de agregar línea de pedido
    $('#addLineaPedidoBtn').on('click', function() {
        // Abrir el modal
        $('#addLineaPedidoModal').modal('show');

        // Cargar el formulario dentro del modal usando AJAX
        $.ajax({
            url: '<?= base_url('pedidos_proveedor/addLineaPedidoForm/' . $pedido['id_pedido']) ?>',
            type: 'GET',
            success: function(response) {
                $('#modal-content-placeholder').html(response); // Insertar el formulario en el modal
            },
            error: function(xhr, status, error) {
                alert('Error al cargar el formulario: ' + error);
            }
        });
    });

    $(document).ready(function() {
    $('.close').on('click', function() {
        $('#addLineaPedidoModal').modal('hide');
        $('#editLineaPedidoModal').modal('hide');
    });
});

    // Acción para guardar la nueva línea de pedido
    $('#saveLineaPedido').on('click', function() {
        // Enviar el formulario mediante AJAX
        var formData = $('#addLineaPedidoForm').serialize(); // Serializar los datos del formulario

        $.ajax({
            url: '<?= base_url('pedidos_proveedor/crearLinea') ?>', // Ruta para guardar el registro
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#addLineaPedidoModal').modal('hide'); // Cerrar el modal
                    location.reload(); // Recargar la página para reflejar los cambios
                } else {
                    alert('Error al agregar la línea de pedido');
                }
            }
        });
    });
    // Acción al hacer clic en el botón de editar línea de pedido
    function editarLinea(id_lineapedido) {
        // Abrir el modal de edición
        $('#editLineaPedidoModal').modal('show');

        // Cargar el formulario dentro del modal usando AJAX
        $.ajax({
            url: '<?= base_url('pedidos_proveedor/editLineaPedidoForm') ?>/' + id_lineapedido,
            type: 'GET',
            success: function(response) {
                $('#edit-modal-content-placeholder').html(response); // Insertar el formulario en el modal
            },
            error: function(xhr, status, error) {
                alert('Error al cargar el formulario de edición: ' + error);
            }
        });
    }

    // Acción para guardar los cambios de la línea de pedido editada
    $('#updateLineaPedido').on('click', function() {
        // Verifica que el formulario está cargado en el modal antes de proceder
        var form = $('#editLineaPedidoForm');

        if (form.length === 0) {
            alert('El formulario de edición no está disponible.');
            return;
        }
        // Serializar el formulario de edición
        var formData = form.serialize();
        // Realizar la solicitud AJAX para actualizar la línea
        $.ajax({
            url: '<?= base_url('pedidos_proveedor/actualizarLinea') ?>/' + $('input[name="id_lineapedido"]').val(),
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editLineaPedidoModal').modal('hide'); // Cerrar el modal
                    location.reload(); // Recargar la página para reflejar los cambios
                } else {
                    alert('Error al actualizar la línea de pedido');
                }
            },
        });
    });
    // Acción al hacer clic en el botón de eliminar línea de pedido
    function eliminarLinea(id_lineapedido) {
        if (confirm('¿Estás seguro de que deseas eliminar esta línea de pedido?')) {
            // Enviar la solicitud AJAX para eliminar la línea
            $.ajax({
                url: '<?= base_url('pedidos_proveedor/eliminarLinea') ?>/' + id_lineapedido,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar la línea de pedido: ' + (response.message || 'Desconocido.'));
                    }
                },
            });
        }
    }
</script>

<?= $this->endSection() ?>