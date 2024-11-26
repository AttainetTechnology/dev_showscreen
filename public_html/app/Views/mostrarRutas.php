<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rutas</title>

    <!-- Incluir las dependencias de ag-Grid -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-grid.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-theme-alpine.css">

    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>
    
    <!-- Estilos personalizados -->
    <style>
        .ag-theme-alpine {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Rutas</h1>
        
        <!-- Contenedor de la tabla con ag-Grid -->
        <div id="myGrid" class="ag-theme-alpine"></div>
    </div>

    <script>
        // Datos de las rutas pasados desde el controlador
        const rutasData = <?php echo json_encode($rutas); ?>;

        // Definición de las columnas de la tabla
        const columnDefs = [
            { headerName: "Fecha", field: "fecha_ruta", sortable: true, filter: true },
            { 
                headerName: "Estado", 
                field: "estado_ruta", 
                sortable: true, 
                filter: true,
                cellRenderer: function(params) {
                    if (params.value == '2') {
                        return params.data.recogida_entrega == '1' ? "Recogido" : "Entregado";
                    } else if (params.value == '1') {
                        return "No preparado";
                    } else {
                        return "Pendiente";
                    }
                }
            },
            { 
                headerName: "Cliente", 
                field: "id_cliente", 
                sortable: true, 
                filter: true,
                cellRenderer: function(params) {
                    return "<a href='" + '<?php echo base_url(); ?>/Pedidos/edit/" + params.value + "'>Ver Cliente</a>";
                }
            },
            { headerName: "Población", field: "poblacion", sortable: true, filter: true },
            { headerName: "Lugar", field: "lugar", sortable: true, filter: true },
            { 
                headerName: "Recogida/Entrega", 
                field: "recogida_entrega", 
                sortable: true, 
                filter: true,
                cellRenderer: function(params) {
                    return params.value == '1' ? "Recogida" : "Entrega";
                }
            },
            { headerName: "Transportista", field: "transportista", sortable: true, filter: true },
            { 
                headerName: "Pedido", 
                field: "id_pedido", 
                sortable: true, 
                filter: true,
                cellRenderer: function(params) {
                    return "<a href='" + '<?php echo base_url(); ?>/Pedidos/edit/" + params.value + "'>Ver Pedido</a>";
                }
            }
        ];

        // Configuración de ag-Grid
        const gridOptions = {
            columnDefs: columnDefs,
            rowData: rutasData,
            pagination: true,
            paginationPageSize: 10,
            suppressPaginationPanel: false // Para permitir la paginación
        };

        // Inicializar ag-Grid
        document.addEventListener('DOMContentLoaded', function() {
            const gridDiv = document.querySelector('#myGrid');
            new agGrid.Grid(gridDiv, gridOptions);
        });
    </script>

</body>
</html>
