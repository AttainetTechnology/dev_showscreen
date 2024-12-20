<!-- Modal para mostrar las rutas de transporte -->
<form id="rutasForm" method="post">
    <!-- Encabezado del modal -->
    <div class="modal-header">
        <h4 class="modal-title">Rutas de Transporte</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <!-- Botón para eliminar los filtros -->
    <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilters()">Eliminar Filtros</button>
    </div>

    <!-- Cuerpo del modal con la tabla -->
    <div class="modal-body">
        <!-- Comprobación de las rutas -->
        <?php if (!empty($rutas)) : ?>
            <div class="table-responsive">
                <table id="rutasTable" class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Población<br><input type="text" class="form-control form-control-sm" placeholder="Buscar..." onkeyup="filterTable()"></th>
                            <th>Lugar<br><input type="text" class="form-control form-control-sm" placeholder="Buscar..." onkeyup="filterTable()"></th>
                            <th>Recogida/Entrega<br><input type="text" class="form-control form-control-sm" placeholder="Buscar..." onkeyup="filterTable()"></th>
                            <th>Observaciones<br><input type="text" class="form-control form-control-sm" placeholder="Buscar..." onkeyup="filterTable()"></th>
                            <th>Fecha<br><input type="text" class="form-control form-control-sm" placeholder="Buscar..." onkeyup="filterTable()"></th>
                            <th>Estado<br><input type="text" class="form-control form-control-sm" placeholder="Buscar..." onkeyup="filterTable()"></th>
                            <th>Acciones</th> <!-- Columna de acciones añadida -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rutas as $ruta) : ?>
                            <tr>
                                <td><?= esc($ruta->poblacion) ?></td>
                                <td><?= esc($ruta->lugar) ?></td>
                                <td><?= esc($ruta->recogida_entrega == 1 ? 'Recogida' : 'Entrega') ?></td>
                                <td><?= esc($ruta->observaciones) ?></td>
                                <td><?= esc(date('d/m/Y', strtotime($ruta->fecha_ruta))) ?></td>
                                <td>
                                    <span class="badge <?= $ruta->estado_ruta == 1 ? 'bg-warning' : ($ruta->estado_ruta == 2 ? 'bg-success' : 'bg-secondary') ?>">
                                        <?= esc($ruta->estado_ruta == 1 ? 'No preparado' : ($ruta->estado_ruta == 2 ? 'Recogido' : 'Pendiente')) ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Botón para editar la ruta -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRutaModal<?= esc($ruta->id_ruta) ?>">
                                        Editar
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal para editar la ruta -->
                            <div class="modal fade" id="editRutaModal<?= esc($ruta->id_ruta) ?>" tabindex="-1" aria-labelledby="editRutaLabel<?= esc($ruta->id_ruta) ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editRutaLabel<?= esc($ruta->id_ruta) ?>">Editar Ruta</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?= base_url('rutas/update/' . esc($ruta->id_ruta)) ?>" method="post">
                                                <div class="form-group">
                                                    <label for="poblacion">Población:</label>
                                                    <input type="text" name="poblacion" class="form-control" value="<?= esc($ruta->poblacion) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="lugar">Lugar:</label>
                                                    <input type="text" name="lugar" class="form-control" value="<?= esc($ruta->lugar) ?>" required>
                                                </div>
                                                <!-- Más campos para editar según la estructura de datos -->
                                                <button type="submit" class="btn btn-primary mt-2">Guardar Cambios</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="alert alert-warning" role="alert">
                No se encontraron rutas para este pedido.
            </div>
        <?php endif; ?>
    </div>
</form>

<!-- Modal para añadir nueva ruta -->
<div class="modal fade" id="addRutaModal" tabindex="-1" aria-labelledby="addRutaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRutaLabel">Añadir Nueva Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('rutas/create') ?>" method="post">
                    <div class="form-group">
                        <label for="poblacion">Población:</label>
                        <input type="text" name="poblacion" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="lugar">Lugar:</label>
                        <input type="text" name="lugar" class="form-control" required>
                    </div>
                    <!-- Más campos para la nueva ruta según la estructura de datos -->
                    <button type="submit" class="btn btn-primary mt-2">Añadir Ruta</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para filtrar la tabla considerando todas las columnas con filtros activos
    function filterTable() {
        var table, tr, i, j, visible;
        table = document.getElementById("rutasTable");
        tr = table.getElementsByTagName("tr");

        // Recorre todas las filas de la tabla, excepto la primera que es el encabezado
        for (i = 1; i < tr.length; i++) {
            visible = true;  // Inicializamos como visible
            for (j = 0; j < 7; j++) {  // Se ha añadido una columna adicional, así que ahora hay 7 columnas
                var input = document.querySelectorAll('thead input')[j];
                var td = tr[i].getElementsByTagName("td")[j];
                if (td && input) {
                    var filter = input.value.toUpperCase();
                    var txtValue = td.textContent || td.innerText;
                    if (filter !== "" && txtValue.toUpperCase().indexOf(filter) === -1) {
                        visible = false;  // Si no coincide, marcar fila como no visible
                        break;  // No es necesario comprobar las demás columnas si una ya falla
                    }
                }
            }
            tr[i].style.display = visible ? "" : "none";  // Mostrar o esconder la fila
        }
    }

    // Función para eliminar todos los filtros
    function clearFilters() {
        var inputs = document.querySelectorAll('thead input');
        inputs.forEach(input => input.value = '');  // Limpiar todos los campos de búsqueda
        filterTable();  // Volver a aplicar el filtro (mostrará todas las filas)
    }
</script>
