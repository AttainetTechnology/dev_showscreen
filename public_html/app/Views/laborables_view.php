<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<br>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">

<h2>Laborables</h2>
<br>
<div id="laborablesGrid" class="ag-theme-alpine" style="height: 200px; width: 100%;"></div>

<div class="modal fade" id="editLaborablesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLaborablesModalLabel">Editar Días Laborables</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLaborablesForm">
                    <?php
                    $dias = [
                        'lunes' => 1,
                        'martes' => 2,
                        'miercoles' => 3,
                        'jueves' => 4,
                        'viernes' => 5,
                        'sabado' => 6,
                        'domingo' => 7
                    ];
                    foreach ($dias as $dia => $index): ?>
                        <div class="mb-3">
                            <label for="<?= $dia ?>" class="form-label"><?= ucfirst($dia) ?></label>
                            <select class="form-control" id="<?= $dia ?>" name="<?= $dia ?>">
                                <option value="0" <?= ($laborables[$dia] == 0) ? 'selected' : '' ?>>No Laborable</option>
                                <option value="<?= $index ?>" <?= ($laborables[$dia] == $index) ? 'selected' : '' ?>>Laborable
                                </option>
                            </select>
                        </div>
                    <?php endforeach; ?>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="boton btnGuardar" onclick="guardarLaborables()">Guardar Cambios
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
        const columnDefs = [
            {
                headerName: "Acciones",
                cellRenderer: () => `
                <button onclick="abrirModalEditarLaborables()" class="btn botonTabla btnEditarTabla">Editar
                   <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                    <path d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z" fill="white"/>
                    </svg></button>
            `,
                minWidth: 120
            },
            { headerName: "Lunes", field: "lunes", cellRenderer: estadoRenderer, minWidth: 120 },
            { headerName: "Martes", field: "martes", cellRenderer: estadoRenderer, minWidth: 120 },
            { headerName: "Miércoles", field: "miercoles", cellRenderer: estadoRenderer, minWidth: 120 },
            { headerName: "Jueves", field: "jueves", cellRenderer: estadoRenderer, minWidth: 120 },
            { headerName: "Viernes", field: "viernes", cellRenderer: estadoRenderer, minWidth: 120 },
            { headerName: "Sábado", field: "sabado", cellRenderer: estadoRenderer, minWidth: 120 },
            { headerName: "Domingo", field: "domingo", cellRenderer: estadoRenderer, minWidth: 120 },

        ];

        const rowData = [
            <?= json_encode($laborables) ?>
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: {
                flex: 1,
                sortable: false,
                resizable: true
            },
            rowData: rowData,
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay Festivos disponibles.'
            },
        };

        const gridDiv = document.querySelector('#laborablesGrid');
        new agGrid.Grid(gridDiv, gridOptions);
        function estadoRenderer(params) {
            const value = parseInt(params.value, 10);

            return value === 0 ? "No Laborable" : "Laborable";
        }
    });

    function abrirModalEditarLaborables() {
        $('#editLaborablesModal').modal('show');
    }

    function guardarLaborables() {
        const formData = $('#editLaborablesForm').serialize();
        $.post('<?= base_url("laborables/actualizarLaborables") ?>', formData)
            .done(response => {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Error al guardar los cambios.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

</script>

<?= $this->endSection() ?>