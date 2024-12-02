<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!-- Estilos de AG-Grid -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-theme-alpine.css">

<!-- Estilos de Bootstrap (si no se han incluido globalmente) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Scripts de jQuery y Bootstrap JS (si no se han incluido globalmente) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Estilos y Scripts de AG-Grid -->
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>



<h2>Menú de la aplicación</h2>

<!-- Botón para abrir el modal -->
<button class="btn btn-success mb-3" id="btnAddMenu" data-toggle="modal" data-target="#menuModal">Añadir Menú</button>

<!-- AG-Grid container para menús sin dependencias -->
<div id="menuGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<!-- Modal para editar un menú -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">Editar Menú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar un menú -->
                <form id="editMenuForm">
                    <input type="hidden" id="editMenuId" name="id">
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
                                <option value="<?= $nivel->id_nivel ?>"><?= $nivel->nombre_nivel ?></option>
                            <?php endforeach; ?>
                        </select>
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
                        <input type="text" class="form-control" name="url_especial" id="editUrlEspecial">
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
                <h5 class="modal-title" id="menuModalLabel">Añadir Menú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para añadir un nuevo menú -->
                <form id="addMenuForm">
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
                                <option value="<?= $nivel->id_nivel ?>"><?= $nivel->nombre_nivel ?></option>
                            <?php endforeach; ?>
                        </select>
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
                        <input type="text" class="form-control" name="url_especial" id="addUrlEspecial">
                    </div>
                    <div class="form-group">
                        <label for="nueva_pestana">Nueva Pestaña</label>
                        <select class="form-control" name="nueva_pestana" id="addNuevaPestana">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir Menú</button>
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
                <button onclick="editMenu(${params.data.id})" class="btn btn-warning btn-sm">Editar</button>
                <button onclick="deleteMenu(${params.data.id})" class="btn btn-danger btn-sm">Eliminar</button>
                <a href="<?= base_url('menu/submenus') ?>/${params.data.id}" class="btn btn-info btn-sm">Submenú</a>
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
            rowData: <?= json_encode($menus['sin_dependencia']); ?>,
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
        };

        const eGridDiv = document.querySelector('#menuGrid');
        new agGrid.Grid(eGridDiv, gridOptions);
    });
    function editMenu(id) {

    if (isNaN(id) || id <= 0) {
        alert('ID no válido');
        return;
    }

    $.ajax({
        url: '<?= base_url('menu/edit') ?>/' + id,
        type: 'GET',
        success: function (response) {
            if (response.success) {

                // Rellenar los campos del modal con los datos
                $('#editMenuId').val(response.menu.id);
                $('#editPosicion').val(response.menu.posicion);
                $('#editTitulo').val(response.menu.titulo);
                $('#editNivel').val(response.menu.nivel);
                $('#editActivo').val(response.menu.activo);
                $('#editUrlEspecial').val(response.menu.url_especial);
                $('#editNuevaPestana').val(response.menu.nueva_pestana);

                // Mostrar el modal
                $('#editMenuModal').modal('show');
            } else {
                alert('Error al cargar los datos del menú.');
            }
        },
        error: function () {
            alert('Error al cargar los datos del menú.');
        }
    });
}

    // Guardar cambios de menú
    $('#editMenuForm').on('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '<?= base_url('menu/edit') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert('Menú actualizado con éxito.');
                    $('#editMenuModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error al actualizar el menú.');
                }
            },
            error: function () {
                alert('Error en la solicitud.');
            }
        });
    });
 
    function deleteMenu(id) {
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
    document.getElementById('addMenuForm').addEventListener('submit', function (event) {
        event.preventDefault();

        // Recoger los datos del formulario
        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        // Realizar la solicitud AJAX para añadir el menú
        $.ajax({
            url: '<?= base_url('menu/add') ?>',
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    alert('Menú añadido con éxito.');
                    $('#menuModal').modal('hide'); // Cerrar el modal
                    location.reload(); // Recargar la página
                } else {
                    alert('Error al añadir el menú.');
                }
            },
            error: function () {
                alert('Error en la solicitud. Por favor, inténtelo de nuevo.');
            }
        });
    });
</script>

<?= $this->endSection() ?>