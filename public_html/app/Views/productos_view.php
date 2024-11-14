<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<title>Productos</title>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<h2>Productos</h2>

<div class="d-flex justify-content-between mb-3">
    <button class="btn btn-primary" onclick="abrirModalAgregarProducto()">Añadir Producto</button>
    <button id="clear-filters" class="btn btn-secondary">Quitar Filtros</button>
</div>
<div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productoModalLabel">Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productoForm" enctype="multipart/form-data">
                    <input type="hidden" id="id_producto" name="id_producto">
                    <div class="mb-3">
                        <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarProductoBtn" onclick="guardarProducto()">Guardar Producto</button>
            </div>
        </div>
    </div>
</div>

<div id="productosGrid" class="ag-theme-alpine" style="height: 500px; width: 100%;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columnDefs = [{
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: accionesRenderer,
                filter: false,
                minWidth: 200
            },
            {
                headerName: "Nombre",
                field: "nombre_producto",
                filter: 'agTextColumnFilter',
                minWidth: 150
            },
            {
                headerName: "Familia",
                field: "nombre_familia",
                filter: 'agTextColumnFilter',
                minWidth: 150
            },
            {
                headerName: "Precio",
                field: "precio",
                filter: 'agNumberColumnFilter',
                minWidth: 100
            },
            {
                headerName: "Unidad",
                field: "unidad_nombre",
                filter: 'agTextColumnFilter',
                minWidth: 100
            },
            {
                headerName: "Estado",
                field: "estado_nombre",
                filter: 'agTextColumnFilter',
                minWidth: 120
            },
            {
                headerName: "Imagen",
                field: "imagen_url",
                cellRenderer: imagenRenderer,
                filter: false,
                minWidth: 100
            }
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: {
                flex: 1,
                sortable: true,
                floatingFilter: true,
                resizable: true
            },
            rowData: [],
            pagination: true,
            paginationPageSize: 10,
            localeText: {
                noRowsToShow: 'No hay productos disponibles.'
            },
            onGridReady: function(params) {
                fetchProductosData(params.api);
            }
        };

        new agGrid.Grid(document.querySelector('#productosGrid'), gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });
    });

    function fetchProductosData(gridApi) {
        fetch('<?= base_url("productos/getProductos") ?>')
            .then(response => response.json())
            .then(data => gridApi.applyTransaction({
                add: data
            }))
            .catch(error => console.error('Error al cargar productos:', error));
    }

    function accionesRenderer(params) {
        return `
            <button onclick="editarProducto(${params.data.id_producto})" class="btn btn-warning">Editar</button>
            <button onclick="eliminarProducto(${params.data.id_producto})" class="btn btn-danger">Eliminar</button>
        `;
    }

    function imagenRenderer(params) {
        const imagenUrl = params.value;
        return `<img src="${imagenUrl}" alt="Imagen Producto" style="width: 100px; height: 100px; object-fit: cover;">`;
    }

    function abrirModalAgregarProducto() {
        // Resetear el formulario y la imagen de vista previa
        document.getElementById('productoForm').reset();
        document.getElementById('id_producto').value = '';
        document.getElementById('productoModalLabel').innerText = 'Agregar Producto';
        $('#productoModal').modal('show');
    }

    function editarProducto(id_producto) {
        // Redirigir a la vista de edición con el ID del producto
        window.location.href = `<?= base_url("productos/editarVista") ?>/${id_producto}`;
    }

    function guardarProducto() {
        const formData = new FormData(document.getElementById('productoForm'));
        const idProducto = document.getElementById('id_producto').value;
        const url = idProducto ?
            `<?= base_url("productos/editarProducto") ?>/${idProducto}` :
            '<?= base_url("productos/agregarProducto") ?>';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#productoModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error en la solicitud.');
            }
        });
    }
    function eliminarProducto(id_producto) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            $.ajax({
                url: `<?= base_url("productos/eliminarProducto") ?>/${id_producto}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error en la solicitud de eliminación.');
                }
            });
        }
    }
</script>


<?= $this->endSection() ?>