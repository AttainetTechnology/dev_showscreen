<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<!-- Estilos y Scripts -->
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">

<h2 class="titlepedidosmostrar">Pedidos</h2>
<div class="botonSeparados">
    <a href="<?= base_url('pedidos/add') ?>" class="btn boton btnAdd" style="margin-left: 25px;">Añadir Pedido</a>
    <button id="clear-filters" class="btn boton btnEliminarfiltros">Quitar Filtros</button>
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
        console.log('Iniciando Ag-Grid para pedidos...');

        const columnDefs = [
            {
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: function(params) {
                    const editBtn = `<a href="<?= base_url('pedidos/edit/') ?>${params.data.id_pedido}" class="btn botonTabla btnEditarTabla">Editar</a>`;
                    const printBtn = `<a href="<?= base_url('pedidos/print/') ?>${params.data.id_pedido}" class="btn botonTabla btnImprimirTabla" target="_blank">Imprimir</a>`;
                    const deleteBtn = params.data.allowDelete ? `<a href="<?= base_url('pedidos/delete/') ?>${params.data.id_pedido}" class="btn botonTabla btnEliminarTabla" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">Eliminar</a>` : '';
                    return `${editBtn} ${printBtn} ${deleteBtn}`;
                },
                minWidth: 260,
                filter: false
            },
            { headerName: "ID Pedido", field: "id_pedido", filter: 'agTextColumnFilter', flex: 1 },
            { headerName: "Cliente", field: "cliente", filter: 'agTextColumnFilter', flex: 2 },
            { headerName: "Referencia", field: "referencia", filter: 'agTextColumnFilter', flex: 1 },
            { headerName: "Estado", field: "estado", filter: 'agTextColumnFilter', flex: 1 },
            { 
                headerName: "Fecha Entrada", 
                field: "fecha_entrada", 
                filter: 'agDateColumnFilter',
                flex: 1,
                valueFormatter: formatDate
            },
            { 
                headerName: "Fecha Entrega", 
                field: "fecha_entrega", 
                filter: 'agDateColumnFilter',
                flex: 1,
                valueFormatter: formatDate
            },
            { headerName: "Usuario", field: "nombre_usuario", filter: 'agTextColumnFilter', flex: 1 },
            { headerName: "Total", field: "total", filter: 'agTextColumnFilter', flex: 1 }
        ];

        const rowData = [
            <?php foreach ($pedidos as $pedido): ?> {
                id_pedido: "<?= $pedido->id_pedido ?>",
                fecha_entrada: "<?= date('Y-m-d', strtotime($pedido->fecha_entrada)) ?>",
                fecha_entrega: "<?= date('Y-m-d', strtotime($pedido->fecha_entrega)) ?>",
                cliente: "<?= $pedido->nombre_cliente ?>",
                referencia: "<?= $pedido->referencia ?>",
                estado: "<?= $estadoMap[$pedido->estado] ?>",
                nombre_usuario: "<?= $pedido->nombre_usuario ?>",
                total: "<?= $pedido->total_pedido ?>€",
                allowDelete: <?= json_encode($allow_delete) ?>
            },
            <?php endforeach; ?>
        ];

        const gridOptions = {
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
                params.api.sizeColumnsToFit();
            },
            getRowClass: function(params) {
                switch (params.data.estado) {
                    case "Pendiente de material": return 'estado0';
                    case "Falta Material": return 'estado1';
                    case "Material recibido": return 'estado2';
                    case "En Máquinas": return 'estado3';
                    case "Terminado": return 'estado4';
                    case "Entregado": return 'estado5';
                    case "Anulado": return 'estado6';
                    default: return '';
                }
            }
        };

        const eGridDiv = document.querySelector('#pedidoTable');
        if (!eGridDiv) {
            console.error('El contenedor del grid no se encontró en el DOM.');
            return;
        }
        new agGrid.Grid(eGridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });

        function formatDate(params) {
            if (!params.value) return '';
            const date = new Date(params.value);
            return ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear();
        }
    });
</script>

<?= $this->endSection() ?>
