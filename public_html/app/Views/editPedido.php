<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<div class="container mt-5">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery debe cargarse primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Luego carga Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <h2>Editar Pedido</h2>
    <!-- Botones de Acción -->
    <div class="mb-3">
        <label for="acciones" class="form-label"></label>
        <div class="d-flex gap-2">
            <a href="<?= base_url('pedidos/print/' . $pedido->id_pedido . '?volver=' . urlencode(current_url())) ?>" class="btn btn-info btn-sm" target="_blank">
                <i class="fa fa-print"></i> Imprimir Pedido
            </a>
            <a href="<?= base_url('pedidos/parte_complejo/' . $pedido->id_pedido . '?volver=' . urlencode(current_url())) ?>" class="btn btn-secondary btn-sm" target="_blank">
                <i class="fa fa-truck"></i> Parte Complejo
            </a>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="fa fa-truck fa-fw"></i> Rutas de transporte
            </button>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Rutas de transporte</h4>
                        </div>
                        <div class="modal-body">
                            <iframe src="<?= base_url('Ruta_pedido/rutas/' . $pedido->id_pedido . '/' . $pedido->id_cliente) ?>" frameborder="0" width="100%" height="400px"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <a href="<?= base_url('pedidos/entregar/' . $pedido->id_pedido) ?>" class="btn btn-success btn-sm">
                <i class="fa fa-check fa-fw"></i> Entregar Pedido
            </a>
            <a href="<?= base_url('pedidos/anular/' . $pedido->id_pedido) ?>" class="btn btn-danger btn-sm btn_anular">
                <i class="fa fa-trash fa-fw"></i> Anular Pedido
            </a>
        </div>
        <br>
    </div>
    <form action="<?= base_url('pedidos/update/' . $pedido->id_pedido) ?>" method="post">
        <!-- Empresa -->
        <div class="form-group">
            <label for="id_cliente">Empresa:</label>
            <select id="id_cliente" name="id_cliente" class="form-control" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>" <?= $pedido->id_cliente == $cliente['id_cliente'] ? 'selected' : '' ?>>
                        <?= $cliente['nombre_cliente'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>
        <!-- Referencia -->
        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control" value="<?= esc($pedido->referencia) ?>">
        </div>
        <br>
        <!-- Fechas -->
        <div class="form-group">
            <label for="fecha_entrada">Fecha de Entrada:</label>
            <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" value="<?= esc($pedido->fecha_entrada) ?>" required>
        </div>
        <br>
        <div class="form-group">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?= esc($pedido->fecha_entrega) ?>" required>
        </div>
        <br>
        <!-- Observaciones -->
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control" rows="3"><?= esc($pedido->observaciones) ?></textarea>
        </div>
        <br>
        <div class="button-container" style=" text-align: right;">
            <button type="submit" class="btn btn-primary btnGuardar">Guardar Pedido</button>
        </div>
        <br>
    </form>
    <!-- Líneas del Pedido -->
    <div class="form-group">
        <br>
        <h3 style="margin-left: 5px;">Líneas del Pedido</h3>
        <hr style="border: 5px solid #FFCC32; margin-top: 10px; margin-bottom: 20px;">
        <br>
        <div class="d-flex justify-content-between botoneseditPedido">
            <button type="button" class="btn btnAddLinea" id="openAddLineaPedidoModal" data-id-pedido="<?= $pedido->id_pedido ?>">
               + Añadir Línea de Pedido
            </button>
            <div>
                <button id="clear-filters" class="btn btnEliminarfiltros">
                    Eliminar Filtros
                </button>
                <button id="reload-page" class="btn btnrecarga ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Modal -->
        <!-- Modal para añadir una nueva línea de pedido -->
        <div class="modal fade" id="addLineaPedidoModal" tabindex="-1" aria-labelledby="addLineaPedidoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLineaPedidoLabel">Añadir Línea de Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBodyAddLineaPedido">
                        <!-- El contenido del formulario se cargará aquí mediante AJAX -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <br> <br>
        <?php
        // Array de estados
        $estados_texto = [
            "0" => "Pendiente de material",
            "1" => "Falta Material",
            "2" => "Material recibido",
            "3" => "En proceso",
            "4" => "Terminado",
            "5" => "Entregado",
            "6" => "Anulado"
        ];
        ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Acciones</th>
                    <th>ID Línea</th>
                    <th>Cantidad</th>
                    <th>Base</th>
                    <th>Producto</th>
                    <th>Estado</th>
                    <th>Medida Inicial</th>
                    <th>Medida Final</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <th></th> <!-- No se necesita filtro para las acciones -->
                    <th>
                        <div class="input-group">
                            <input type="text" id="filter-id" class="form-control">
                            <button class="btn btn-outline-secondary clear-filter" data-filter="filter-id">&times;</button>
                        </div>
                    </th>
                    <th>
                        <div class="input-group">
                            <input type="text" id="filter-cantidad" class="form-control">
                            <button class="btn btn-outline-secondary clear-filter" data-filter="filter-cantidad">&times;</button>
                        </div>
                    </th>
                    <th>
                        <div class="input-group">
                            <input type="text" id="filter-base" class="form-control">
                            <button class="btn btn-outline-secondary clear-filter" data-filter="filter-base">&times;</button>
                        </div>
                    </th>
                    <th>
                        <div class="input-group">
                            <select id="filter-producto" class="form-control">
                                <option value=""></option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= esc($producto['nombre_producto']) ?>"><?= esc($producto['nombre_producto']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-outline-secondary clear-filter" data-filter="filter-producto">&times;</button>
                        </div>
                    </th>
                    <th>
                        <div class="input-group">
                            <select id="filter-estado" class="form-control">
                                <option value=""></option>
                                <?php foreach ($estados_texto as $key => $estado): ?>
                                    <option value="<?= esc($estado) ?>"><?= esc($estado) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-outline-secondary clear-filter" data-filter="filter-estado">&times;</button>
                        </div>
                    </th>
                    <th>
                        <div class="input-group">
                            <input type="text" id="filter-medida-inicial" class="form-control">
                            <button class="btn btn-outline-secondary clear-filter" data-filter="filter-medida-inicial">&times;</button>
                        </div>
                    </th>
                    <th>
                        <div class="input-group">
                            <input type="text" id="filter-medida-final" class="form-control">
                            <button class="btn btn-outline-secondary clear-filter" data-filter="filter-medida-final">&times;</button>
                        </div>
                    </th>
                    <th>
                        <div class="input-group">
                            <input type="text" id="filter-total" class="form-control">
                            <button class="btn btn-outline-secondary clear-filter" data-filter="filter-total">&times;</button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody id="lineaPedidoTable">
                <?php if (!empty($lineas_pedido)): ?>
                    <?php foreach ($lineas_pedido as $linea): ?>
                        <tr>
                            <td style="width:18vh" > <!-- Columna de Acciones -->
                                <button type="button" class="btn btnEditar btn-sm" data-bs-toggle="modal" data-bs-target="#editarLineaModal<?= $linea['id_lineapedido'] ?>">
                                    Editar
                                </button>
                                <?= view('editLineaPedido', ['linea' => $linea]) ?>
                                <!-- Botón Parte -->
                                <a href="<?= base_url('pedidos/imprimir_parte/' . $linea['id_lineapedido']) ?>" class="btn btnImprimir btn-sm" target="_blank">
                                    Parte
                                </a>

                                <!-- Botón Eliminar -->
                                <a href="<?= base_url('pedidos/deleteLinea/' . $linea['id_lineapedido']) ?>" class="btn btnEliminar btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta línea?');">
                                    Eliminar
                                </a>
                            </td>
                            <td><?= esc($linea['id_lineapedido']) ?></td>
                            <td><?= esc($linea['n_piezas']) ?></td>
                            <td><?= esc($linea['nom_base']) ?></td>
                            <td><?= esc($linea['nombre_producto']) ?></td>
                            <td><?= isset($estados_texto[$linea['estado']]) ? esc($estados_texto[$linea['estado']]) : 'Estado desconocido' ?></td>
                            <td><?= esc($linea['med_inicial']) ?></td>
                            <td><?= esc($linea['med_final']) ?></td>
                            <td><?= esc($linea['total_linea']) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No se encontraron líneas de pedido.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
    <script>
        $(document).ready(function() {
            // Mapeo de los filtros
            const filters = {
                'filter-id': 1,
                'filter-cantidad': 2,
                'filter-base': 3,
                'filter-producto': 4,
                'filter-estado': 5,
                'filter-medida-inicial': 6,
                'filter-medida-final': 7,
                'filter-total': 8
            };

            // Función para aplicar los filtros a la tabla
            const applyFilters = () => {
                const rows = document.querySelectorAll('#lineaPedidoTable tr');
                rows.forEach(row => {
                    let isVisible = true;

                    // Iterar sobre cada filtro
                    Object.keys(filters).forEach(filterId => {
                        const columnIndex = filters[filterId];
                        const element = document.getElementById(filterId);
                        const filterValue = element.tagName === 'SELECT' ? element.value.toLowerCase() : element.value.toLowerCase();
                        const cellValue = row.cells[columnIndex]?.textContent.toLowerCase() || '';
                        // Si el filtro tiene valor y no coincide, ocultar la fila
                        if (filterValue && !cellValue.includes(filterValue)) {
                            isVisible = false;
                        }
                    });

                    // Mostrar u ocultar la fila según el resultado de los filtros
                    row.style.display = isVisible ? '' : 'none';
                });
            };

            // Aplicar filtros al cambiar los valores de los inputs o selects
            Object.keys(filters).forEach(filterId => {
                const element = document.getElementById(filterId);
                const eventType = element.tagName === 'SELECT' ? 'change' : 'input';

                element.addEventListener(eventType, applyFilters);
            });

            // Limpiar filtros individuales
            $('.clear-filter').on('click', function() {
                const filterId = $(this).data('filter');
                const element = document.getElementById(filterId);

                if (element.tagName === 'SELECT') {
                    element.selectedIndex = 0;
                } else {
                    element.value = '';
                }

                applyFilters();
            });

            // Limpiar todos los filtros
            $('#clear-filters').on('click', function() {
                Object.keys(filters).forEach(filterId => {
                    const element = document.getElementById(filterId);
                    if (element.tagName === 'SELECT') {
                        element.selectedIndex = 0;
                    } else {
                        element.value = '';
                    }
                });

                applyFilters();
            });
        });
        $(document).ready(function() {
            // Al hacer clic en el botón para abrir el modal "Añadir Línea de Pedido"
            $('#openAddLineaPedidoModal').click(function() {
                var idPedido = $(this).data('id-pedido');
                // Hacer la solicitud AJAX para cargar el contenido del modal
                $.ajax({
                    url: '<?= base_url("pedidos/mostrarFormularioAddLineaPedido") ?>/' + idPedido,
                    method: 'GET',
                    success: function(response) {
                        $('#modalBodyAddLineaPedido').html(response);
                        $('#addLineaPedidoModal').modal('show');
                    },
                    error: function() {
                        alert('Hubo un error al cargar el formulario. Por favor, intenta de nuevo.');
                    }
                });
            });
            // Gestión del envío del formulario
            $(document).on('submit', '#addLineaPedidoForm', function(e) {
                e.preventDefault(); 
                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // alert('Línea de pedido añadida correctamente.');
                        location.reload(); 
                    },
                    error: function() {
                        alert('No se pudo guardar la línea de pedido.');
                    }
                });
            });
        });
    </script>

    <?= $this->endSection() ?>