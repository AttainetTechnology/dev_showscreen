<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<h2>Gestión de Rutas</h2>

<div id="gridRutas" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const estado = <?= $estado ?>;

        const columnDefs = [
            { headerName: "Fecha", field: "fecha_ruta", sortable: true, filter: true },
            { headerName: "Estado", field: "estado_ruta", sortable: true, filter: true },
            { headerName: "Cliente", field: "id_cliente", sortable: true, filter: true },
            { headerName: "Población", field: "poblacion", sortable: true, filter: true },
            { headerName: "Lugar", field: "lugar", sortable: true, filter: true },
            { headerName: "Transportista", field: "transportista", sortable: true, filter: true },
            { headerName: "Pedido", field: "id_pedido", sortable: true, filter: true },
            {
                headerName: "Acciones",
                cellRenderer: params => `
                    <button onclick="editRuta(${params.data.id_ruta})">Editar</button>
                    <button onclick="deleteRuta(${params.data.id_ruta})">Eliminar</button>
                `
            }
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: {
                sortable: true,
                filter: true,
                floatingFilter: true,
                resizable: true,
            },
            rowData: [], // Inicializa con datos vacíos
            pagination: true,
        };

        const gridDiv = document.querySelector('#gridRutas');

        // Usa createGrid para inicializar ag-Grid
        const gridApi = agGrid.createGrid(gridDiv, gridOptions);

        // Fetch rutas with estado
        fetch('/rutas/getRutas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                coge_estado: estado.condicion,
                where_estado: estado.valor,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Usa applyTransaction para establecer los datos en la cuadrícula
                    gridApi.applyTransaction({ add: data.data });
                } else {
                    console.error('Error al cargar rutas:', data.message);
                }
            })
            .catch((error) => console.error('Error en la solicitud:', error));

        window.editRuta = function (id) {
            alert('Editar ruta ' + id); // Implementa lógica para editar.
        };

        window.deleteRuta = function (id) {
            if (confirm('¿Estás seguro de eliminar esta ruta?')) {
                fetch(`/rutas/deleteRuta/${id}`, { method: 'DELETE' })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert('Ruta eliminada');
                            // Usa applyTransaction para eliminar la fila
                            gridApi.applyTransaction({
                                remove: [gridApi.getRowNode(id).data],
                            });
                        } else {
                            alert('Error al eliminar');
                        }
                    });
            }
        };
    });
</script>


<?= $this->endSection() ?>