<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<h2 class="tituloProveedores">Fichajes</h2>
<div class="btnsEditPedido">
    <button id="clear-filters" class="boton btnEliminarfiltros">Quitar Filtros

    </button>
</div>
<br>
<div id="myGrid" class="ag-theme-alpine" ></div>
<br>

<script>
let isEditing = false;
document.addEventListener('DOMContentLoaded', function () {
    const columnDefs = [{
            headerName: "Acciones",
            field: "acciones",
            cellRenderer: params => {
                const links = params.data.acciones;
                return `
                    <button onclick="editarFamilia('${links.editar}', '${params.data.nombre}', '${params.data.id}')" class="btn botonTabla btnEditarTabla" title="Editar">
                        Editar
                    </button>
                    <button onclick="eliminarFamilia('${links.eliminar}')" class="btn botonTabla btnEliminarTabla" title="Eliminar">
                        Eliminar
                    </button>
                `;
            },
            filter: false,
            minWidth: 200
        },
        {
            headerName: "Usuario",
            field: "nombre_usuario",
            filter: 'agTextColumnFilter'
        },
        {
            headerName: "Salida",
            field: "salida",
            filter: 'agTextColumnFilter',
            cellRenderer: function(params) {
                return formatDateTime(params.value);
            }
        },        {
            headerName: "Entrada",
            field: "entrada",
            filter: 'agTextColumnFilter',
            cellRenderer: function(params) {
                return formatDateTime(params.value);
            }
        },
        {
            headerName: "Total",
            field: "total",
            filter: 'agTextColumnFilter'
        },
        {
            headerName: "Total",
            field: "total",
            filter: 'agTextColumnFilter'
        },
        {
            headerName: "Incidencia",
            field: "incidencia",
            filter: 'agTextColumnFilter'
        },
        {
            headerName: "Extras",
            field: "extras",
            filter: 'agTextColumnFilter', 
            cellRenderer: function(params) {
                return params.value === 'Sí' ? 'Sí' : 'No';
            },
            filterParams: {
                values: ['Sí', 'No'], 
            }
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
        rowData: [],
        pagination: true,
        paginationPageSize: 10,
        domLayout: 'autoHeight',
        onGridReady: function (params) {
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
function formatDateTime(dateTimeStr) {
    if (!dateTimeStr) return '';

    const date = new Date(dateTimeStr); // Parse the date
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).slice(-2); // Last 2 digits of the year
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day}/${month}/${year}, ${hours}:${minutes}`;
}


function fetchData(gridApi) {
    fetch('<?= base_url("fichajes/getFichajes") ?>')
        .then(response => response.json())
        .then(data => gridApi.applyTransaction({
            add: data
        }))
        .catch(error => console.error('Error al cargar los datos:', error));
}



</script>
<?= $this->endSection() ?>