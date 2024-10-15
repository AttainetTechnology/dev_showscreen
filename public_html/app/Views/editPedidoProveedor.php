<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<h2>Editar Pedido del Proveedor</h2>
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
        <a href="<?= base_url('pedidos_proveedor') ?>" class="btn btn-secondary">Cancelar</a>
    </div>

</form>
<br> <br>
<h2>Lineas del pedido</h2>
<br>
<button id="addLineaPedidoBtn" class="btn btn-primary">Agregar Línea de Pedido</button>
<button id="clear-filters" class="btn btn-secondary" style="margin-top: 10px;">Eliminar Filtros</button>

<div id="lineaPedidosGrid" class="ag-theme-alpine" style="height: 500px; width: 100%; margin-top: 20px;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const estadosTexto = <?= json_encode($estados) ?>;
        const lineasPedido = <?= json_encode($lineasPedido) ?> || []; // Aseguramos que sea un array vacío si no hay datos.
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
                field: "id_producto",
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
                valueFormatter: params => `${params.value} €`
            }
        ];

        function renderActions(params) {
            const id = params.data.id_lineapedido;
            return `
                <button onclick="editarLinea(${id})" class="btn btn-warning btn-sm">Editar</button>
                <button onclick="eliminarLinea(${id})" class="btn btn-danger btn-sm">Eliminar</button>
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

<?= $this->endSection() ?>