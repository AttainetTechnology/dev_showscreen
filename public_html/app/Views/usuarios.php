<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">

<h2>Usuarios</h2>
<div class="mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fa fa-plus"></i> Añadir Usuario
    </button>
</div>

<!-- Tabla -->
<div id="gridUsuarios" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<!-- Modal para añadir usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Añadir Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="form-group">
                        <label for="nombre_usuario">Nombre:</label>
                        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidos_usuario">Apellidos:</label>
                        <input type="text" class="form-control" id="apellidos_usuario" name="apellidos_usuario"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>
<div id="gridUsuarios" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columnDefs = [
            {
                headerName: "Acciones",
                cellRenderer: params => `
                    <a href="/usuarios/editar/${params.data.id}" class="btn btn-warning btn-sm">Editar</a>
                    <button onclick="eliminarUsuario(${params.data.id})" class="btn btn-danger btn-sm">Eliminar</button>
                `
            },
            { headerName: "Nombre", field: "nombre_usuario", sortable: true, filter: true },
            { headerName: "Apellidos", field: "apellidos_usuario", sortable: true, filter: true },
            { headerName: "Email", field: "email", sortable: true, filter: true },
            { headerName: "Teléfono", field: "telefono", sortable: true, filter: true },

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
            rowHeight: 60,
            onGridReady: params => fetchUsuarios(params.api)
        };

        new agGrid.Grid(document.querySelector('#gridUsuarios'), gridOptions);
    });

    function fetchUsuarios(gridApi) {
        fetch('/usuarios/getUsuarios')
            .then(response => response.json())
            .then(data => {
                gridApi.applyTransaction({ add: data });
            })
            .catch(error => console.error('Error al cargar usuarios:', error));
    }

// Guardar nuevo usuario
document.querySelector('#saveUserBtn').addEventListener('click', function () {
    const formData = new FormData(document.querySelector('#addUserForm'));
    fetch('/usuarios/crearUsuario', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('#addUserModal').querySelector('.btn-close').click();
            window.location.reload(); // Recargar la página
        } else {
            console.error('Error:', data.message);
        }
    })
    .catch(error => console.error('Error al añadir usuario:', error));
});
function eliminarUsuario(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        fetch(`/usuarios/eliminar/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload(); // Recargar la página para actualizar la tabla
            } else {
                console.error('Error al eliminar el usuario:', data.message);
            }
        })
        .catch(error => console.error('Error al eliminar usuario:', error));
    }
}



</script>

<?= $this->endSection() ?>