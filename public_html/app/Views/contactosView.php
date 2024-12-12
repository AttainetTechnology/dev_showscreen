<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<br>
<title>Contactos</title>

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<h2>Contactos</h2>

<div class="d-flex justify-content-between mb-3 btnFamiliaProveedor">
    <button class="boton btnAdd" onclick="abrirModalAgregarContacto()">Añadir Contacto
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
            <path
                d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z"
                fill="white" />
        </svg>
    </button>
    <button id="clear-filters" class="boton btnEliminarfiltros">Quitar Filtros
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
            <path
                d="M7.54974 8.04975C7.62521 7.97408 7.71487 7.91405 7.81358 7.87309C7.91229 7.83213 8.01812 7.81105 8.12499 7.81105C8.23186 7.81105 8.33768 7.83213 8.43639 7.87309C8.5351 7.91405 8.62476 7.97408 8.70024 8.04975L13 12.3511L17.2997 8.04975C17.3753 7.97421 17.465 7.91428 17.5637 7.8734C17.6624 7.83251 17.7682 7.81147 17.875 7.81147C17.9818 7.81147 18.0876 7.83251 18.1863 7.8734C18.285 7.91428 18.3747 7.97421 18.4502 8.04975C18.5258 8.12529 18.5857 8.21497 18.6266 8.31368C18.6675 8.41238 18.6885 8.51816 18.6885 8.625C18.6885 8.73183 18.6675 8.83762 18.6266 8.93632C18.5857 9.03502 18.5258 9.12471 18.4502 9.20025L14.1489 13.5L18.4502 17.7997C18.5258 17.8753 18.5857 17.965 18.6266 18.0637C18.6675 18.1624 18.6885 18.2682 18.6885 18.375C18.6885 18.4818 18.6675 18.5876 18.6266 18.6863C18.5857 18.785 18.5258 18.8747 18.4502 18.9502C18.3747 19.0258 18.285 19.0857 18.1863 19.1266C18.0876 19.1675 17.9818 19.1885 17.875 19.1885C17.7682 19.1885 17.6624 19.1675 17.5637 19.1266C17.465 19.0857 17.3753 19.0258 17.2997 18.9502L13 14.6489L8.70024 18.9502C8.62469 19.0258 8.53501 19.0857 8.43631 19.1266C8.33761 19.1675 8.23182 19.1885 8.12499 19.1885C8.01815 19.1885 7.91237 19.1675 7.81366 19.1266C7.71496 19.0857 7.62528 19.0258 7.54974 18.9502C7.47419 18.8747 7.41427 18.785 7.37339 18.6863C7.3325 18.5876 7.31146 18.4818 7.31146 18.375C7.31146 18.2682 7.3325 18.1624 7.37339 18.0637C7.41427 17.965 7.47419 17.8753 7.54974 17.7997L11.8511 13.5L7.54974 9.20025C7.47407 9.12477 7.41404 9.03511 7.37308 8.9364C7.33212 8.83769 7.31104 8.73187 7.31104 8.625C7.31104 8.51813 7.33212 8.4123 7.37308 8.31359C7.41404 8.21488 7.47407 8.12522 7.54974 8.04975Z"
                fill="white" />
        </svg>
    </button>
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
                    <div class="d-flex justify-content-end">
                        <button type="button" class="boton btnGuardar" onclick="guardarContacto()">Guardar Contacto
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27"
                                fill="none">
                                <path
                                    d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                                    fill="white" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Iniciando Ag-Grid...');

        const columnDefs = [
            { headerName: "Acciones", field: "acciones", cellRenderer: params => `
                <button onclick="editarContacto(${params.data.id_contacto})" class="btn botonTabla btnEditarTabla">Editar</button>
                <button onclick="eliminarContacto(${params.data.id_contacto})" class="btn botonTabla btnEliminarTabla">Eliminar</button>
            `, filter: false, minWidth: 200 },
            { headerName: "Nombre", field: "nombre", filter: 'agTextColumnFilter', minWidth: 150 },
            { headerName: "Apellidos", field: "apellidos", filter: 'agTextColumnFilter', minWidth: 150 },
            { headerName: "Teléfono", field: "telf", filter: 'agTextColumnFilter', minWidth: 100 },
            { headerName: "Cargo", field: "cargo", filter: 'agTextColumnFilter', minWidth: 120 }
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
            localeText: {
                noRowsToShow: 'No hay contactos disponibles.'
            },
            onGridReady: function (params) {
                fetchContactosData(params.api);
            }
        };

        const gridDiv = document.querySelector('#contactosGrid');
        if (!gridDiv) {
            console.error('El contenedor del grid no se encontró en el DOM.');
            return;
        }
        new agGrid.Grid(gridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });
    });

    function fetchContactosData(gridApi) {
        console.log('Cargando datos...');
        fetch('<?= base_url("contactos/getContactos") ?>')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    console.log('Datos recibidos:', data);
                    gridApi.applyTransaction({ add: data });
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
                    location.reload();
                } else {
                    alert(response.message);
                }
            });
    }

    function abrirModalAgregarContacto() {
        $('#addContactoModalLabel').text('Añadir Contacto');
        $('#id_contacto').val('');
        $('#addContactoForm')[0].reset();
        $('#addContactoModal').modal('show');
    }

    function guardarContacto() {
        const formData = $('#addContactoForm').serialize();
        const idContacto = $('#id_contacto').val();
        const url = idContacto
            ? `<?= base_url("contactos/actualizarContacto") ?>/${idContacto}`
            : '<?= base_url("contactos/agregarContacto") ?>';

        $.post(url, formData)
            .done(response => {
                if (response.success) {
                    $('#addContactoModal').modal('hide');
                    $('#addContactoForm')[0].reset();
                    location.reload();
                } else {
                    alert(response.message);
                }
            });
    }

    function editarContacto(id) {
        $.get(`<?= base_url("contactos/getContacto") ?>/${id}`, function (data) {
            if (data) {
                $('#addContactoModalLabel').text('Editar Contacto');
                $('#id_contacto').val(data.id_contacto);
                $('#nombre').val(data.nombre);
                $('#apellidos').val(data.apellidos);
                $('#id_cliente').val(data.id_cliente);
                $('#telf').val(data.telf);
                $('#cargo').val(data.cargo);
                $('#addContactoModal').modal('show');
            } else {
                alert('Error al cargar los datos del contacto.');
            }
        }).fail(function () {
            alert('Error: Contacto no encontrado.');
        });
    }

    function eliminarContacto(id) {
        if (confirm('¿Deseas eliminar este contacto?')) {
            $.post(`<?= base_url("contactos/eliminarContacto") ?>/${id}`)
                .done(response => {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                });
        }
    }
</script>

<?= $this->endSection() ?>