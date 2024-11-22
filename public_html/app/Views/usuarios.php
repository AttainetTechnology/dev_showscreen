<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<h2>Usuarios</h2>
<div id="gridUsuarios" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columnDefs = [
            {
                headerName: "Acciones",
                cellRenderer: params => `
                    <a href="/usuarios/editar/${params.data.id}" class="btn btn-warning btn-sm">Editar</a>
                    <button onclick="eliminarUsuario(${params.data.id})" class="btn btn-danger btn-sm">Eliminar</button>
                `
            },
            { headerName: "Nombre", field: "nombre_usuario", sortable: true, filter: true },
            { headerName: "Apellidos", field: "apellidos_usuario", sortable: true, filter: true },
            { headerName: "Email", field: "email", sortable: true, filter: true },
            { headerName: "Teléfono", field: "telefono", sortable: true, filter: true },

        ];

        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: { flex: 1, minWidth: 100, sortable: true, filter: true },
            rowData: [],
            pagination: true,
            onGridReady: params => fetchUsuarios(params.api)
        };

        new agGrid.Grid(document.querySelector('#gridUsuarios'), gridOptions);
    });

    function fetchUsuarios(gridApi) {
        fetch('/usuarios/getUsuarios')
            .then(response => response.json())
            .then(data => {
                gridApi.applyTransaction({ add: data }); // Agrega los datos
            })
            .catch(error => console.error('Error al cargar usuarios:', error));
    }

</script>

<?= $this->endSection() ?>