<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<h2 class="titlepedidosmostrar">Pedidos</h2>
<div class="d-flex justify-content-between mb-3 btnsMostrarPedido">
    <a href="<?= base_url('pedidos/add') ?>" class="btn btnAddPedido">+ Añadir Pedido</a>

    <button id="clear-filters" class="btn btnEliminarfiltros">Eliminar Filtros</button>
</div>
<br>
<?php
$estadoMap = [
    "0" => "Pendiente de material",
    "1" => "Falta Material",
    "2" => "Material recibido",
    "3" => "En Máquinas",
    "4" => "Terminado",
    "5" => "Entregado",
    "6" => "Anulado"
];
?>
<div id="pedidoTable" class="ag-theme-alpine" style="height: 400px; width: 100%;"></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var allowDelete = <?= json_encode($allow_delete); ?>;

        var columnDefs = [{
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: function(params) {
                    var editBtn = `<a href="<?= base_url('pedidos/edit/') ?>${params.data.id_pedido}" class="btn btnEditar">
                <span class="material-symbols-outlined icono">edit</span>Editar</a>`;
                    var printBtn = `<a href="<?= base_url('pedidos/print/') ?>${params.data.id_pedido}" class="btn btnImprimir" target="_blank">
                <span class="material-symbols-outlined icono">print</span> Imprimir</a>`
                    var deleteBtn = allowDelete ? `<a href="<?= base_url('pedidos/delete/') ?>${params.data.id_pedido}" class="btn btnEliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">
                <span class="material-symbols-outlined icono"> delete </span>Eliminar</a>` : '';
                    return `${editBtn} ${printBtn} ${deleteBtn}`;
                },
                cellClass: 'acciones-col',
                minWidth: 220,
                filter: false
            },
            {
                headerName: "ID Pedido",
                field: "id_pedido",
                flex: 1,
                filter: 'agTextColumnFilter'
            },

            {
                headerName: "Cliente",
                field: "cliente",
                flex: 2,
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Referencia",
                field: "referencia",
                flex: 1,
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Estado",
                field: "estado",
                flex: 1,
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Fecha Entrada",
                field: "fecha_entrada",
                flex: 1,
                filter: 'agDateColumnFilter',
                filterParams: {
                    comparator: function(filterLocalDateAtMidnight, cellValue) {
                        if (!cellValue) return -1;
                        var cellDateParts = cellValue.split('-');
                        var cellDate = new Date(Number(cellDateParts[0]), Number(cellDateParts[1]) - 1, Number(cellDateParts[2]));
                        if (cellDate < filterLocalDateAtMidnight) {
                            return -1;
                        } else if (cellDate > filterLocalDateAtMidnight) {
                            return 1;
                        } else {
                            return 0;
                        }
                    }
                },
                valueFormatter: function(params) {
                    if (!params.value) return '';
                    var date = new Date(params.value);
                    return ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear();
                }
            },
            {
                headerName: "Fecha Entrega",
                field: "fecha_entrega",
                flex: 1,
                filter: 'agDateColumnFilter',
                filterParams: {
                    comparator: function(filterLocalDateAtMidnight, cellValue) {
                        if (!cellValue) return -1;
                        var cellDateParts = cellValue.split('-');
                        var cellDate = new Date(Number(cellDateParts[0]), Number(cellDateParts[1]) - 1, Number(cellDateParts[2]));
                        if (cellDate < filterLocalDateAtMidnight) {
                            return -1;
                        } else if (cellDate > filterLocalDateAtMidnight) {
                            return 1;
                        } else {
                            return 0;
                        }
                    }
                },
                valueFormatter: function(params) {
                    if (!params.value) return '';
                    var date = new Date(params.value);
                    return ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear();
                }
            },
            {
                headerName: "Usuario",
                field: "nombre_usuario",
                flex: 1,
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Total",
                field: "total",
                flex: 1,
                filter: 'agTextColumnFilter'
            }
        ];

        var rowData = [
            <?php foreach ($pedidos as $pedido): ?> {
                    id_pedido: "<?= $pedido->id_pedido ?>",
                    fecha_entrada: "<?= date('Y-m-d', strtotime($pedido->fecha_entrada)) ?>",
                    fecha_entrega: "<?= date('Y-m-d', strtotime($pedido->fecha_entrega)) ?>",
                    cliente: "<?= $pedido->nombre_cliente ?>",
                    referencia: "<?= $pedido->referencia ?>",
                    estado: "<?= $estadoMap[$pedido->estado] ?>",
                    nombre_usuario: "<?= $pedido->nombre_usuario ?>",
                    total: "<?= $pedido->total_pedido ?>€"
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
            rowHeight: 60,
            domLayout: 'autoHeight',
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            },
            onGridReady: function(params) {
                gridApi = params.api;
                params.api.sizeColumnsToFit();
                setTimeout(function() {
                    params.api.sizeColumnsToFit();
                }, 100);
                document.getElementById('pedidoTable').style.display = 'block';
            },
            getRowClass: function(params) {
                switch (params.data.estado) {
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

        var eGridDiv = document.querySelector('#pedidoTable');
        const gridApi = agGrid.createGrid(eGridDiv, gridOptions);

        window.addEventListener('resize', function() {
            gridApi.sizeColumnsToFit();
        });

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridApi.setFilterModel(null);
            gridApi.onFilterChanged();
        });

        function compareDates(filterLocalDateAtMidnight, cellValue) {
            if (!cellValue) return -1;
            const cellDateParts = cellValue.split('-');
            const cellDate = new Date(Number(cellDateParts[0]), Number(cellDateParts[1]) - 1, Number(cellDateParts[2]));
            return cellDate < filterLocalDateAtMidnight ? -1 : cellDate > filterLocalDateAtMidnight ? 1 : 0;
        }

        function formatDate(params) {
            if (!params.value) return '';
            const date = new Date(params.value);
            return ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear();
        }

        function renderActions(params) {
            const id = params.data.id_pedido;
            return `
                <a href="<?= base_url('pedidos/edit/') ?>${id}" class="btn btnEditar">Editar</a>
                <a href="<?= base_url('pedidos/delete/') ?>${id}" class="btn btnEliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">Eliminar</a>
                <a href="<?= base_url('pedidos/print/') ?>${id}" class="btn btnImprimir" target="_blank">Imprimir</a>`;
        }
    });
</script>
<?= $this->endSection() ?>