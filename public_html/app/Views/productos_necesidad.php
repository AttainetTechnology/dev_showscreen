<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>

<h2>Productos Necesidad</h2>

<!-- Botón para añadir producto -->
<div class="mb-3">
    <a href="<?= base_url('productos_necesidad/add') ?>" class="btn btn-primary">
        <i class="fa fa-plus"></i> Añadir Producto
    </a>
</div>


<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gridOptions = {
            columnDefs: [{
                    headerName: "Acciones",
                    field: "acciones",
                    cellRenderer: params => {
                        const links = params.value;
                        return `
                            <a href="${links.editar}" class="btn btn-success btn-sm">
                                <i class="fa fa-edit"></i> Editar
                            </a>
                            <a href="${links.precio}" class="btn btn-primary btn-sm">
                                <i class="fa fa-euro-sign"></i> Precio
                            </a>
                            <a href="${links.eliminar}" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                <i class="fa fa-trash"></i> Eliminar
                            </a>
                        `;
                    }
                },
                {
                    headerName: "Nombre del Producto",
                    field: "nombre_producto",
                    sortable: true,
                    filter: true
                },
                {
                    headerName: "Familia",
                    field: "nombre_familia",
                    sortable: true,
                    filter: true
                },
                {
                    headerName: "Imagen",
                    field: "imagen",
                    cellRenderer: params => params.value ? `<img src="${params.value}" height="60">` : ''
                },
                {
                    headerName: "Unidad",
                    field: "unidad",
                    sortable: true,
                    filter: true
                },
                {
                    headerName: "Estado",
                    field: "estado_producto",
                    sortable: true,
                    filter: true
                },
            ],
            defaultColDef: {
                flex: 1,
                minWidth: 100
            },
            rowData: []
        };

        const eGridDiv = document.querySelector('#myGrid');
        const gridApi = agGrid.createGrid(eGridDiv, gridOptions);

        // Fetch data and apply it to the grid
        fetch('<?= base_url("productos_necesidad/getProductos") ?>')
            .then(response => response.json())
            .then(data => {
                gridApi.applyTransaction({
                    add: data
                });
            })
            .catch(error => console.error('Error al cargar los datos:', error));
    });
</script>

<?= $this->endSection() ?>