<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<br>
<h2>Proceso: <?= esc($nombre_proceso) ?></h2>

<div class="btnsEditPedido">
    <button id="clear-filters" class="boton btnEliminarfiltros">
        Quitar Filtros
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
            <path
                d="M7.54974 8.04975C7.62521 7.97408 7.71487 7.91405 7.81358 7.87309C7.91229 7.83213 8.01812 7.81105 8.12499 7.81105C8.23186 7.81105 8.33768 7.83213 8.43639 7.87309C8.5351 7.91405 8.62476 7.97408 8.70024 8.04975L13 12.3511L17.2997 8.04975C17.3753 7.97421 17.465 7.91428 17.5637 7.8734C17.6624 7.83251 17.7682 7.81147 17.875 7.81147C17.9818 7.81147 18.0876 7.83251 18.1863 7.8734C18.285 7.91428 18.3747 7.97421 18.4502 8.04975C18.5258 8.12529 18.5857 8.21497 18.6266 8.31368C18.6675 8.41238 18.6885 8.51816 18.6885 8.625C18.6885 8.73183 18.6675 8.83762 18.6266 8.93632C18.5857 9.03502 18.5258 9.12471 18.4502 9.20025L14.1489 13.5L18.4502 17.7997C18.5258 17.8753 18.5857 17.965 18.6266 18.0637C18.6675 18.1624 18.6885 18.2682 18.6885 18.375C18.6885 18.4818 18.6675 18.5876 18.6266 18.6863C18.5857 18.785 18.5258 18.8747 18.4502 18.9502C18.3747 19.0258 18.285 19.0857 18.1863 19.1266C18.0876 19.1675 17.9818 19.1885 17.875 19.1885C17.7682 19.1885 17.6624 19.1675 17.5637 19.1266C17.465 19.0857 17.3753 19.0258 17.2997 18.9502L13 14.6489L8.70024 18.9502C8.62469 19.0258 8.53501 19.0857 8.43631 19.1266C8.33761 19.1675 8.23182 19.1885 8.12499 19.1885C8.01815 19.1885 7.91237 19.1675 7.81366 19.1266C7.71496 19.0857 7.62528 19.0258 7.54974 18.9502C7.47419 18.8747 7.41427 18.785 7.37339 18.6863C7.3325 18.5876 7.31146 18.4818 7.31146 18.375C7.31146 18.2682 7.3325 18.1624 7.37339 18.0637C7.41427 17.965 7.47419 17.8753 7.54974 17.7997L11.8511 13.5L7.54974 9.20025C7.47407 9.12477 7.41404 9.03511 7.37308 8.9364C7.33212 8.83769 7.31104 8.73187 7.31104 8.625C7.31104 8.51813 7.33212 8.4123 7.37308 8.31359C7.41404 8.21488 7.47407 8.12522 7.54974 8.04975Z"
                fill="white" />
        </svg>
    </button>
</div>
<br>
<div id="myGrid" class="ag-theme-alpine" style="height: 500px; width: 100%;"></div>

<?php if (isset($relaciones) && !empty($relaciones)): ?>
    <script>
        const relacionesData = <?php echo json_encode($relaciones); ?>;

        const columnDefs = [

            { headerName: "Usuario", field: "nombre_usuario", filter: 'agTextColumnFilter' },
            { headerName: "Estado", field: "estado", filter: 'agTextColumnFilter' },
            { headerName: "Buenas", field: "buenas", filter: 'agTextColumnFilter' },
            { headerName: "Malas", field: "malas", filter: 'agTextColumnFilter' },
            { headerName: "Repasadas", field: "repasadas", filter: 'agTextColumnFilter' },
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            rowData: relacionesData,
            pagination: true,
            paginationPageSize: 10,
            defaultColDef: {
                flex: 1,
                minWidth: 100,
                sortable: true,
                floatingFilter: true,
                resizable: true
            },
            domLayout: 'autoHeight',
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            },
            onGridReady: function (params) {
                fetchEmpresasData(params.api);
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            const gridDiv = document.querySelector('#myGrid');
            new agGrid.Grid(gridDiv, gridOptions);
        });

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });

        function verMas(idLineaPedido) {
            // Puedes redirigir a otra vista o mostrar más detalles de la línea de pedido
            window.location.href = `/escandallo/verMas/${idLineaPedido}`;
        }
    </script>
<?php else: ?>
    <p>No se encontraron detalles para esta línea de pedidos.</p>
<?php endif; ?>

<?= $this->endSection() ?>