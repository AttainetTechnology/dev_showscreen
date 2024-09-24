<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2>Pedidos</h2>

<div class="d-flex justify-content-between mb-3">
    <a href="#" id="openModal" class="btn btn-primary">Añadir Pedido</a>

    <div class="modal fade" id="addPedidoModal" tabindex="-1" role="dialog" aria-labelledby="addPedidoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPedidoModalLabel">Añadir Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- cargara el contenido desde la vista add_pedido.php -->
                </div>
            </div>
        </div>
    </div>
    <button id="clear-filters" class="btn btn-secondary">Eliminar Filtros</button>
</div>
<br>
<?php
// Mapeo de estados
$estadoMap = [
    "0" => "Pendiente de material",
    "2" => "Material recibido",
    "3" => "En Máquinas",
    "4" => "Terminado",
    "5" => "Entregado",
    "1" => "Falta Material",
    "6" => "Anulado"
];
?>
<table class="table table-striped table-pedidos">
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Fecha Entrada</th>
            <th>Fecha Entrega</th>
            <th>Cliente</th>
            <th>Referencia</th>
            <th>Estado</th>
            <th>Usuario</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
        <tr>
            <th>
                <div class="input-group">
                    <input type="text" id="filter-id" class="form-control" placeholder="">
                    <button class="btn btn-outline-secondary clear-filter" data-filter="filter-id">&times;</button>
                </div>
            </th>
            <th>
                <div class="input-group">
                    <input type="text" id="filter-fecha-entrada" class="form-control datepicker" placeholder="">
                    <button class="btn btn-outline-secondary clear-filter" data-filter="filter-fecha-entrada">&times;</button>
                </div>
            </th>
            <th>
                <div class="input-group">
                    <input type="text" id="filter-fecha-entrega" class="form-control datepicker" placeholder="">
                    <button class="btn btn-outline-secondary clear-filter" data-filter="filter-fecha-entrega">&times;</button>
                </div>
            </th>
            <th>
                <div class="input-group">
                    <input list="clientes" id="filter-cliente" class="form-control" placeholder="" oninput="this.value = this.value.toUpperCase()">
                    <datalist id="clientes">
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= strtoupper($cliente['nombre_cliente']) ?>"><?= strtoupper($cliente['nombre_cliente']) ?></option>
                        <?php endforeach; ?>
                    </datalist>
                    <button class="btn btn-outline-secondary clear-filter" data-filter="filter-cliente">&times;</button>
                </div>
            </th>
            <th>
                <div class="input-group">
                    <input type="text" id="filter-referencia" class="form-control" placeholder="">
                    <button class="btn btn-outline-secondary clear-filter" data-filter="filter-referencia">&times;</button>
                </div>
            </th>
            <th>
                <div class="input-group">
                    <select id="filter-estado" class="form-control">
                        <option value=""></option>
                        <option value="0">Pendiente de material</option>
                        <option value="1">Falta Material</option>
                        <option value="2">Material recibido</option>
                        <option value="3">En Máquinas</option>
                        <option value="4">Terminado</option>
                        <option value="5">Entregado</option>
                        <option value="6">Anulado</option>
                    </select>
                    <button class="btn btn-outline-secondary clear-filter" data-filter="filter-estado">&times;</button>
                </div>
            </th>
            <th>
                <div class="input-group">
                    <input list="usuarios" id="filter-usuario" class="form-control" placeholder="" oninput="this.value = this.value.toUpperCase()">
                    <datalist id="usuarios">
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= strtoupper($usuario['nombre_usuario']) ?>"><?= strtoupper($usuario['nombre_usuario']) ?></option>
                        <?php endforeach; ?>
                    </datalist>
                    <button class="btn btn-outline-secondary clear-filter" data-filter="filter-usuario">&times;</button>
                </div>
            </th>
            <th>
                <div class="input-group">
                    <input type="text" id="filter-total" class="form-control" placeholder="">
                    <button class="btn btn-outline-secondary clear-filter" data-filter="filter-total">&times;</button>
                </div>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody id="pedidoTable">
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido->id_pedido ?></td>
                <td><?= date('d-m-Y', strtotime($pedido->fecha_entrada)) ?></td>
                <td><?= date('d-m-Y', strtotime($pedido->fecha_entrega)) ?></td>
                <td><?= $pedido->nombre_cliente ?></td>
                <td><?= $pedido->referencia ?></td>
                <td><?= $estadoMap[$pedido->estado] ?></td>
                <td><?= $pedido->nombre_usuario ?></td>
                <td><?= $pedido->total_pedido ?>€</td>
                <td>
                    <a href="<?= base_url('pedidos/print/' . $pedido->id_pedido) ?>" class="btn btn-info" target="_blank">Imprimir</a>
                    <a href="<?= base_url('pedidos/edit/' . $pedido->id_pedido) ?>" class="btn btn-warning">Editar</a>
                    <?php if ($allow_delete): ?>
                        <a href="<?= base_url('pedidos/delete/' . $pedido->id_pedido) ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">Eliminar</a>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        // Función para abrir el modal y cargar el contenido vía AJAX
        function abrirModal() {
            $.ajax({
                url: '<?= base_url('pedidos/add') ?>',
                method: 'GET',
                success: function(response) {
                    $('#addPedidoModal .modal-body').html(response);
                    $('#addPedidoModal').modal('show');
                },
                error: function() {
                    alert('Hubo un error al cargar el contenido del modal.');
                }
            });
        }
        // Detectar el parámetro 'modal=add' en la URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('modal') === 'add') {
            abrirModal();
        }
        $('#openModal').click(function(e) {
            e.preventDefault();
            abrirModal();
        });
    });
    $(document).ready(function() {
        const estadoMap = {
            "0": "pendiente de material",
            "1": "falta material",
            "2": "material recibido",
            "3": "en máquinas",
            "4": "terminado",
            "5": "entregado",
            "6": "anulado"
        };
        // Inicializar Datepicker en los campos de fecha con formato día/mes/año
        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy',
            onSelect: function() {
                applyFilters();
            }
        });
        const filters = {
            'filter-id': 0,
            'filter-fecha-entrada': 1,
            'filter-fecha-entrega': 2,
            'filter-cliente': 3,
            'filter-referencia': 4,
            'filter-estado': 5,
            'filter-usuario': 6,
            'filter-total': 7
        };
        // Función para aplicar todos los filtros a la vez
        const applyFilters = () => {
            const rows = document.querySelectorAll('#pedidoTable tr');

            rows.forEach(row => {
                let isVisible = true;

                Object.keys(filters).forEach(filterId => {
                    const columnIndex = filters[filterId];
                    const element = document.getElementById(filterId);
                    let filterValue = element.tagName === 'SELECT' ? element.value : element.value.toLowerCase();
                    let cellValue = row.cells[columnIndex].textContent.toLowerCase();
                    // Si es el filtro de estado, convertir el valor del select en su texto correspondiente
                    if (filterId === 'filter-estado' && filterValue) {
                        filterValue = estadoMap[filterValue];
                        cellValue = cellValue.toLowerCase();
                    }

                    if (filterValue && !cellValue.includes(filterValue)) {
                        isVisible = false;
                    }
                });
                row.style.display = isVisible ? '' : 'none';
            });
        };
        // Asignar eventos a todos los filtros para que apliquen el filtro general
        Object.keys(filters).forEach(filterId => {
            const element = document.getElementById(filterId);
            const eventType = element.tagName === 'INPUT' ? 'input' : 'change';

            element.addEventListener(eventType, applyFilters);
        });
        // Filtrar por estado usando el valor del select y convertir el valor numérico en texto
        $('#filter-estado').on('change', function() {
            applyFilters();
        });
        // Función para limpiar todos los filtros
        const clearFilters = () => {
            Object.keys(filters).forEach(filterId => {
                const element = document.getElementById(filterId);
                if (element.tagName === 'SELECT') {
                    element.selectedIndex = 0;
                } else {
                    element.value = '';
                }
            });
            applyFilters();
        };
        // Botón para eliminar todos los filtros
        $('#clear-filters').on('click', clearFilters);
        // Función para eliminar un filtro específico
        $('.clear-filter').on('click', function() {
            const filterId = $(this).data('filter');
            const element = document.getElementById(filterId);

            if (element.tagName === 'SELECT') {
                element.selectedIndex = 0;
            } else {
                element.value = '';
            }
            applyFilters(); // Reaplicar los filtros después de eliminar uno específico
        });
    });
</script>
<?= $this->endSection() ?>