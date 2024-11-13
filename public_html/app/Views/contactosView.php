<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<title>Contactos</title>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<h2>Contactos</h2>

<div class="d-flex justify-content-start mb-3">
<button class="btn btn-success me-2" onclick="abrirModalAgregarContacto()">Añadir Contacto</button>
    <button id="clear-filters" class="btn btn-danger">Quitar Filtros</button>
</div>

<div id="contactosGrid" class="ag-theme-alpine" style="height: 400px; width: 100%;"></div>

<!-- Modal único para agregar o editar contacto -->
<div class="modal fade" id="addContactoModal" tabindex="-1" aria-labelledby="addContactoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addContactoModalLabel">Añadir Contacto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addContactoForm">
                    <input type="hidden" id="id_contacto" name="id_contacto"> <!-- Campo oculto para ID del contacto -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>
                    <div class="mb-3">
                        <label for="empresa" class="form-label">Empresa</label>
                        <select class="form-control" id="id_cliente" name="id_cliente" required>
                            <option value="">Seleccionar Empresa</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?= $cliente['id_cliente'] ?>"><?= $cliente['nombre_cliente'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="telf" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telf" name="telf" required>
                    </div>
                    <div class="mb-3">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="cargo" name="cargo">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="guardarContacto()">Guardar Contacto</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columnDefs = [{
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: params => `
                    <button onclick="editarContacto(${params.data.id_contacto})" class="btn btn-primary btn-sm">Editar</button>
                    <button onclick="eliminarContacto(${params.data.id_contacto})" class="btn btn-danger btn-sm">Eliminar</button>
                `,
                filter: false,
                minWidth: 200
            },
            {
                headerName: "Nombre",
                field: "nombre",
                filter: 'agTextColumnFilter',
                minWidth: 150
            },
            {
                headerName: "Apellidos",
                field: "apellidos",
                filter: 'agTextColumnFilter',
                minWidth: 150
            },
            {
                headerName: "Teléfono",
                field: "telf",
                filter: 'agTextColumnFilter',
                minWidth: 100
            },
            {
                headerName: "Cargo",
                field: "cargo",
                filter: 'agTextColumnFilter',
                minWidth: 120
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
            rowHeight: 50,
            localeText: {
                noRowsToShow: 'No hay contactos disponibles.'
            },
            onGridReady: function(params) {
                fetchContactosData(params.api);
            }
        };

        const gridDiv = document.querySelector('#contactosGrid');
        new agGrid.Grid(gridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });
    });

    function fetchContactosData(gridApi) {
        fetch('<?= base_url("contactos/getContactos") ?>')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    gridApi.applyTransaction({
                        add: data
                    });
                } else {
                    console.error('Los datos recibidos no son un array:', data);
                }
            })
            .catch(error => console.error('Error al cargar contactos:', error));
    }
    function agregarContacto() {
    const formData = $('#addContactoForm').serialize();
    $.post('<?= base_url("contactos/agregarContacto") ?>', formData)
        .done(response => {
            if (response.success) {
                $('#addContactoModal').modal('hide');
                $('#addContactoForm')[0].reset();
                location.reload(); // Recargar la página
            } else {
                alert(response.message);
            }
        });
}
function abrirModalAgregarContacto() {
    $('#addContactoModalLabel').text('Añadir Contacto');
    $('#id_contacto').val(''); // Asegúrate de que el campo oculto ID esté vacío
    $('#addContactoForm')[0].reset(); // Restablecer todos los campos del formulario
    $('#addContactoModal').modal('show'); // Abrir el modal
}


function guardarContacto() {
    const formData = $('#addContactoForm').serialize();
    const idContacto = $('#id_contacto').val();
    const url = idContacto ? `<?= base_url("contactos/actualizarContacto") ?>/${idContacto}` : '<?= base_url("contactos/agregarContacto") ?>';

    $.post(url, formData)
        .done(response => {
            if (response.success) {
                $('#addContactoModal').modal('hide');
                $('#addContactoForm')[0].reset();
                location.reload(); // Recargar la página
            } else {
                alert(response.message);
            }
        });
}

function editarContacto(id) {
    $.get(`<?= base_url("contactos/getContacto") ?>/${id}`, function(data) {
        if (data) {
            $('#addContactoModalLabel').text('Editar Contacto');
            $('#id_contacto').val(data.id_contacto); // Cargar el ID en el campo oculto
            $('#nombre').val(data.nombre);
            $('#apellidos').val(data.apellidos);
            $('#id_cliente').val(data.id_cliente);
            $('#telf').val(data.telf);
            $('#cargo').val(data.cargo);
            $('#addContactoModal').modal('show'); // Abrir el modal en modo edición
        } else {
            alert('Error al cargar los datos del contacto.');
        }
    }).fail(function() {
        alert('Error: Contacto no encontrado.');
    });
}
    function eliminarContacto(id) {
        if (confirm('¿Deseas eliminar este contacto?')) {
            $.post(`<?= base_url("contactos/eliminarContacto") ?>/${id}`)
                .done(response => {
                    if (response.success) {
                        location.reload(); // Recargar la página después de eliminar
                    } else {
                        alert(response.message);
                    }
                });
        }
    }
</script>
<?= $this->endSection() ?>