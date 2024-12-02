<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h2>Submenús del Menú: <?= $titulo; ?></h2>

    <a href="<?= base_url('menu/create') ?>" class="btn btn-success mb-3">Añadir Submenú</a>

    <!-- AG-Grid container para submenús -->
    <div id="submenuGrid" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>
</div>

<!-- Estilos y Scripts de AG-Grid -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columnDefs = [
            {
                headerName: "Acciones", cellRenderer: function (params) {
                    return ` 
                    <a href="<?= base_url('menu/edit') ?>/${params.data.id}" class="btn btn-warning btn-sm">Editar</a>
                    <button onclick="deleteMenu(${params.data.id})" class="btn btn-danger btn-sm">Eliminar</button>
                `;
                }
            },
            { headerName: "Posición", field: "posicion" },
            { headerName: "Título", field: "titulo" },
            { headerName: "Enlace", field: "enlace" },
            { headerName: "Nivel", field: "nivel" },
            { headerName: "Activo", field: "activo", valueFormatter: (params) => params.value === '1' ? 'Activo' : 'Desactivado' }
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            rowData: <?= json_encode($submenus); ?>, // Submenús obtenidos desde el controlador
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
        };

        const eGridDiv = document.querySelector('#submenuGrid');
        new agGrid.Grid(eGridDiv, gridOptions);
    });

    function deleteMenu(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este menú?')) {
            $.ajax({
                url: '<?= base_url('menu/delete') ?>/' + id,
                type: 'DELETE',
                success: function (response) {
                    if (response.success) {
                        alert('Menú eliminado con éxito.');
                        location.reload();
                    } else {
                        alert('Error al eliminar el menú.');
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