<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<br>
<h2 class="tituloProveedores"><?= $titulo_pagina ?></h2>

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
<div id="myGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columnDefs = [{
                headerName: 'Acciones',
                field: 'acciones',
                cellRenderer: renderActions,
                cellClass: 'acciones-col',
                maxWidth: 150,
                filter: false,
            },
            {
                headerName: "Linea Pedido",
                field: "id_lineapedido",
                filter: 'agTextColumnFilter',
                maxWidth: 120
            },
            {
                headerName: "ID",
                field: "id_lineapedido",
                filter: 'agTextColumnFilter',
                hide: true
            },
            {
                headerName: "Fecha de Entrada",
                field: "fecha_entrada",
                filter: 'agDateColumnFilter',
                valueFormatter: params => formatDate(params.value),
                comparator: dateComparator
            },
            {
                headerName: "Med Inicial",
                field: "med_inicial",
                filter: 'agTextColumnFilter',
                maxWidth: 150
            },
            {
                headerName: "Med Final",
                field: "med_final",
                filter: 'agTextColumnFilter',
                maxWidth: 150
            },
            {
                headerName: "Base",
                field: "nom_base",
                filter: 'agTextColumnFilter',
                maxWidth: 150
            },
            {
                headerName: "Producto",
                field: "nombre_producto",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Pedido",
                field: "pedido_completo",
                filter: 'agTextColumnFilter',
                cellRenderer: params => {
                    return `<a href="/pedidos/edit/${params.data.id_pedido}" style="text-decoration: none; color: #007bff;">
                    ${params.value}
                </a>`;
                }
            },
            {
                headerName: "Estado",
                field: "estado",
                filter: 'agTextColumnFilter'
            },
            {
                headerName: "Familia",
                field: "nombre_familia",
                filter: 'agTextColumnFilter'
            }
        ];

        function renderActions(params) {
            const id = params.data.id_lineapedido;
            const accionParte = params.data.accion_parte;

            return `
        <button class="btn boton btnImprimir" onclick="window.open('${accionParte}', '_blank')">
            Parte   <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
        <path d="M8.71593 4.72729C8.16741 4.72729 7.64136 4.95853 7.2535 5.37014C6.86564 5.78174 6.64774 6.34 6.64774 6.9221V9.11691H5.61365C5.06514 9.11691 4.53909 9.34814 4.15123 9.75975C3.76337 10.1714 3.54547 10.7296 3.54547 11.3117L3.54547 14.6039C3.54547 15.186 3.76337 15.7443 4.15123 16.1559C4.53909 16.5675 5.06514 16.7987 5.61365 16.7987H6.64774V17.8961C6.64774 18.4782 6.86564 19.0365 7.2535 19.4481C7.64136 19.8597 8.16741 20.0909 8.71593 20.0909H14.9205C15.469 20.0909 15.995 19.8597 16.3829 19.4481C16.7708 19.0365 16.9887 18.4782 16.9887 17.8961V16.7987H18.0227C18.5713 16.7987 19.0973 16.5675 19.4852 16.1559C19.873 15.7443 20.0909 15.186 20.0909 14.6039V11.3117C20.0909 10.7296 19.873 10.1714 19.4852 9.75975C19.0973 9.34814 18.5713 9.11691 18.0227 9.11691H16.9887V6.9221C16.9887 6.34 16.7708 5.78174 16.3829 5.37014C15.995 4.95853 15.469 4.72729 14.9205 4.72729H8.71593ZM7.68184 6.9221C7.68184 6.63105 7.79078 6.35192 7.98471 6.14612C8.17864 5.94032 8.44167 5.8247 8.71593 5.8247H14.9205C15.1947 5.8247 15.4578 5.94032 15.6517 6.14612C15.8456 6.35192 15.9546 6.63105 15.9546 6.9221V9.11691H7.68184V6.9221ZM8.71593 12.4091C8.16741 12.4091 7.64136 12.6404 7.2535 13.052C6.86564 13.4636 6.64774 14.0218 6.64774 14.6039V15.7013H5.61365C5.3394 15.7013 5.07637 15.5857 4.88244 15.3799C4.68851 15.1741 4.57956 14.895 4.57956 14.6039V11.3117C4.57956 11.0207 4.68851 10.7415 4.88244 10.5357C5.07637 10.3299 5.3394 10.2143 5.61365 10.2143H18.0227C18.297 10.2143 18.56 10.3299 18.754 10.5357C18.9479 10.7415 19.0568 11.0207 19.0568 11.3117V14.6039C19.0568 14.895 18.9479 15.1741 18.754 15.3799C18.56 15.5857 18.297 15.7013 18.0227 15.7013H16.9887V14.6039C16.9887 14.0218 16.7708 13.4636 16.3829 13.052C15.995 12.6404 15.469 12.4091 14.9205 12.4091H8.71593ZM15.9546 14.6039V17.8961C15.9546 18.1872 15.8456 18.4663 15.6517 18.6721C15.4578 18.8779 15.1947 18.9935 14.9205 18.9935H8.71593C8.44167 18.9935 8.17864 18.8779 7.98471 18.6721C7.79078 18.4663 7.68184 18.1872 7.68184 17.8961V14.6039C7.68184 14.3129 7.79078 14.0337 7.98471 13.8279C8.17864 13.6221 8.44167 13.5065 8.71593 13.5065H14.9205C15.1947 13.5065 15.4578 13.6221 15.6517 13.8279C15.8456 14.0337 15.9546 14.3129 15.9546 14.6039Z" fill="black" fill-opacity="0.6"/>
        </svg>
        </button>`;
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            const [year, month, day] = dateString.split('-');
            return `${day}/${month}/${year.slice(-2)}`;
        }

        function dateComparator(date1, date2) {
            const [day1, month1, year1] = date1.split('/').map(Number);
            const [day2, month2, year2] = date2.split('/').map(Number);
            const d1 = new Date(year1, month1 - 1, day1);
            const d2 = new Date(year2, month2 - 1, day2);
            return d1 - d2;
        }

        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: {
                flex: 1,
                minWidth: 100,
                sortable: true,
                floatingFilter: true,
                resizable: true
            },
            rowData: <?= json_encode($result) ?>,
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            },
            getRowClass: function(params) {
                const rowClass = params.data.estado_clase;
                return rowClass;
            }

        };

        const eGridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(eGridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', function() {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });
    });

    function mostrarParte(id_lineapedido) {
        $.ajax({
            url: '<?= base_url("partes/print/") ?>' + id_lineapedido,
            type: 'GET',
            success: function(data) {
                $('#modalParteContent').html(data);
                $('#parteModal').modal('show');
                sessionStorage.setItem('modalParteAbierto', 'true');
                sessionStorage.setItem('modalParteId', id_lineapedido);
            },
            error: function() {
                $('#modalParteContent').html('<p class="text-danger">Error al cargar el parte.</p>');
                $('#parteModal').modal('show');
                sessionStorage.setItem('modalParteAbierto', 'true');
                sessionStorage.setItem('modalParteId', id_lineapedido);
            }
        });
    }
</script>
<?= $this->endSection() ?>