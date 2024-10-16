<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<h2 class="tituloProductosNecesidad">Productos Proveedor</h2>

<div class="d-flex justify-content-between mb-3 btnProductoNecesidad">
    <a href="<?= base_url('productos_necesidad/add') ?>" class="btn btn-primary btnAddPedido">
   </i>+ Añadir Producto
    </a>
    <button id="clear-filters" class="btn btn-danger btnEliminarFiltro">Eliminar Filtros</button>

</div>

<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%; margin-left:27px;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columnDefs = [{
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: params => {
                    const links = params.value;
                    return `
    <a href="${links.editar}" class="btn btnEditar">
    <span class="material-symbols-outlined icono">edit</span>Editar</a>
    </a>
                        <a href="${links.precio}" class="btn btn-primary btn-sm btnPrecio"><i class="fa fa-euro-sign"></i>€ Precio</a>
                        <button onclick="eliminarProducto('${links.eliminar}')" class="btn btn-danger btn-sm btnEliminar"><i class="fa fa-trash"></i> Eliminar</button>
                    `;
                },
                filter: false,
                minWidth: 250
            },
            {
                headerName: "Nombre del Producto",
                field: "nombre_producto",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Familia",
                field: "nombre_familia",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Imagen",
                field: "imagen",
                cellRenderer: params => params.value ? `<img src="${params.value}" height="60">` : ''
            },
            {
                headerName: "Unidad",
                field: "unidad",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Estado",
                field: "estado_producto",
                filter: 'agTextColumnFilter'
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
            onGridReady: function(params) {
                const gridApi = params.api;
                fetchData(gridApi);
            },
            rowHeight: 60,
            domLayout: 'autoHeight',
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
        fetch('<?= base_url("productos_necesidad/getProductos") ?>')
            .then(response => response.json())
            .then(data => gridApi.applyTransaction({
                add: data
            }))
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    function eliminarProducto(url) {
        if (confirm("¿Estás seguro de eliminar este producto?")) {
            $.ajax({
                url: url,
                type: 'POST',
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert("Error al intentar eliminar el producto.");
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>