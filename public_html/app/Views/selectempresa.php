<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="tituloEmpresas">Empresas</h2>
<br>
<div class="d-flex justify-content-between mb-3 btnFamiliaProveedor">
    <button onclick="abrirModalAgregar()" class="boton btnAdd">Añadir Empresa</button>
</div>

<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<!-- Modal para editar empresa -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarEmpresa" method="POST" action="<?= base_url('select_empresa/editar') ?>"
                    enctype="multipart/form-data">
                    <input type="hidden" id="id_empresa" name="id_empresa">
                    <div class="mb-3">
                        <label for="nombre_empresa" class="form-label">Nombre de la Empresa</label>
                        <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa">
                    </div>
                    <div class="mb-3">
                        <label for="db_name" class="form-label">Nombre de la Base de Datos</label>
                        <input type="text" class="form-control" id="db_name" name="db_name">
                    </div>
                    <div class="mb-3">
                        <label for="db_user" class="form-label">Usuario DB</label>
                        <input type="text" class="form-control" id="db_user" name="db_user">
                    </div>
                    <div class="mb-3">
                        <label for="db_password" class="form-label">Contraseña DB</label>
                        <input type="text" class="form-control" id="db_password" name="db_password">
                    </div>
                    <div class="mb-3">
                        <label for="NIF" class="form-label">NIF</label>
                        <input type="text" class="form-control" id="NIF" name="NIF">
                    </div>
                    <div class="mb-3">
                        <label for="logo_empresa" class="form-label">Logo Empresa</label>
                        <input type="file" class="form-control" id="logo_empresa" name="logo_empresa">
                        <div id="current_logo_empresa" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="favicon" class="form-label">Favicon</label>
                        <input type="file" class="form-control" id="favicon" name="favicon">
                        <div id="current_favicon" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="logo_fichajes" class="form-label">Logo Fichajes</label>
                        <input type="file" class="form-control" id="logo_fichajes" name="logo_fichajes">
                        <div id="current_logo_fichajes" class="mt-2"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columnDefs = [
            {
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: params => {
                    return `
        <button onclick="editarEmpresa(${params.data.id})" class="btn botonTabla btnMover">Editar</button>
        <button onclick="accederEmpresa(${params.data.id})" class="btn botonTabla btnMover">Acceder</button>
        <button onclick="eliminarEmpresa(${params.data.id})" class="btn botonTabla btnEliminar">Eliminar</button>
    `;
                },

                filter: false,
                maxWidth: 200
            },
            {
                headerName: "ID",
                field: "id",
                filter: 'agTextColumnFilter',
                maxWidth: 120
            },
            {
                headerName: "Nombre de la Empresa",
                field: "nombre_empresa",
                filter: 'agTextColumnFilter',
                minWidth: 220
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
            rowData: <?= json_encode($empresas) ?>,
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            }
        };

        const eGridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(eGridDiv, gridOptions);
    });
    function editarEmpresa(id) {
        fetch(`<?= base_url('select_empresa/get_empresa') ?>/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('id_empresa').value = data.id;
                document.getElementById('nombre_empresa').value = data.nombre_empresa;
                document.getElementById('db_name').value = data.db_name;
                document.getElementById('db_user').value = data.db_user;
                document.getElementById('db_password').value = data.db_password;
                document.getElementById('NIF').value = data.NIF;

                // Construir rutas absolutas para las imágenes
                const baseUrl = '<?= base_url('public/assets/uploads/files') ?>';
                document.getElementById('current_logo_empresa').innerHTML = data.logo_empresa
                    ? `<img src="${baseUrl}/${data.logo_empresa}" width="100" alt="Logo Empresa">`
                    : 'No hay logo';
                document.getElementById('current_favicon').innerHTML = data.favicon
                    ? `<img src="${baseUrl}/${data.favicon}" width="50" alt="Favicon">`
                    : 'No hay favicon';
                document.getElementById('current_logo_fichajes').innerHTML = data.logo_fichajes
                    ? `<img src="${baseUrl}/${data.logo_fichajes}" width="100" alt="Logo Fichajes">`
                    : 'No hay logo de fichajes';

                document.getElementById('modalEditarLabel').textContent = 'Editar Empresa';

                $('#modalEditar').modal('show');
            });
    }

    function abrirModalAgregar() {
        // Limpia los campos del formulario
        document.getElementById('formEditarEmpresa').reset();
        document.getElementById('id_empresa').value = '';
        document.getElementById('current_logo_empresa').innerHTML = '';
        document.getElementById('current_favicon').innerHTML = '';
        document.getElementById('current_logo_fichajes').innerHTML = '';

        // Cambiar el título del modal
        document.getElementById('modalEditarLabel').textContent = 'Añadir Empresa';

        // Mostrar el modal
        $('#modalEditar').modal('show');
    }
    function eliminarEmpresa(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta empresa?')) {
            fetch(`<?= base_url('select_empresa/eliminar') ?>/${id}`, {
                method: 'DELETE',
            })
                .then(response => {
                    if (response.ok) {
                        alert('Empresa eliminada correctamente.');
                        // Recargar la página o actualizar la tabla
                        location.reload();
                    } else {
                        alert('Hubo un problema al eliminar la empresa.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error en la conexión con el servidor.');
                });
        }
    }

    function accederEmpresa(id) {
        // Lógica para acceder a la empresa, por ejemplo, redirigir a otra página
        window.location.href = '<?= base_url("/Acceso/") ?>' + id;
    }
</script>

<?= $this->endSection() ?>