<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<h2 class="tituloProveedores">Vacaciones</h2>
<div class="d-flex justify-content-between mb-3 btnFamiliaProveedor">
    <button onclick="abrirModalAgregar()" class="boton btnAdd">Añadir Vacaciones</button>
    <button id="clear-filters" class="boton btnEliminarfiltros">Quitar Filtros</button>
</div>
<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<!-- Modal para agregar o editar vacaciones -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Añadir Vacaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="editVacacionesForm">
                    <div class="form-group">
                        <label for="user_id">Usuario</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="" disabled selected>Seleccione un usuario</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="desde">Desde</label>
                        <input type="text" class="form-control" id="desde" name="desde" placeholder="dd/mm/yyyy" required>
                    </div>
                    <div class="form-group">
                        <label for="hasta">Hasta</label>
                        <input type="text" class="form-control" id="hasta" name="hasta" placeholder="dd/mm/yyyy" required>
                    </div>
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                    </div>
                    <input type="hidden" name="id" id="id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="boton btnGuardar" id="saveEditBtn">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    let isEditing = false;
    let usuarios = [];

    document.addEventListener('DOMContentLoaded', function() {
        fetchUsuarios();

        const columnDefs = [
            { headerName: "Acciones", field: "acciones", cellRenderer: actionCellRenderer, filter: false, minWidth: 200 },
            { headerName: "ID", field: "id", filter: 'agTextColumnFilter', hide: true },
            { headerName: "Usuario", field: "nombre_usuario", filter: 'agTextColumnFilter' },
            { headerName: "Desde", field: "desde", filter: 'agDateColumnFilter' },
            { headerName: "Hasta", field: "hasta", filter: 'agDateColumnFilter' },
            { headerName: "Observaciones", field: "observaciones", filter: 'agTextColumnFilter' }
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
            localeText: { noRowsToShow: 'No hay registros disponibles.' }
        };
        const eGridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(eGridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });

        // Inicializar Flatpickr en los campos de fecha
        flatpickr("#desde", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#hasta", {
            dateFormat: "d/m/Y"
        });
    });

    function fetchUsuarios() {
        fetch('<?= base_url("vacaciones/getUsuarios") ?>')
            .then(response => response.json())
            .then(data => {
                usuarios = data;
                const userSelect = document.getElementById('user_id');
                userSelect.innerHTML = '<option value="" disabled selected>Seleccione un usuario</option>';
                data.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = user.nombre_usuario;
                    userSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar los usuarios:', error));
    }

    function fetchData(gridApi) {
        fetch('<?= base_url("vacaciones/getVacaciones") ?>')
            .then(response => response.json())
            .then(data => gridApi.applyTransaction({ add: data }))
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    function actionCellRenderer(params) {
        const links = params.data.acciones;
        return `
            <button onclick="editarVacaciones('${params.data.id}', '${params.data.user_id}', '${params.data.desde}', '${params.data.hasta}', '${params.data.observaciones}')" class="btn botonTabla btnEditarTabla" title="Editar">Editar</button>
            <button onclick="eliminarVacaciones('${params.data.id}')" class="btn botonTabla btnEliminarTabla" title="Eliminar">Eliminar</button>
        `;
    }

    function abrirModalAgregar() {
        $('#editModalLabel').text('Añadir Vacaciones');
        $('#editVacacionesForm')[0].reset();
        $('#id').val('');
        $('#user_id').val(''); // Asegurarse de que el select esté vacío
        isEditing = false;
        $('#editModal').modal('show');
    }

    function editarVacaciones(id, user_id, desde, hasta, observaciones) {
        $('#editModalLabel').text('Editar Vacaciones');
        $('#id').val(id);
        $('#user_id').val(user_id);
        $('#desde').val(desde);
        $('#hasta').val(hasta);
        $('#observaciones').val(observaciones);
        isEditing = true;
        $('#editModal').modal('show');

        // Set the selected user in the select dropdown
        const userSelect = document.getElementById('user_id');
        const selectedUser = usuarios.find(user => user.id == user_id);
        if (selectedUser) {
            userSelect.value = selectedUser.id;
        }

        // Inicializar Flatpickr en los campos de fecha
        flatpickr("#desde", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#hasta", {
            dateFormat: "d/m/Y"
        });
    }

    $(document).on('click', '#saveEditBtn', function() {
        var formData = $('#editVacacionesForm').serialize();
        var url = isEditing ? '<?= base_url("vacaciones/save") ?>' : '<?= base_url("vacaciones/save") ?>';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error en la solicitud.');
            }
        });
    });

    function eliminarVacaciones(id) {
        if (confirm("¿Estás seguro de eliminar estas vacaciones?")) {
            $.ajax({
                url: '<?= base_url("vacaciones/delete") ?>/' + id,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: No se pudo eliminar las vacaciones.');
                    }
                },
            });
        }
    }
</script>

<?= $this->endSection() ?>