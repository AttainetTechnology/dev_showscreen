<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<h2 class="tituloProveedores">Fichajes</h2>
<div class="btnsEditPedido">
    <button id="clear-filters" class="boton btnEliminarfiltros">Quitar Filtros

    </button>
</div>
<br>
<!-- Modal para editar el registro -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Fichaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
    <form id="editFichajeForm">
        <input type="hidden" id="fichajeId" name="id">
        
        <!-- Campo Nombre Usuario (ahora es un select) -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <select class="form-control" id="nombre" name="nombre">
                <!-- Las opciones se rellenarán con JavaScript -->
            </select>
        </div>

        <div class="mb-3">
    <label for="entrada" class="form-label">Entrada</label>
    <input type="datetime-local" class="form-control" id="entrada" name="entrada">
</div>

<div class="mb-3">
    <label for="salida" class="form-label">Salida</label>
    <input type="datetime-local" class="form-control" id="salida" name="salida">
</div>

        <div class="mb-3">
            <label for="incidencia" class="form-label">Incidencia</label>
            <input type="text" class="form-control" id="incidencia" name="incidencia">
        </div>

        <div class="mb-3">
            <label for="extras" class="form-label">Extras</label>
            <select class="form-control" id="extras" name="extras">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary" id="saveBtn">Guardar cambios</button>

    </form>
</div>

        </div>
    </div>
</div>

<div id="myGrid" class="ag-theme-alpine" ></div>
<br>

<script>
let isEditing = false;
document.addEventListener('DOMContentLoaded', function () {
    const columnDefs = [{
            headerName: "Acciones",
            field: "acciones",
            cellRenderer: params => {
                const links = params.data.acciones;
                return `
                    <button onclick="editarFichaje('${links.editar}', '${params.data.nombre}', '${params.data.id}')" class="btn botonTabla btnEditarTabla" title="Editar">
                        Editar
                    </button>
                    <button onclick="eliminarFamilia('${links.eliminar}')" class="btn botonTabla btnEliminarTabla" title="Eliminar">
                        Eliminar
                    </button>
                `;
            },
            filter: false,
            minWidth: 200
        },
        {
            headerName: "Usuario",
            field: "nombre_usuario",
            filter: 'agTextColumnFilter'
        },
        {
            headerName: "Entrada",
            field: "entrada",
            filter: 'agTextColumnFilter',
            cellRenderer: function(params) {
                return formatDateTime(params.value);
            }
        },
        {
            headerName: "Salida",
            field: "salida",
            filter: 'agTextColumnFilter',
            cellRenderer: function(params) {
                return formatDateTime(params.value);
            }
        },       

        {
            headerName: "Total",
            field: "total",
            filter: 'agTextColumnFilter'
        },
        {
            headerName: "Incidencia",
            field: "incidencia",
            filter: 'agTextColumnFilter'
        },
        {
            headerName: "Extras",
            field: "extras",
            filter: 'agTextColumnFilter', 
            cellRenderer: function(params) {
                return params.value === 'Sí' ? 'Sí' : 'No';
            },
            filterParams: {
                values: ['Sí', 'No'], 
            }
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
        onGridReady: function (params) {
            const gridApi = params.api;
            fetchData(gridApi);
        },
        rowHeight: 60,
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
function formatDateTime(dateTimeStr) {
    if (!dateTimeStr) return '';

    const date = new Date(dateTimeStr); // Parse the date
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).slice(-2); // Last 2 digits of the year
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day}/${month}/${year}, ${hours}:${minutes}`; // Formato de tabla
}
document.getElementById('entrada').value = formatToDateTimeLocal(fichaje.entrada);
document.getElementById('salida').value = formatToDateTimeLocal(fichaje.salida);

// Función para convertir las fechas a formato `yyyy-mm-ddThh:mm`
function formatToDateTimeLocal(dateTimeStr) {
    if (!dateTimeStr) return '';

    const date = new Date(dateTimeStr);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`; // Formato para datetime-local
}



function fetchData(gridApi) {
    fetch('<?= base_url("fichajes/getFichajes") ?>')
        .then(response => response.json())
        .then(data => gridApi.applyTransaction({
            add: data
        }))
        .catch(error => console.error('Error al cargar los datos:', error));
}
function editarFichaje(editarUrl, nombre, id) {
    // Obtener los datos del registro a editar
    fetch(editarUrl)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            const fichaje = data.fichaje; // Fichaje a editar
            const usuarios = data.usuarios; // Lista de usuarios

            // Rellenar el formulario del modal
            document.getElementById('fichajeId').value = fichaje.id;
            document.getElementById('entrada').value = formatToDateTimeLocal(fichaje.entrada);
            document.getElementById('salida').value = formatToDateTimeLocal(fichaje.salida);
            document.getElementById('incidencia').value = fichaje.incidencia;
            document.getElementById('extras').value = fichaje.extras === 'Sí' ? '1' : '0';

            // Rellenar el select de nombre con los usuarios
            const nombreSelect = document.getElementById('nombre');
            nombreSelect.innerHTML = ''; // Limpiar el select antes de llenarlo
            usuarios.forEach(usuario => {
                const option = document.createElement('option');
                option.value = usuario.id; // Asignar el id como valor
                option.text = `${usuario.nombre_usuario} ${usuario.apellidos_usuario}`;
                if (usuario.id === fichaje.id_usuario) {
                    option.selected = true; // Marcar como seleccionado el usuario actual
                }
                nombreSelect.appendChild(option);
            });
            // Mostrar el modal
            $('#editModal').modal('show');
        })
        .catch(error => console.error('Error al obtener los datos:', error));
}
document.getElementById('saveBtn').addEventListener('click', function () {
    const form = document.getElementById('editFichajeForm');
    const formData = new FormData(form);  // Recoge todos los datos del formulario

    // Enviar la actualización de los datos al servidor
    fetch('<?= base_url('fichajes/actualizar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();  // Recargar la página para ver los cambios
            $('#editModal').modal('hide');  // Cerrar el modal
        } else {
            alert('Error al actualizar');
        }
    })
    .catch(error => console.error('Error al actualizar el fichaje:', error));
});


function eliminarFamilia(eliminarUrl) {
    if (confirm("¿Estás seguro de que quieres eliminar este fichaje?")) {
        fetch(eliminarUrl, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar');
            }
        })
        .catch(error => console.error('Error al eliminar el fichaje:', error));
    }
}



</script>
<?= $this->endSection() ?>