<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<h2 class="tituloProveedores">Proveedores</h2>
<div class="d-flex justify-content-between mb-3 btnProveedores">
    <a href="<?= base_url('proveedores/add') ?>" class="btn btn-primary btnAddPedido">+ Añadir Proveedor
    </a>

    <button id="clear-filters" class="btn btnEliminarFiltro">Eliminar Filtros</button>
</div>
<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%; margin-left:27px;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columnDefs = [{
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: params => {
                    const links = params.data.acciones;
                    return `
                    <a href="${links.editar}" class="btn btnEditar">
                        <span class="material-symbols-outlined icono">edit</span>Editar
                    </a>
                    <button onclick="eliminarProveedor('${links.eliminar}')" class="btn btn-danger btn-sm btnEliminar">
                        <i class="fa fa-trash"></i> Eliminar
                    </button>
                `;
                },
                filter: false,
                minWidth: 250
            },
            {
                headerName: "Nombre",
                field: "nombre_proveedor",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "NIF",
                field: "nif",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Dirección",
                field: "direccion",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Contacto",
                field: "contacto",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Provincia",
                field: "nombre_provincia",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Teléfono",
                field: "telf",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Web",
                field: "web",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Email",
                field: "email",
                filter: 'agTextColumnFilter'
            },
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
            rowData: [],
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            onGridReady: function(params) {
                const gridApi = params.api;
                fetchData(gridApi);
            },
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            }
        };

        const eGridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(eGridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });
    });

    function fetchData(gridApi) {
        fetch('<?= base_url("proveedores/getProveedores") ?>')
            .then(response => response.json())
            .then(data => gridApi.applyTransaction({
                add: data
            }))
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    function eliminarProveedor(url) {
        if (confirm("¿Estás seguro de eliminar este proveedor?")) {
            $.ajax({
                url: url,
                type: 'POST',
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert("Error al intentar eliminar el proveedor.");
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>