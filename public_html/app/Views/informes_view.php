<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<!-- ag-Grid CSS -->
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">

<!-- ag-Grid JS -->
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">

<h2>Gestión de Informes</h2>

<div class="d-flex justify-content-between mb-3">
    <button class="boton btnAdd" onclick="abrirModalAgregarInforme()">Añadir Informe</button>
    <button id="clear-filters" class="boton btnEliminarfiltros">Quitar Filtros</button>
</div>

<div id="informesGrid" class="ag-theme-alpine" style="height: 400px; width: 100%;"></div>

<!-- Modal único para agregar o editar informe -->
<div class="modal fade" id="addInformeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInformeModalLabel">Añadir Informe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addInformeForm">
                    <input type="hidden" id="id_informe" name="id_informe">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="desde" class="form-label">Desde</label>
                        <input type="date" class="form-control" id="desde" name="desde" required>
                    </div>
                    <div class="mb-3">
                        <label for="hasta" class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="hasta" name="hasta" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="boton btnGuardar" onclick="guardarInforme()">Guardar Informe</button>
                    </div>
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
                cellRenderer: params => `
                    <button onclick="editarInforme(${params.data.id_informe})" class="btn botonTabla btnEditarTabla">Editar</button>
                    <button onclick="eliminarInforme(${params.data.id_informe})" class="btn botonTabla btnEliminarTabla">Eliminar</button>
                `,
                filter: false,
                minWidth: 200
            },
            { headerName: "Título", field: "titulo", filter: 'agTextColumnFilter', minWidth: 150 },
            {
                headerName: "Desde",
                field: "desde",
                filter: 'agDateColumnFilter',
                minWidth: 120,
                valueFormatter: params => formatDate(params.value),
                valueGetter: params => params.data.desde ? new Date(params.data.desde) : null,
            },
            {
                headerName: "Hasta",
                field: "hasta",
                filter: 'agDateColumnFilter',
                minWidth: 120,
                valueFormatter: params => formatDate(params.value),
                valueGetter: params => params.data.hasta ? new Date(params.data.hasta) : null,
            }
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: {
                flex: 1,
                sortable: true,
                filter: true,
                floatingFilter: true,
                resizable: true
            },
            rowData: [],
            pagination: true,
            paginationPageSize: 10,
            onGridReady: function (params) {
                fetchInformesData(params.api);
            }
        };

        const gridDiv = document.querySelector('#informesGrid');
        new agGrid.Grid(gridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });
    });

    function fetchInformesData(gridApi) {
        fetch('<?= base_url("informes/getInformes") ?>')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    gridApi.applyTransaction({ add: data });
                } else {
                    console.error('Los datos recibidos no son un array:', data);
                }
            })
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    function abrirModalAgregarInforme() {
        $('#addInformeModalLabel').text('Añadir Informe');
        $('#id_informe').val('');
        $('#addInformeForm')[0].reset();
        $('#addInformeModal').modal('show');
    }

    function guardarInforme() {
        const formData = $('#addInformeForm').serialize();
        const idInforme = $('#id_informe').val();
        const url = idInforme ? `<?= base_url("informes/actualizarInforme") ?>/${idInforme}` : '<?= base_url("informes/agregarInforme") ?>';

        $.post(url, formData)
            .done(response => {
                if (response.success) {
                    $('#addInformeModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            });
    }

    function editarInforme(id) {
        $.get(`<?= base_url("informes/getInforme") ?>/${id}`, data => {
            $('#addInformeModalLabel').text('Editar Informe');
            $('#id_informe').val(data.id_informe);
            $('#titulo').val(data.titulo);
            $('#desde').val(data.desde);
            $('#hasta').val(data.hasta);
            $('#addInformeModal').modal('show');
        });
    }

    function eliminarInforme(id) {
        if (confirm('¿Deseas eliminar este informe?')) {
            $.post(`<?= base_url("informes/eliminarInforme") ?>/${id}`)
                .done(response => {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                });
        }
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear().toString().slice(-2);
        return `${day}/${month}/${year}`;
    }
</script>

<?= $this->endSection() ?>
