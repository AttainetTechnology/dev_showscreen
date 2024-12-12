<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<!-- Estilos y Scripts -->

<br>
<h2 class="tituloEmpresas">Empresas</h2>
<br>
<div class="d-flex justify-content-between mb-3 btnFamiliaProveedor">
    <button onclick="abrirModalAgregar()" class="boton btnAdd">Añadir Empresa</button>
    <button id="clear-filters" class="boton btnEliminarfiltros">Quitar Filtros</button>
</div>
<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<!-- Modal para agregar empresa -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="addModalContent"></div>
    </div>
</div>

<!-- Modal para editar empresa -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="editModalContent"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Iniciando Ag-Grid...');

        const columnDefs = [
            {
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: params => `
                    <button onclick="editarEmpresa('${params.data.editar}')" class="btn botonTabla btnEditarTabla">Editar</button>
                    <button onclick="eliminarEmpresa('${params.data.eliminar}')" class="btn botonTabla btnEliminarTabla">Eliminar</button>
                `,
                filter: false,
                minWidth: 180
            },
            { headerName: "Nombre Cliente", field: "nombre_cliente", filter: 'agTextColumnFilter', minWidth: 220 },
            { headerName: "NIF", field: "nif", filter: 'agTextColumnFilter' },
            { headerName: "Dirección", field: "direccion", filter: 'agTextColumnFilter' },
            { headerName: "Provincia", field: "id_provincia", filter: 'agTextColumnFilter' },
            { headerName: "Población", field: "poblacion", filter: 'agTextColumnFilter' },
            { headerName: "Teléfono", field: "telf", filter: 'agTextColumnFilter' },
            { headerName: "Forma de Pago", field: "f_pago", filter: 'agTextColumnFilter' },
            { headerName: "Email", field: "email", filter: 'agTextColumnFilter' },
            { headerName: "Observaciones", field: "observaciones_cliente", filter: 'agTextColumnFilter' },
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
                noRowsToShow: 'No hay registros disponibles.'
            },
            onGridReady: function (params) {
                fetchEmpresasData(params.api);
            }
        };

        const gridDiv = document.querySelector('#myGrid');
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

    function abrirModalAgregar() {
        $('#addModalContent').load('<?= base_url("empresas/addForm") ?>', function () {
            $.ajax({
                url: '<?= base_url("empresas/getProvincias") ?>',
                type: 'GET',
                success: function (provincias) {
                    const provinciaSelect = $('#id_provincia');
                    provinciaSelect.empty().append('<option value="">Seleccione una provincia</option>');
                    $.each(provincias, function (i, provincia) {
                        provinciaSelect.append(`<option value="${provincia.id_provincia}">${provincia.provincia}</option>`);
                    });
                    $('#addModal').modal('show');
                },
                error: function () {
                    alert('Error al cargar las provincias.');
                }
            });
        });
    }

    function eliminarEmpresa(url) {
        if (confirm("¿Estás seguro de eliminar esta empresa?")) {
            $.ajax({
                url: url,
                type: 'POST',
                success: function (response) {
                    if (response.success) {
                        alert("Empresa eliminada con éxito.");
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'No se pudo eliminar la empresa.'));
                    }
                },
                error: function () {
                    alert('Error en la solicitud. Por favor, inténtelo de nuevo.');
                }
            });
        }
    }

    function fetchEmpresasData(gridApi) {
        console.log('Cargando datos de empresas...');
        fetch('<?= base_url("empresas/getEmpresas") ?>')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    console.log('Datos cargados:', data);
                    gridApi.applyTransaction({ add: data });
                } else {
                    console.error('Los datos recibidos no son un array:', data);
                }
            })
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    function editarEmpresa(url) {
        window.location.href = url;
    }
</script>
<?= $this->endSection() ?>
