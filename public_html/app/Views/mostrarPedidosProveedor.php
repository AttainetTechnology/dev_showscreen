<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<h2 class="tituloProveedores">Pedidos Proveedor</h2>

<div class="d-flex justify-content-between mb-3 btnMostrarPediProveed">
    <a href="<?= base_url('Pedidos_proveedor/addPedido') ?>" class="btn btn-primary btnAddPedido">+ Añadir Pedido</a>
    <button id="clear-filters" class="btn ms-auto btnEliminarfiltros">Eliminar Filtros</button>
</div>
<?php

$estadoMap = [
    "0" => "Pendiente de realizar",
    "1" => "Pendiente de recibir",
    "2" => "Recibido",
    "6" => "Anulado"
];
?>
<div id="gridPedidosProveedor" class="ag-theme-alpine" style="height: 600px; width: 100%; margin-left:20px"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columnDefs = [{
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: function(params) {
                    const acciones = params.value;
                    return `
                        <button onclick="editarPedido('${acciones.editar}')" class="btn btnEditar btn-sm">
                            <span class="material-symbols-outlined icono">edit</span>Editar
                        </button>
                        <a href="${acciones.imprimir}" class="btn btnImprimir btn-sm" target="_blank">
                            <i class="fa fa-print"></i> Imprimir
                        </a>
                        <button onclick="eliminarPedido('${acciones.eliminar}')" class="btn btnEliminar btn-sm">
                             <i class="fa fa-trash"></i> Eliminar
                        </button>
                    `;
                },
                minWidth: 300,
                filter: false
            },
            {
                headerName: "ID Pedido",
                field: "id_pedido",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Fecha Salida",
                field: "fecha_salida",
                filter: 'agDateColumnFilter'
            },
            {
                headerName: "Proveedor",
                field: "nombre_proveedor",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Referencia",
                field: "referencia",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Estado",
                field: "estado_texto",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Usuario",
                field: "nombre_usuario",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Total Pedido",
                field: "total_pedido",
                flex: 1,
                filter: "agTextColumnFilter",
                floatingFilter: true,
                valueFormatter: params => `${params.value !== null ? params.value : 0} €`
            }
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: {
                flex: 1,
                minWidth: 100,
                sortable: true,
                floatingFilter: true,
                resizable: true
            },
            rowData: <?= json_encode($pedidos) ?>,
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            },
            onGridReady: function(params) {
                const gridApi = params.api;
                gridApi.sizeColumnsToFit();
            },
            getRowClass: function(params) {
                switch (params.data.estado_texto) {
                    case "Pendiente de realizar":
                        return 'estado0';
                    case "Pendiente de recibir":
                        return 'estado1';
                    case "Recibido":
                        return 'estado2';
                    case "Anulado":
                        return 'estado6';
                    default:
                        return '';
                }
            }
        };

        const eGridDiv = document.querySelector('#gridPedidosProveedor');
        new agGrid.Grid(eGridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });
    });

    function eliminarPedido(url) {
        if (confirm("¿Estás seguro de eliminar este pedido?")) {
            fetch(url, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("No se pudo eliminar el pedido.");
                    }
                })
                .catch(error => console.error("Error al eliminar el pedido:", error));
        }
    }


    function editarPedido(url) {
        window.location.href = url;
    }
</script>
<?= $this->endSection() ?>