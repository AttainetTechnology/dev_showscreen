<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<style>
    .ag-icon-filter {
        display: none !important;
    }

    .acciones-col {
        width: 240px !important;
    }
</style>
<!-- Importar estilos y scripts de AG Grid -->
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>

<h2>Pedidos</h2>
<div class="d-flex justify-content-between mb-3">
    <a href="#" id="openModal" class="btn btn-primary">Añadir Pedido</a>

    <div class="modal fade" id="addPedidoModal" tabindex="-1" role="dialog" aria-labelledby="addPedidoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPedidoModalLabel">Añadir Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- cargará el contenido desde la vista add_pedido.php -->
                </div>
            </div>
        </div>
    </div>
    <button id="clear-filters" class="btn btn-secondary">Eliminar Filtros</button>
</div>
<br>

<?php
// Mapeo de estados
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
        // Definición de columnas con filtros de texto
        var columnDefs = [{
                headerName: "ID Pedido",
                field: "id_pedido",
                filter: 'agTextColumnFilter'
            }, 
            {
                headerName: "Fecha Entrada",
                field: "fecha_entrada",
                filter: 'agDateColumnFilter'
            }, 
            {
                headerName: "Fecha Entrega",
                field: "fecha_entrega",
                filter: 'agDateColumnFilter'
            }, 
            {
                headerName: "Cliente",
                field: "cliente",
                filter: 'agTextColumnFilter'
            }, 
            {
                headerName: "Referencia",
                field: "referencia",
                filter: 'agTextColumnFilter'
            }, 
            {
                headerName: "Estado",
                field: "estado",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Usuario",
                field: "usuario",
                filter: 'agTextColumnFilter'
            }, 
            {
                headerName: "Total",
                field: "total",
                filter: 'agTextColumnFilter'
            }, 
            {
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: function(params) {
                    var editBtn = `<a href="<?= base_url('pedidos/edit/') ?>${params.data.id_pedido}" class="btn btn-warning">Editar</a>`;
                    var deleteBtn = `<a href="<?= base_url('pedidos/delete/') ?>${params.data.id_pedido}" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">Eliminar</a>`;
                    var printBtn = `<a href="<?= base_url('pedidos/print/') ?>${params.data.id_pedido}" class="btn btn-info" target="_blank">Imprimir</a>`;
                    return `${editBtn} ${deleteBtn} ${printBtn}`;
                },
                cellClass: 'acciones-col'
            }
        ];
        // Datos de ejemplo (cargar dinámicamente desde el backend)
        var rowData = [
            <?php foreach ($pedidos as $pedido): ?> {
                    id_pedido: "<?= $pedido->id_pedido ?>",
                    fecha_entrada: "<?= date('d-m-Y', strtotime($pedido->fecha_entrada)) ?>",
                    fecha_entrega: "<?= date('d-m-Y', strtotime($pedido->fecha_entrega)) ?>",
                    cliente: "<?= $pedido->nombre_cliente ?>",
                    referencia: "<?= $pedido->referencia ?>",
                    estado: "<?= $estadoMap[$pedido->estado] ?>", 
                    total: "<?= $pedido->total_pedido ?>€"
                },
            <?php endforeach; ?>
        ];
        // Inicialización de AG Grid con filtros simples en cada columna
        var gridOptions = {
            columnDefs: columnDefs,
            rowData: rowData,
            pagination: true,
            paginationPageSize: 10,
            defaultColDef: {
                sortable: true,
                filter: true, 
                floatingFilter: true 
            },
            domLayout: 'autoHeight',
            onGridReady: function() {
                document.getElementById('pedidoTable').style.display = 'block';
            }
        };
        // Crear la tabla en el contenedor
        var eGridDiv = document.querySelector('#pedidoTable');
        const gridApi = agGrid.createGrid(eGridDiv, gridOptions);

        // Botón para eliminar filtros
        document.getElementById('clear-filters').addEventListener('click', function() {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged(); 
        });
    });
    // Función para abrir el modal de añadir pedido
    $(document).ready(function() {
        function abrirModal() {
            $.ajax({
                url: '<?= base_url('pedidos/add') ?>',
                method: 'GET',
                success: function(response) {
                    $('#addPedidoModal .modal-body').html(response);
                    $('#addPedidoModal').modal('show');
                },
                error: function() {
                    alert('Hubo un error al cargar el contenido del modal.');
                }
            });
        }
        $('#openModal').click(function(e) {
            e.preventDefault();
            abrirModal();
        });
    });
</script>

<?= $this->endSection() ?>