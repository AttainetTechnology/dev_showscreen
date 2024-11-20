<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>


<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<br>
<div class="container mt-5">
    <h1 class="mb-4">Lista de Procesos</h1>
    <div class="mb-3 d-flex justify-content-between">
    <a href="<?= base_url('procesos/add'); ?>" class="btn btn-primary">+ Añadir Proceso</a>
    <button id="clear-filters" class="btn btn-secondary">Quitar Filtros</button>
</div>

<!-- Botones para alternar entre Activos/Inactivos -->
<?php if ($estado_proceso == 1): ?>
    <button id="toggle-status" class="btn btn-secondary" data-estado="0">Ver Desactivados</button>
<?php else: ?>
    <button id="toggle-status" class="btn btn-success" data-estado="1">Ver Activos</button>
<?php endif; ?>

<div id="gridProcesos" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Definición de columnas
        const columnDefs = [
        {
            headerName: "Acciones",
            field: "acciones",
            cellRenderer: params => {
                const links = params.data.acciones || {};
                return `
                    <a href="${links.editar}" class="btn btn-warning btn-sm me-2">Editar</a>
                    <button class="btn ${params.data.estado_proceso == 1 ? 'btn-danger' : 'btn-success'} btn-sm" onclick="cambiarEstado('${links.cambiar_estado}', '${params.data.estado_proceso}')">
                        ${params.data.estado_proceso == 1 ? 'Desactivar' : 'Activar'}
                    </button>
                `;
            },
            minWidth: 250,
            filter: false,
            floatingFilter: false,
        },
        { headerName: "ID", field: "id_proceso", sortable: true, filter: "agTextColumnFilter", width: 100, hide: true },
        { headerName: "Restricción", field: "restriccion", sortable: true, filter: "agTextColumnFilter", hide: true },
        { headerName: "Nombre del Proceso", field: "nombre_proceso", sortable: true, filter: "agTextColumnFilter" },
    ];


        // Opciones del grid
        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: {
                flex: 1,
                minWidth: 100,
                sortable: true,
                filter: true,
                floatingFilter: true, // Habilita las cajas de búsqueda
                resizable: true
            },
            rowData: [], // Datos iniciales vacíos
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            onGridReady: function (params) {
                fetchProcesos(params.api);
            },
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            }
        };

        // Inicializar el grid
        const gridDiv = document.querySelector('#gridProcesos');
        new agGrid.Grid(gridDiv, gridOptions);

        // Fetch para obtener los datos
        function fetchProcesos(gridApi) {
            fetch('<?= base_url("procesos/getProcesos") ?>')
                .then(response => response.json())
                .then(data => {
                    console.log('Datos cargados:', data);
                    gridApi.applyTransaction({ add: data }); // Insertar datos en la tabla
                })
                .catch(error => console.error('Error al cargar los procesos:', error));
        }

        // Función para limpiar los filtros
        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });

        // Función para cambiar el estado del proceso
        window.cambiarEstado = function (url, estado) {
            if (confirm(`¿Estás seguro de que deseas ${estado == 1 ? 'desactivar' : 'activar'} este proceso?`)) {
                fetch(url, { method: 'POST' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Estado cambiado correctamente.');
                            fetchProcesos(gridOptions.api); // Recargar los datos después del cambio
                        } else {
                            alert('Error al cambiar el estado del proceso.');
                        }
                    })
                    .catch(() => alert('Error en la solicitud.'));
            }
        };
    });
</script>

<?= $this->endSection() ?>