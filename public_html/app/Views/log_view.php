<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<!-- Cargar estilos para ag-Grid -->
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<br>
<h2 class="tituloEmpresas">Logs</h2>
<div class="buttonsEditProductProveedAbajo">
    <button id="clear-filters" class="boton btnEliminarfiltros">Quitar Filtros
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
            <path
                d="M7.54974 8.04975C7.62521 7.97408 7.71487 7.91405 7.81358 7.87309C7.91229 7.83213 8.01812 7.81105 8.12499 7.81105C8.23186 7.81105 8.33768 7.83213 8.43639 7.87309C8.5351 7.91405 8.62476 7.97408 8.70024 8.04975L13 12.3511L17.2997 8.04975C17.3753 7.97421 17.465 7.91428 17.5637 7.8734C17.6624 7.83251 17.7682 7.81147 17.875 7.81147C17.9818 7.81147 18.0876 7.83251 18.1863 7.8734C18.285 7.91428 18.3747 7.97421 18.4502 8.04975C18.5258 8.12529 18.5857 8.21497 18.6266 8.31368C18.6675 8.41238 18.6885 8.51816 18.6885 8.625C18.6885 8.73183 18.6675 8.83762 18.6266 8.93632C18.5857 9.03502 18.5258 9.12471 18.4502 9.20025L14.1489 13.5L18.4502 17.7997C18.5258 17.8753 18.5857 17.965 18.6266 18.0637C18.6675 18.1624 18.6885 18.2682 18.6885 18.375C18.6885 18.4818 18.6675 18.5876 18.6266 18.6863C18.5857 18.785 18.5258 18.8747 18.4502 18.9502C18.3747 19.0258 18.285 19.0857 18.1863 19.1266C18.0876 19.1675 17.9818 19.1885 17.875 19.1885C17.7682 19.1885 17.6624 19.1675 17.5637 19.1266C17.465 19.0857 17.3753 19.0258 17.2997 18.9502L13 14.6489L8.70024 18.9502C8.62469 19.0258 8.53501 19.0857 8.43631 19.1266C8.33761 19.1675 8.23182 19.1885 8.12499 19.1885C8.01815 19.1885 7.91237 19.1675 7.81366 19.1266C7.71496 19.0857 7.62528 19.0258 7.54974 18.9502C7.47419 18.8747 7.41427 18.785 7.37339 18.6863C7.3325 18.5876 7.31146 18.4818 7.31146 18.375C7.31146 18.2682 7.3325 18.1624 7.37339 18.0637C7.41427 17.965 7.47419 17.8753 7.54974 17.7997L11.8511 13.5L7.54974 9.20025C7.47407 9.12477 7.41404 9.03511 7.37308 8.9364C7.33212 8.83769 7.31104 8.73187 7.31104 8.625C7.31104 8.51813 7.33212 8.4123 7.37308 8.31359C7.41404 8.21488 7.47407 8.12522 7.54974 8.04975Z"
                fill="white" />
        </svg>
    </button>
</div>
<br>
<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columnDefs = [
            {
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: params => {
                    const logId = params.data.id_log;  // Usa el nombre correcto 'id_log'
                    if (logId === undefined || logId === null) {
                        console.error("ID de log no encontrado:", params.data);
                    }
                    return `
                <button onclick="eliminarLog(${logId})" class="btn botonTabla btnEliminarTabla" title="Eliminar">
                    Eliminar 
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                    <path d="M7.66752 7.27601C7.41731 7.27601 7.17736 7.37593 7.00044 7.5538C6.82351 7.73166 6.72412 7.9729 6.72412 8.22444V9.17287C6.72412 9.4244 6.82351 9.66564 7.00044 9.84351C7.17736 10.0214 7.41731 10.1213 7.66752 10.1213H8.13922V18.6571C8.13922 19.1602 8.338 19.6427 8.69184 19.9984C9.04569 20.3542 9.5256 20.554 10.026 20.554H15.6864C16.1868 20.554 16.6667 20.3542 17.0205 19.9984C17.3744 19.6427 17.5732 19.1602 17.5732 18.6571V10.1213H18.0449C18.2951 10.1213 18.535 10.0214 18.712 9.84351C18.8889 9.66564 18.9883 9.4244 18.9883 9.17287V8.22444C18.9883 7.9729 18.8889 7.73166 18.712 7.5538C18.535 7.37593 18.2951 7.27601 18.0449 7.27601H14.743C14.743 7.02447 14.6436 6.78324 14.4667 6.60537C14.2898 6.42751 14.0498 6.32758 13.7996 6.32758H11.9128C11.6626 6.32758 11.4226 6.42751 11.2457 6.60537C11.0688 6.78324 10.9694 7.02447 10.9694 7.27601H7.66752ZM10.4977 11.0697C10.6228 11.0697 10.7428 11.1197 10.8312 11.2086C10.9197 11.2975 10.9694 11.4182 10.9694 11.5439V18.1829C10.9694 18.3087 10.9197 18.4293 10.8312 18.5182C10.7428 18.6072 10.6228 18.6571 10.4977 18.6571C10.3726 18.6571 10.2526 18.6072 10.1642 18.5182C10.0757 18.4293 10.026 18.3087 10.026 18.1829V11.5439C10.026 11.4182 10.0757 11.2975 10.1642 11.2086C10.2526 11.1197 10.3726 11.0697 10.4977 11.0697ZM12.8562 11.0697C12.9813 11.0697 13.1013 11.1197 13.1897 11.2086C13.2782 11.2975 13.3279 11.4182 13.3279 11.5439V18.1829C13.3279 18.3087 13.2782 18.4293 13.1897 18.5182C13.1013 18.6072 12.9813 18.6571 12.8562 18.6571C12.7311 18.6571 12.6111 18.6072 12.5227 18.5182C12.4342 18.4293 12.3845 18.3087 12.3845 18.1829V11.5439C12.3845 11.4182 12.4342 11.2975 12.5227 11.2086C12.6111 11.1197 12.7311 11.0697 12.8562 11.0697ZM15.6864 11.5439V18.1829C15.6864 18.3087 15.6367 18.4293 15.5482 18.5182C15.4598 18.6072 15.3398 18.6571 15.2147 18.6571C15.0896 18.6571 14.9696 18.6072 14.8811 18.5182C14.7927 18.4293 14.743 18.3087 14.743 18.1829V11.5439C14.743 11.4182 14.7927 11.2975 14.8811 11.2086C14.9696 11.1197 15.0896 11.0697 15.2147 11.0697C15.3398 11.0697 15.4598 11.1197 15.5482 11.2086C15.6367 11.2975 15.6864 11.4182 15.6864 11.5439Z" fill="white"/>
                    </svg>
                    </svg>
                </button>
            `;
                },
                filter: false,
                maxWidth: 120
            },
            {
                headerName: "Fecha",
                field: "fecha",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Usuario",
                field: "id_usuario",
                filter: 'agTextColumnFilter',
                minWidth: 220
            },
            {
                headerName: "Log",
                field: "log",
                filter: 'agTextColumnFilter',
                minWidth: 220
            },
            {
                headerName: "Seccion",
                field: "seccion",
                filter: 'agTextColumnFilter'
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
            onGridReady: function (params) {
                const gridApi = params.api;
                const gridColumnApi = params.columnApi;
                fetchLogsData(gridApi);
                window.addEventListener('resize', function () {
                    gridApi.sizeColumnsToFit();
                });
                gridApi.sizeColumnsToFit();
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
    function fetchLogsData(gridApi) {
        fetch('<?= base_url("log/getLogs") ?>')
            .then(response => response.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (Array.isArray(data)) {
                        gridApi.setRowData(data);
                    }
                } catch (e) {
                    console.error('Error al parsear JSON:', e);
                }
            })
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    function eliminarLog(logId) {
        if (confirm("¿Estás seguro de eliminar este log?")) {
            $.ajax({
                url: '<?= base_url("log/deleteLog") ?>/' + logId,
                type: 'DELETE',
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'No se pudo eliminar el log.'));
                    }
                },
                error: function () {
                    alert('Error en la solicitud. Por favor, inténtelo de nuevo.');
                }
            });
        }
    }

</script>

<?= $this->endSection() ?>