<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!-- Estilos de AG-Grid -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<!-- Estilos de Bootstrap (si no se han incluido globalmente) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Scripts de jQuery y Bootstrap JS (si no se han incluido globalmente) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Estilos y Scripts de AG-Grid -->
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>



<h2>Menú de la aplicación</h2>

<!-- Botón para abrir el modal -->
<button class="btn btn-success mb-3" id="btnAddSubMenu" data-toggle="modal" data-target="#menuModal">Añadir
    Menú</button>

<!-- AG-Grid container para menús sin dependencias -->
<div id="menuGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<!-- Modal para editar un menú -->
<div class="modal fade" id="editSubMenuModal" tabindex="-1" aria-labelledby="editSubMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubMenuModalLabel">Editar Menú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar un menú -->
                <form id="editSubMenuForm">
                    <input type="hidden" id="editSubMenuId" name="id">
                    <div class="form-group">
                        <label for="posicion">Posición</label>
                        <input type="number" class="form-control" name="posicion" id="editPosicion" required>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" name="titulo" id="editTitulo" required>
                    </div>
                    <div class="form-group">
                        <label for="nivel">Nivel</label>
                        <select class="form-control" name="nivel" id="editNivel" required>
                            <option value="">Selecciona un nivel</option>
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel['id_nivel'] ?>"><?= $nivel['nombre_nivel'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enlace">Enlace</label>
                        <input type="text" class="form-control" name="enlace" id="editEnlace" required>
                    </div>
                    <div class="form-group">
                        <label for="activo">Activo</label>
                        <select class="form-control" name="activo" id="editActivo">
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="url_especial">URL Especial</label>
                        <select class="form-control" name="url_especial" id="editUrlEspecial">
                            <option value="0">No, url genérica.</option>
                            <option value="1">Sí, url personalizada.</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nueva_pestana">Nueva Pestaña</label>
                        <select class="form-control" name="nueva_pestana" id="editNuevaPestana">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal para añadir un nuevo menú -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">Añadir Submenú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para añadir un nuevo submenú -->
                <form id="addSubMenuForm">
                    <input type="hidden" id="dependencia" name="dependencia" value="<?= $id ?>">

                    <div class="form-group">
                        <label for="posicion">Posición</label>
                        <input type="number" class="form-control" name="posicion" id="addPosicion" required>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" name="titulo" id="addTitulo" required>
                    </div>
                    <div class="form-group">
                        <label for="nivel">Nivel</label>
                        <select class="form-control" name="nivel" id="addNivel" required>
                            <option value="">Selecciona un nivel</option>
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel['id_nivel'] ?>"><?= $nivel['nombre_nivel'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enlace">Enlace</label>
                        <input type="text" class="form-control" name="enlace" id="addEnlace" required>
                    </div>

                    <div class="form-group">
                        <label for="activo">Activo</label>
                        <select class="form-control" name="activo" id="addActivo">
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="url_especial">URL Especial</label>
                        <select class="form-control" name="url_especial" id="addUrlEspecial">
                            <option value="0">No, url genérica.</option>
                            <option value="1">Sí, url personalizada.</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nueva_pestana">Nueva Pestaña</label>
                        <select class="form-control" name="nueva_pestana" id="addNuevaPestana">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir Submenú</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
<!-- Estilos y Scripts de AG-Grid -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Definir columnas para el AG-Grid
        const columnDefs = [
            {
                headerName: "Acciones",
                cellRenderer: function (params) {
                    return ` 
                <button onclick="editSubMenu(${params.data.id})" class="btn btn-warning btn-sm">Editar</button>
                <button onclick="deleteSubMenu(${params.data.id})" class="btn btn-danger btn-sm">Eliminar</button>
            `;
                }
            },
            { headerName: "Posición", field: "posicion" },
            { headerName: "Título", field: "titulo" },
            { headerName: "Enlace", field: "enlace" },
            { headerName: "Nivel", field: "nivel" },
            { headerName: "Activo", field: "activo", valueFormatter: (params) => params.value === '1' ? 'Activo' : 'Desactivado' }
        ];


        const gridOptions = {
            columnDefs: columnDefs,
            rowData: <?= json_encode($menus['submenus'] ?? []); ?>,

            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            defaultColDef: {
                flex: 1,
                minWidth: 100,
                sortable: true,
                floatingFilter: true,
                resizable: true
            },
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            onGridReady: function (params) {
                const gridApi = params.api;
                fetchEmpresasData(gridApi);
            },
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            }
        };

        const eGridDiv = document.querySelector('#menuGrid');
        new agGrid.Grid(eGridDiv, gridOptions);
    });

    function editSubMenu(id) {
        if (isNaN(id) || id <= 0) {
            alert('ID no válido');
            return;
        }

        console.log('ID del menú:', id);

        $.ajax({
            url: '<?= base_url('menu/edit') ?>/' + id,
            type: 'GET',
            success: function (response) {
                if (response.success) {
                    $('#editSubMenuId').val(response.menu.id);
                    $('#editPosicion').val(response.menu.posicion);
                    $('#editTitulo').val(response.menu.titulo);
                    $('#editNivel').val(response.menu.nivel);
                    $('#editActivo').val(response.menu.activo);
                    $('#editUrlEspecial').val(response.menu.url_especial);
                    $('#editNuevaPestana').val(response.menu.nueva_pestana);
                    $('#editEnlace').val(response.menu.enlace);

                    $('#editSubMenuModal').modal('show');
                } else {
                    alert('Error al cargar los datos del menú.');
                }
            },
            error: function () {
                alert('Error al cargar los datos del menú.');
            }
        });
    }

    $('#editSubMenuForm').on('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        const id = $('#editSubMenuId').val();

        $.ajax({
            url: `<?= base_url('menu/updateSubmenu') ?>/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert('Menú actualizado con éxito.');
                    $('#editSubMenuModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error al actualizar el menú: ' + response.message);
                }
            },
            error: function () {
                alert('Error en la solicitud.');
            }
        });
    });


    function deleteSubMenu(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este menú?')) {
            $.ajax({
                url: '<?= base_url('menu/delete') ?>/' + id,
                type: 'DELETE',
                success: function (response) {
                    if (response.success) {
                        alert('Menú eliminado con éxito.');
                        location.reload();
                    } else {
                        alert('Error al eliminar el menú.');
                    }
                },
                error: function () {
                    alert('Error en la solicitud. Por favor, inténtelo de nuevo.');
                }
            });
        }
    }

    // Manejo de la creación de nuevo menú
    document.getElementById('addSubMenuForm').addEventListener('submit', function (event) {
        event.preventDefault();

        // Recoger los datos del formulario
        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        // Realizar la solicitud AJAX para añadir el submenú
        $.ajax({
            url: '<?= base_url('menu/addSubmenu') ?>',
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    alert('Menú añadido con éxito.');
                    $('#menuModal').modal('hide'); // Cerrar el modal
                    location.reload(); // Recargar la página
                } else {
                    alert('Error al añadir el submenú.');
                }
            },
            error: function () {
                alert('Error en la solicitud. Por favor, inténtelo de nuevo.');
            }
        });
    });


</script>

<?= $this->endSection() ?>