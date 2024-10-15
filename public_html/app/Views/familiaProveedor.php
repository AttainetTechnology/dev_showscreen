<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<h2 class="tituloProveedores">Familias de Proveedores</h2>
<div class="d-flex justify-content-between mb-3">
    <button onclick="abrirModalAgregar()" class="btn btn-primary">+ Añadir Familia</button>
    <button id="clear-filters" class="btn btn-secondary">Eliminar Filtros</button>
</div>
<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<!-- Modal para agregar o editar la familia -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header del modal con la cruz para cerrar -->
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Añadir Familia</h5> <!-- Cambia dinámicamente -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Cuerpo del modal con el formulario -->
            <div class="modal-body">
                <form id="editFamiliaForm">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <input type="hidden" name="id_familia" id="id_familia"> <!-- Para editar, este campo será necesario -->
                </form>
            </div>

            <!-- Footer del modal para los botones de acción -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveEditBtn">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    let isEditing = false; // Bandera para saber si estamos agregando o editando

    document.addEventListener('DOMContentLoaded', function() {
        const columnDefs = [
            {
        headerName: "Acciones",
        field: "acciones",
        cellRenderer: params => {
            const links = params.data.acciones;
            return `
                <button onclick="editarFamilia('${links.editar}', '${params.data.nombre}', '${params.data.id_familia}')" class="btn btn-warning btn-sm" title="Editar">
                    <i class="fa fa-pencil"></i> Editar
                </button>
                <button onclick="eliminarFamilia('${links.eliminar}')" class="btn btn-danger btn-sm" title="Eliminar">
                    <i class="fa fa-trash"></i> Eliminar
                </button>
            `;
        },
        filter: false,
        minWidth: 200
    },
            {
                headerName: "ID Familia",
                field: "id_familia",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Nombre",
                field: "nombre",
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
            rowHeight: 50,
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
        fetch('<?= base_url("familiaProveedor/getFamiliasProveedores") ?>')
            .then(response => response.json())
            .then(data => gridApi.applyTransaction({ add: data }))
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    // Función para abrir el modal para agregar una nueva familia
    function abrirModalAgregar() {
        $('#editModalLabel').text('Añadir Familia'); // Cambiar el título del modal
        $('#nombre').val(''); // Limpiar el campo nombre
        $('#id_familia').val(''); // Limpiar el campo id
        isEditing = false; // Cambiar la bandera para modo de agregar
        $('#editModal').modal('show'); // Mostrar el modal
    }

    // Función para abrir el modal para editar una familia
    function editarFamilia(url, nombre, idFamilia) {
        $('#editModalLabel').text('Editar Familia'); // Cambiar el título del modal
        $('#nombre').val(nombre); // Llenar el campo nombre con el valor correspondiente
        $('#id_familia').val(idFamilia); // Llenar el campo oculto con el id de la familia
        isEditing = true; // Cambiar la bandera a modo de edición
        $('#editModal').modal('show'); // Mostrar el modal
    }

    // Guardar los cambios del formulario mediante AJAX
    $(document).on('click', '#saveEditBtn', function() {
        var formData = $('#editFamiliaForm').serialize(); // Serializar el formulario
        var url = isEditing ? '<?= base_url("familiaProveedor/actualizarFamilia") ?>' : '<?= base_url("familiaProveedor/agregarFamilia") ?>';

        $.ajax({
            url: url, // Dependiendo de si se está agregando o editando, cambiar la URL
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editModal').modal('hide'); // Cerrar el modal
                    location.reload(); // Recargar la página para reflejar los cambios
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error en la solicitud.');
            }
        });
    });

   // Función para eliminar una familia
function eliminarFamilia(url) {
    if (confirm("¿Estás seguro de eliminar esta familia?")) {
        $.ajax({
            url: url, // La URL del controlador para eliminar
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    location.reload(); 
                } else {
                    alert('Error: No se pudo eliminar la familia.');
                }
            },
        });
    }
}

</script>

<?= $this->endSection() ?>
