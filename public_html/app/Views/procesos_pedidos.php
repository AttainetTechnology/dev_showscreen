<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORGANIZADOR</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/organizador.css') ?>?v=<?= time() ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <!-- Cargamos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<div class="wrapper">
    <div id="organizador">
        <div class="column" id="col2">
            <div class="cabecera">
                <h4>Procesos listos para producir</h4>
                <br>
                <div style="display: inline-block; vertical-align: middle;">
                    <select id="searchInput" class="form-control d-inline-block" style="width: auto;">
                        <option value="">Seleccione un proceso...</option>
                        <?php if (isset($procesos)) : ?>
                            <?php
                            usort($procesos, function ($a, $b) {
                                return strcmp($a['nombre_proceso'], $b['nombre_proceso']);
                            });
                            ?>
                            <?php foreach ($procesos as $proceso) : ?>
                                <option value="<?= esc($proceso['nombre_proceso']) ?>"><?= esc($proceso['nombre_proceso']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div style="display: inline-block; vertical-align: middle;">
                    <button id="clearFilters" class="btn btn-sm btn-light ms-2">
                        <i class="bi bi-x-circle"></i> Eliminar Filtros
                    </button>
                </div>
            </div>
            <div class="resultados">
                <table id="Tabla2" class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllCol2" class="selectAll"></th>
                            <th>
                                id
                                <br>
                                <input type="text" id="idSearchInputCol2" class="form-control d-inline-block" style="width: 70%; font-size: 1em; border: 1px solid #989A9C;" placeholder="ID" onkeyup="filtrarPorIdCol2();">
                            </th>
                            <th>
                                Cliente
                                <select id="clienteFilter" style="width: 100%;" onchange="filtrarPorCliente(this.value);">
                                    <option value="">Todos</option>
                                    <?php if (isset($clientes)) : ?>>
                                    <?php
                                        usort($clientes, function ($a, $b) {
                                            return strcmp($a['nombre_cliente'], $b['nombre_cliente']);
                                        });
                                    ?>
                                    <?php foreach ($clientes as $cliente) : ?>
                                        <option value="<?= esc($cliente['nombre_cliente']) ?>"><?= esc($cliente['nombre_cliente']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </select>
                            </th>
                            <th>
                                Medidas
                                <select id="medidasFilter" style="width: 100%;" onchange="filtrarPorMedida(this.value);">
                                    <option value="">Sin Orden</option>
                                    <option value="iniciales">Medidas Iniciales</option>
                                    <option value="finales">Medidas Finales</option>
                                </select>
                            </th>
                            <th>Fecha Entrega</th>
                            <th>
                                Producto
                                <select id="productoFilterCol2" style="width: 100%;" onchange="filtrarPorProducto(this.value, 2);">
                                    <option value="">Todos</option>
                                    <?php if (isset($productos)) : ?>
                                        <?php
                                        usort($productos, function ($a, $b) {
                                            $aNombre = preg_replace('/[^\p{L}\p{N}\s]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT', $a['nombre_producto']));
                                            $bNombre = preg_replace('/[^\p{L}\p{N}\s]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT', $b['nombre_producto']));

                                            if (is_numeric($aNombre[0]) && !is_numeric($bNombre[0])) {
                                                return 1;
                                            } elseif (!is_numeric($aNombre[0]) && is_numeric($bNombre[0])) {
                                                return -1;
                                            } else {
                                                return strcmp($aNombre, $bNombre);
                                            }
                                        });
                                        ?>
                                        <?php foreach ($productos as $producto) : ?>
                                            <option value="<?= esc($producto['nombre_producto']) ?>"><?= esc($producto['nombre_producto']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </th>
                            <th>Nº Piezas</th>
                            <th>Proceso</th>
                            <th>Base</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lineas as $linea) : ?>
                            <?php
                            // Verificar si la clave 'restriccion' está definida antes de intentar acceder a ella
                            $restriccion = isset($linea['restriccion']) ? $linea['restriccion'] : null;
                            ?>
                            <tr class="linea" data-nombre-cliente="<?= esc($linea['cliente']); ?>" data-nombre-proceso="<?= esc($linea['proceso']); ?>" data-nombre-producto="<?= esc($linea['producto']); ?>" data-med-inicial="<?= isset($linea['med_inicial']) ? esc($linea['med_inicial']) : '0'; ?>" data-med-final="<?= isset($linea['med_final']) ? esc($linea['med_final']) : '0'; ?>">
                                <td><input type="checkbox" class="checkboxCol2" name="selectedLineCol2[]"></td>
                                <td><?= esc($linea['id_linea_pedido']); ?></td>
                                <td><?= esc($linea['cliente']); ?></td>
                                <td><?= esc($linea['medidas']); ?></td>
                                <td><?= esc($linea['fecha']); ?></td>
                                <td><?= esc($linea['producto']); ?></td>
                                <td><?= esc($linea['n_piezas']); ?></td>
                                <td>
                                    <?= esc($linea['proceso']); ?>
                                    <?php if ($restriccion !== null && $restriccion !== '0' && $restriccion !== '') : ?>
                                        <span style="margin-left: 5px;">🔒</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($linea['base']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
        <div class="column" id="col3">
            <button data-action="move-left" class="btn btn-md btn-primary"><i class="bi bi-arrow-left"></i></button><br>
            <button data-action="move-right" class="btn btn-md btn-primary"><i class="bi bi-arrow-right"></i></button><br>
            <button data-action="confirm" class="btn btn-md btn-info"><i class="bi bi-floppy"></i></button><br>
            <button data-action="btn-terminado" class="btn btn-md" style="background-color: #50b752; color: white;"><i class="bi bi-clipboard2-check"></i></button><br>
            <button data-action="btn-imprimir" onclick="printDiv('printableArea')" class="btn btn-secondary btn-md"><i class='bi bi-printer'></i></button><br>
            <button data-action="cancelar" onclick="window.location.reload();" class="btn btn-md btn-warning"><i class="bi bi-arrow-clockwise"></i></button><br>
            <button data-action="pedido" class="btn btn-md btn-success"><i class="bi bi-box"></i></button><br>
            <?php echo view('procesosTerminados'); ?>

        </div>
        <div class="column" id="col4">
            <div class="cabecera">
                <h4 id="tituloProcesosEnMaquina">Procesos en máquina</h4>
                <br>
                <div style="display: inline-block; vertical-align: middle;">
                    <select id="maquinaFilterCol4" class="form-control d-inline-block" onchange="filtrarProcesosPorMaquina(this.value);">
                        <option value="">Todas las máquinas</option>
                        <?php if (isset($maquinas)) : ?>
                            <?php
                            usort($maquinas, function ($a, $b) {
                                return strcmp($a['nombre'], $b['nombre']);
                            });
                            ?>
                            <?php foreach ($maquinas as $maquina) : ?>
                                <option value="<?= esc($maquina['id_maquina']) ?>"><?= esc($maquina['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div style="display: inline-block; vertical-align: middle;">
                    <button id="clearMachineFilter" class="btn btn-sm btn-light ms-2" onclick="eliminarFiltroMaquina()">
                        <i class="bi bi-x-circle"></i> Eliminar Filtros
                    </button>
                </div>
            </div>
            <div class="resultados">
                <table id="sortableTable" class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllCol4" class="selectAll"></th>
                            <th>
                                id
                                <br>
                                <input type="text" id="idSearchInputCol4" class="form-control d-inline-block" style="width: 70%; font-size: 1em; border: 1px solid #989A9C;" placeholder="ID" onkeyup="filtrarPorIdCol4();">
                            </th>
                            <th>
                                Cliente
                                <select id="clienteFilterCol4" style="width: 100%;" onchange="filtrarPorClienteCol4(this.value);">
                                    <option value="">Todos</option>
                                    <?php if (isset($clientes)) : ?>
                                        <?php foreach ($clientes as $cliente) : ?>
                                            <option value="<?= esc($cliente['nombre_cliente']) ?>"><?= esc($cliente['nombre_cliente']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </th>
                            <th>Medidas</th>
                            <th>Fecha Entrega</th>
                            <th>
                                Producto
                                <select id="productoFilterCol4" style="width: 100%;" onchange="filtrarPorProducto(this.value, 4);">
                                    <option value="">Todos</option>
                                    <?php if (isset($productos)) : ?>
                                        <?php foreach ($productos as $producto) : ?>
                                            <option value="<?= esc($producto['nombre_producto']) ?>"><?= esc($producto['nombre_producto']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>

                            </th>

                            <th>Nº Piezas</th>
                            <th>Proceso</th>
                            <th>Base</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lineasEstado3 as $linea) : ?>
                            <tr class="linea" data-nombre-cliente="<?= esc($linea['cliente']) ?>" data-nombre-proceso="<?= esc($linea['proceso']); ?>" data-nombre-producto="<?= esc($linea['producto']); ?>" data-id-maquina="<?= $linea['id_maquina']; ?>" data-estado="<?= esc($linea['guardado']) ? 'guardado' : 'no-guardado'; ?>">
                                <td><input type="checkbox" class="checkboxCol4" name="selectedLineCol4[]"></td>
                                <td><?= $linea['id_linea_pedido']; ?></td>
                                <td><?= $linea['cliente'] ?></td>
                                <td><?= $linea['medidas'] ?></td>
                                <td><?= $linea['fecha'] ?></td>
                                <td><?= $linea['producto'] ?></td>
                                <td><?= $linea['n_piezas'] ?></td>
                                <td>
                                    <?= esc($linea['proceso']); ?>
                                    <?php if ($linea['restriccion'] !== null && $linea['restriccion'] !== '0' && $linea['restriccion'] !== '') : ?>
                                        <span style="margin-left: 5px;">🔒</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= $linea['base'] ?></td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="printableArea" style="display: none;">
            <div id="fondo">
                <div id="printableContent">
                    <h1>Informe de Procesos en Máquinas</h1>
                    <?php foreach ($maquinas as $maquina) : ?>
                        <div>
                            <h2>Máquina: <?= esc($maquina['nombre']); ?></h2>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Línea Pedido</th>
                                        <th>Cliente</th>
                                        <th>Medidas</th>
                                        <th>Fecha Entrega</th>
                                        <th>Producto</th>
                                        <th>Nº Piezas</th>
                                        <th>Proceso</th>
                                        <th>Base</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lineasEstado3 as $linea) : ?>
                                        <?php if ($linea['id_maquina'] == $maquina['id_maquina']) : ?>
                                            <tr>
                                                <td><?= esc($linea['id_linea_pedido']); ?></td>
                                                <td><?= esc($linea['cliente']); ?></td>
                                                <td><?= esc($linea['medidas']); ?></td>
                                                <td><?= esc($linea['fecha']); ?></td>
                                                <td><?= esc($linea['producto']); ?></td>
                                                <td><?= esc($linea['n_piezas']); ?></td>
                                                <td><?= esc($linea['proceso']); ?></td>
                                                <td><?= esc($linea['base']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function printDiv(divId) {
        // Verificar si hay una máquina seleccionada
        if (!selectedMachineId) {
            alert('¡Seleccione una máquina antes de imprimir!');
            return;
        }

        // Generar contenido imprimible solo para la máquina seleccionada
        generarContenidoImprimible();

        const printContents = document.getElementById(divId).innerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Impresión</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 12px;
                            color: #333;
                        }
                        h1, h2 {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                        @media print {
                            @page {
                                margin: 1cm;
                            }
                            body {
                                margin: 0;
                                padding: 0;
                            }
                            header, footer {
                                position: fixed;
                                width: 100%;
                                background-color: #f8f8f8;
                                padding: 5px;
                                text-align: center;
                                font-size: 10px;
                            }
                            header {
                                top: 0;
                            }
                            footer {
                                bottom: 0;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.print();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Añadir evento de clic a cada fila de la tabla
        document.querySelectorAll('.linea').forEach(function(row) {
            row.addEventListener('click', function(event) {
                // Evitar que el evento se propague si se hace clic en el checkbox directamente
                if (event.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                }
            });
        });

        actualizarColores();
        actualizarColoresCol2();
        generarContenidoImprimible();
        seleccionarMaquinaGuardada();

        // Guardar el orden original de las filas
        const tbody = document.querySelector('#Tabla2 tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        originalOrder = rows.map((row, index) => ({
            element: row,
            index: index
        }));
    });

    function generarContenidoImprimible() {
        const maquinas = <?php echo json_encode($maquinas); ?>;
        const lineasEstado3 = <?php echo json_encode($lineasEstado3); ?>;

        // Obtener la fecha actual
        const fechaActual = new Date();
        const fechaFormateada = fechaActual.toLocaleDateString();

        const content = document.getElementById('printableContent');
        content.innerHTML = `<h1>Informe de Procesos en Máquinas - ${fechaFormateada}</h1>`;

        const maquinaSeleccionada = maquinas.find(maquina => maquina.id_maquina === selectedMachineId);
        if (maquinaSeleccionada) {
            const lineasMaquina = lineasEstado3.filter(linea => linea.id_maquina === selectedMachineId);

            if (lineasMaquina.length > 0) {
                const maquinaDiv = document.createElement('div');
                maquinaDiv.innerHTML = `<h2>Máquina: ${maquinaSeleccionada.nombre}</h2>`;

                const table = document.createElement('table');
                table.className = 'table table-sm table-hover';
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>ID Línea Pedido</th>
                            <th>Cliente</th>
                            <th>Medidas</th>
                            <th>Fecha Entrega</th>
                            <th>Producto</th>
                            <th>Nº Piezas</th>
                            <th>Proceso</th>
                            <th>Base</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                `;

                const tbody = table.querySelector('tbody');

                lineasMaquina.forEach(linea => {
                    if (!linea.restriccion || linea.restriccion === '0' || linea.restriccion === '') {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${linea.id_linea_pedido}</td>
                            <td>${linea.cliente}</td>
                            <td>${linea.medidas}</td>
                            <td>${linea.fecha}</td>
                            <td>${linea.producto}</td>
                            <td>${linea.n_piezas}</td>
                            <td>${linea.proceso}</td>
                            <td>${linea.base}</td>
                        `;
                        tbody.appendChild(row);
                    }
                });

                maquinaDiv.appendChild(table);
                content.appendChild(maquinaDiv);
            }
        }
    }
</script>

</body>

</html>
<script>
    // Variables globales
    // Variables globales
    let selectedMachineId = null;
    let selectedClientFilterCol2 = '';
    let selectedProcesoFilterCol2 = '';
    let selectedClientFilterCol4 = '';
    let selectedProcesoFilterCol4 = '';
    let selectedProductoFilterCol2 = '';
    let selectedProductoFilterCol4 = '';
    let idFilterCol2 = '';
    let idFilterCol4 = '';

    let sortable;

    $(document).ready(function() {
        // Inicializar select2 en el select con id 'searchInput'
        $('#searchInput').select2({
            placeholder: 'Seleccione un proceso...',
            allowClear: true
        });

        // Inicializar select2 en col4 si col2 también lo tiene (mantener tu código existente)
        $('#maquinaFilterCol4').select2({
            placeholder: 'Seleccione una máquina...',
            allowClear: true
        });
    });

    $(document).ready(function() {
    // Manejar el clic en el botón "pedido"
    $('[data-action="pedido"]').click(function() {
        // Hacemos la petición AJAX para obtener los procesos con estado 4
        $.ajax({
            url: '<?= base_url('procesos_pedidos/getProcesosEstado4') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var tabla = '';
                $.each(response, function(index, proceso) {
                    tabla += '<tr>';
                    tabla += '<td>' + proceso.id_linea_pedido + '</td>';
                    tabla += '<td>' + proceso.nombre_proceso + '</td>';
                    tabla += '<td>' + proceso.nombre_producto + '</td>';
                    tabla += '<td><button class="btn btn-warning revertir-estado" data-id-relacion="' + proceso.id_relacion + '">Revertir</button></td>';
                    tabla += '</tr>';
                });
                $('#tablaProcesos').html(tabla);
                $('#modalProcesos').modal('show');
            },
            error: function() {
                alert('Error al cargar los datos.');
            }
        });
    });

    // Manejar el clic en el botón "Revertir Estado"
    $(document).on('click', '.revertir-estado', function() {
        var idRelacion = $(this).data('id-relacion');

        $.ajax({
            url: '<?= base_url('procesos_pedidos/actualizarEstadoYEliminarRestricciones/') ?>' + idRelacion,
            type: 'POST',
            success: function(response) {
                if (response.success) {
            $('#modalProcesos').modal('hide');
            location.reload(); 
                } else {
                    alert('Error al revertir el estado del proceso.');
                }
            },
        });
    });
});
    // Funciones de filtrado
    function aplicarFiltros(columna) {
        const tableRows = document.querySelectorAll(`#col${columna} tbody tr`);
        const clientFilter = columna === 2 ? selectedClientFilterCol2 : selectedClientFilterCol4;
        const procesoFilter = columna === 2 ? selectedProcesoFilterCol2 : selectedProcesoFilterCol4;
        const productoFilter = columna === 2 ? selectedProductoFilterCol2 : selectedProductoFilterCol4;
        const idFilter = columna === 2 ? idFilterCol2 : idFilterCol4;
        const maquinaFilter = columna === 4 ? selectedMachineId : null;

        tableRows.forEach(row => {
            const cliente = row.getAttribute('data-nombre-cliente');
            const proceso = row.getAttribute('data-nombre-proceso');
            const producto = row.getAttribute('data-nombre-producto');
            const idMaquina = row.getAttribute('data-id-maquina');
            const id = row.querySelector('td:nth-child(2)').textContent.trim();
            let display = true;

            if (clientFilter && cliente && !cliente.toLowerCase().includes(clientFilter)) {
                display = false;
            }
            if (procesoFilter && proceso && !proceso.toLowerCase().includes(procesoFilter)) {
                display = false;
            }
            if (productoFilter && producto && !producto.toLowerCase().includes(productoFilter)) {
                display = false;
            }
            if (idFilter && id && !id.toUpperCase().includes(idFilter)) {
                display = false;
            }
            if (columna === 4 && maquinaFilter && idMaquina !== maquinaFilter) {
                display = false;
            }


            row.style.display = display ? '' : 'none';
        });
    }


    function filtrarPorCliente(valor, columna) {
        if (columna === 2) {
            selectedClientFilterCol2 = valor.toLowerCase();
        } else {
            selectedClientFilterCol4 = valor.toLowerCase();
        }
        aplicarFiltros(columna);
    }

    function filtrarPorClienteCol4(valor) {
        selectedClientFilterCol4 = valor.toLowerCase();
        aplicarFiltros(4);
    }


    function filtrarPorProceso(valor, columna) {
        // Deseleccionar todos los checkboxes seleccionados previamente
        document.querySelectorAll(`#col${columna} input[type="checkbox"]:checked`).forEach(checkbox => {
            checkbox.checked = false;
        });
        if (columna === 2) {
            selectedProcesoFilterCol2 = valor.toLowerCase();
        } else {
            selectedProcesoFilterCol4 = valor.toLowerCase();
        }
        aplicarFiltros(columna);
    }

    function filtrarPorIdCol2() {
        var input = document.getElementById("idSearchInputCol2");
        idFilterCol2 = input.value.toUpperCase();
        aplicarFiltros(2);
    }

    function filtrarPorIdCol4() {
        var input = document.getElementById("idSearchInputCol4");
        idFilterCol4 = input.value.toUpperCase();
        aplicarFiltros(4);
    }

    function filtrarProcesosPorMaquina(idMaquina, nombreMaquina) {
        // Deseleccionar todos los checkboxes seleccionados previamente
        document.querySelectorAll('#col4 input[type="checkbox"]:checked').forEach(checkbox => {
            checkbox.checked = false;
        });

        selectedMachineId = idMaquina;
        console.log("Maquina seleccionada:", selectedMachineId);

        document.querySelectorAll('#col4 .linea').forEach(row => {
            const estado = row.getAttribute('data-estado');
            const idMaquinaFila = row.getAttribute('data-id-maquina');
            const shouldDisplay = (estado === 'no-guardado' || idMaquinaFila === idMaquina);
            row.style.display = shouldDisplay ? '' : 'none';
            console.log("Fila:", row, "Visible:", shouldDisplay);
        });

        if (sortable) {
            sortable.option("disabled", false); // Habilitar Sortable al seleccionar una máquina
            console.log("Sortable activado");
        }
        aplicarFiltros(4);
    }


    function filtrarPorMedida(valor) {
        const tbody = document.querySelector('#Tabla2 tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        if (valor === "") {
            // Restaurar el orden original
            originalOrder.sort((a, b) => a.index - b.index).forEach(item => tbody.appendChild(item.element));
        } else {
            // Función para extraer la medida desde el texto
            const getMedida = (texto, tipo) => {
                const partes = texto.split('-').map(parte => parseFloat(parte.trim()) || 0);
                if (tipo === 'iniciales') {
                    return partes[0]; // Primera medida (antes del '-')
                } else if (tipo === 'finales') {
                    return partes[1] || 0; // Segunda medida (después del '-') o 0 si no existe
                }
                return 0;
            };

            // Función de comparación para ordenar las filas
            const compareFunction = (a, b) => {
                const medidaA = getMedida(a.querySelector('td:nth-child(4)').textContent, valor);
                const medidaB = getMedida(b.querySelector('td:nth-child(4)').textContent, valor);

                return medidaA - medidaB; // Orden ascendente
            };

            // Ordenar las filas usando la función de comparación
            rows.sort(compareFunction).forEach(row => tbody.appendChild(row));
        }
    }

    function filtrarPorProducto(valor, columna) {
        if (columna === 2) {
            selectedProductoFilterCol2 = valor.toLowerCase();
        } else {
            selectedProductoFilterCol4 = valor.toLowerCase();
        }
        aplicarFiltros(columna);
    }

    // Funciones de movimiento y confirmación
    function moverPedidos(selectorCheckbox, selectorTablaDestino) {
        document.querySelectorAll(selectorCheckbox).forEach(checkbox => {
            const filaOriginal = checkbox.closest('tr');
            const tablaDestino = document.querySelector(selectorTablaDestino);

            if (!tablaDestino) {
                console.error('El selector de la tabla destino no encontró ningún elemento:', selectorTablaDestino);
                return;
            }

            const nuevaFila = crearNuevaFila(filaOriginal);
            tablaDestino.appendChild(nuevaFila);
            filaOriginal.remove();
            nuevaFila.classList.add('fondo-rojo');
        });
        actualizarColores();
        actualizarColoresCol2();
    }

    function crearNuevaFila(filaOriginal) {
        const nuevaFila = document.createElement('tr');
        nuevaFila.className = 'linea';
        nuevaFila.setAttribute('data-id-maquina', selectedMachineId); // Asegura que el ID de la máquina esté correctamente asignado
        nuevaFila.setAttribute('data-nombre-proceso', filaOriginal.getAttribute('data-nombre-proceso'));
        nuevaFila.setAttribute('data-estado', 'no-guardado');

        const tdCheckbox = document.createElement('td');
        const nuevoCheckbox = document.createElement('input');
        nuevoCheckbox.type = 'checkbox';
        tdCheckbox.appendChild(nuevoCheckbox);
        nuevaFila.appendChild(tdCheckbox);

        Array.from(filaOriginal.children).slice(1).forEach(td => {
            nuevaFila.appendChild(td.cloneNode(true));
        });

        return nuevaFila;
    }

    function confirmarProcesos() {
        // Guardar la máquina seleccionada en el almacenamiento local antes de confirmar
        if (selectedMachineId) {
            localStorage.setItem('selectedMachineId', selectedMachineId);
        }
        const procesosActualizar = obtenerProcesos('#col4 tbody tr', true);
        const procesosRevertir = obtenerProcesos('#col2 tbody tr', false);

        if (procesosActualizar.length > 0) {
            actualizarProcesos(procesosActualizar);
        }

        if (procesosRevertir.length > 0) {
            revertirProcesos(procesosRevertir);
        }
    }

    function obtenerProcesos(selector, conOrden) {
        return Array.from(document.querySelectorAll(selector)).filter(fila => {
            const filaMaquinaId = fila.getAttribute('data-id-maquina');
            return filaMaquinaId && (conOrden ? filaMaquinaId === selectedMachineId : true);
        }).map((fila, index) => ({
            nombre_proceso: fila.getAttribute('data-nombre-proceso'),
            id_linea_pedido: fila.querySelector('td:nth-child(2)').textContent.trim(),
            id_maquina: conOrden ? selectedMachineId : fila.getAttribute('data-id-maquina'),
            orden: conOrden ? index + 1 : 0
        }));
    }


    function actualizarProcesos(procesos) {
        realizarPeticionAjax('<?php echo base_url('procesos_pedidos/actualizarEstadoProcesos'); ?>', procesos, () => {
            actualizarEstadoLineaPedido();
        });
    }

    function revertirProcesos(procesos) {
        realizarPeticionAjax('<?php echo base_url('procesos_pedidos/revertirEstadoProcesos'); ?>', procesos, () => {
            localStorage.setItem('reloadedFromConfirm', 'true');
            window.location.reload();
        });
    }

    function actualizarEstadoLineaPedido() {
        fetch('<?php echo base_url('procesos_pedidos/actualizarEstadoLineaPedido'); ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('reloadedFromConfirm', 'true');
                    window.location.reload();
                } else {
                    alert('Error al actualizar los estados de las líneas de pedido.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Funciones de utilidad
    function realizarPeticionAjax(url, procesos, callback) {
        $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                procesos: procesos
            }),
            success: function(response) {
                if (response.success) {
                    if (callback) callback();
                } else {
                    alert('Error al actualizar los procesos.');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error en la solicitud AJAX. Revisa la consola para más detalles.');
            }
        });
    }

    function mostrarTodasLasLineas() {
        document.querySelectorAll('#col4 .linea').forEach(linea => {
            linea.style.display = '';
        });
    }

    function actualizarColores() {
        document.querySelectorAll('#col4 .linea').forEach(fila => {
            fila.classList.toggle('sin-color', fila.getAttribute('data-guardado') === 'guardado');
            fila.classList.toggle('verde-tenue', fila.getAttribute('data-guardado') !== 'guardado');
        });
    }

    function actualizarColoresCol2() {
        document.querySelectorAll('#col2 .linea').forEach(fila => {
            fila.classList.toggle('proceso-col2', fila.getAttribute('data-guardado') !== 'guardado');
        });
    }

    function seleccionarMaquinaGuardada() {
        // Revisar si hay un ID de máquina guardado
        const savedMachineId = localStorage.getItem('selectedMachineId');
        if (savedMachineId) {
            const maquina = document.querySelector(`#maquinaFilterCol4 option[value="${savedMachineId}"]`);
            if (maquina) {
                // Selecciona la opción en el select y simula un cambio para aplicar el filtro
                maquina.selected = true;
                filtrarProcesosPorMaquina(savedMachineId);
            }
            localStorage.removeItem('selectedMachineId'); // Elimina la máquina guardada del almacenamiento local
        }
    }

    // Inicialización y eventos
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Select2
        ['#searchInput', '#clienteFilter', '#medidasFilter', '#productoFilterCol2', '#productoFilterCol4', '#clienteFilterCol4', '#searchInputCol4'].forEach(selector => {
            $(selector).select2();
        });

        // Seleccionar la máquina guardada (si hay alguna)
        seleccionarMaquinaGuardada();

        // Eventos de filtrado
        $('#searchInput').on('change', e => filtrarPorProceso(e.target.value, 2));
        $('#clienteFilter').on('change', e => filtrarPorCliente(e.target.value, 2));
        $('#clienteFilterCol4').on('change', e => filtrarPorCliente(e.target.value, 4));
        $('#searchInputCol4').on('change', e => filtrarPorProceso(e.target.value, 4));
        $('#medidasFilter').on('change', e => filtrarPorMedida(e.target.value));

        // Evento para limpiar filtros
        $('#clearFilters').on('click', () => {
            ['#searchInput', '#clienteFilter', '#productoFilterCol2', '#productoFilterCol4', '#clienteFilterCol4', '#medidasFilter', ].forEach(selector => {
                $(selector).val('').trigger('change');
            });
            $('#idSearchInputCol2').val('').trigger('keyup');
            if (sortable) sortable.option("disabled", true);
        });

        // Eventos de botones
        document.querySelectorAll('button[data-action]').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                if (action === 'move-right') {
                    if (!selectedMachineId) {
                        alert('¡Seleccione una máquina!');
                        return;
                    }
                    moverPedidos('input[type="checkbox"]:checked', '#col4 table tbody');
                } else if (action === 'move-left') {
                    moverPedidos('#col4 input[type="checkbox"]:checked', '#col2 table tbody');
                } else if (action === 'confirm') {
                    confirmarProcesos();
                }
            });
        });

        document.querySelectorAll('.selectAll').forEach(selectAllCheckbox => {
            selectAllCheckbox.addEventListener('click', function(event) {
                event.preventDefault(); // Evita que el checkbox principal se marque o desmarque
                const columnId = this.id === 'selectAllCol2' ? 'Col2' : 'Col4';
                const checkboxes = document.querySelectorAll(`input[name="selectedLine${columnId}[]"]`);
                const isChecked = !this.classList.contains('highlight');
                // Filtrar solo los checkboxes visibles
                checkboxes.forEach(checkbox => {
                    if (checkbox.offsetParent !== null) { // Verifica si el checkbox es visible
                        checkbox.checked = isChecked;
                    }
                });
                this.classList.toggle('highlight', isChecked);
            });
        });

        // Eventos de máquinas
        document.querySelectorAll('.maquina').forEach(maquina => {
            maquina.addEventListener('click', function() {
                selectedMachineId = this.getAttribute('data-id-maquina');
                filtrarProcesosPorMaquina(selectedMachineId, this.getAttribute('data-nombre'));
                if (sortable) sortable.option("disabled", false);
            });
        });

        // Evento para ver todo
        $('#verTodo').on('click', () => {
            selectedMachineId = null;
            selectedClientFilterCol4 = '';
            selectedProcesoFilterCol4 = '';
            $('#clienteFilterCol4, #searchInputCol4').val('').trigger('change');
            mostrarTodasLasLineas();
            document.getElementById('tituloProcesosEnMaquina').textContent = 'Procesos en máquinas';
            if (sortable) sortable.option("disabled", true);
        });

        // Inicializar Sortable
        var el = document.getElementById('sortableTable').getElementsByTagName('tbody')[0];
        sortable = Sortable.create(el, {
            animation: 150,
            onEnd: function(evt) {
                actualizarOrdenProcesos();
            }
        });

        document.querySelector('button[data-action="btn-terminado"]').addEventListener('click', function() {
            marcarComoTerminado(this);
        });

        sortable.option("disabled", true); // Deshabilitar Sortable inicialmente
    });

    // Eventos adicionales
    document.addEventListener('click', function(event) {
        if (event.target.matches('button[data-action="confirm"]')) {
            actualizarOrdenProcesos();
        }

        const targetTerminado = event.target.closest('button[data-action="btn-terminado"]');
        if (targetTerminado) {
            event.preventDefault();
            marcarComoTerminado(targetTerminado);
        }
    });

    function actualizarOrdenProcesos() {
        const filas = document.querySelectorAll('#sortableTable tbody tr');
        let ordenes = Array.from(filas)
            .filter(fila => fila.getAttribute('data-id-maquina') === selectedMachineId)
            .map((fila, index) => ({
                id_linea_pedido: fila.querySelector('td:nth-child(2)').textContent.trim(),
                nombre_proceso: fila.getAttribute('data-nombre-proceso').trim(),
                orden: index + 1,
                id_maquina: fila.getAttribute('data-id-maquina').trim()
            }));

        console.log('Ordenes a enviar:', ordenes);

        fetch('<?php echo base_url('procesos_pedidos/actualizarOrdenProcesos'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ordenes: ordenes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Orden actualizado correctamente.');
                    console.log('Procesos actualizados:', data.procesos_actualizados);
                    if (data.procesos_no_encontrados.length > 0) {
                        console.warn('Algunos procesos no se encontraron:', data.procesos_no_encontrados);
                    }
                } else {
                    alert('Error al actualizar el orden: ' + data.error);
                }
            });
    }


    function eliminarFiltroMaquina() {
        // Restablecer el valor del select de máquinas a vacío (sin selección)
        document.getElementById('maquinaFilterCol4').value = '';

        // Eliminar la máquina seleccionada de la variable global
        selectedMachineId = null;

        // Mostrar todas las filas en la columna 4
        mostrarTodasLasLineas();

        // Deshabilitar el sortable si no hay una máquina seleccionada
        if (sortable) {
            sortable.option("disabled", true);
        }
    }

    function mostrarTodasLasLineas() {
        document.querySelectorAll('#col4 .linea').forEach(linea => {
            linea.style.display = ''; // Mostrar todas las líneas
        });
    }


    function marcarComoTerminado(button) {
        event.stopPropagation();
        event.preventDefault();

        const selectedLines = document.querySelectorAll('#col4 input[name="selectedLineCol4[]"]:checked');
        let lineItems = [];
        let hasRestriction = false;

        selectedLines.forEach(line => {
            const row = line.closest('tr');
            const restriccion = row.querySelector('td:nth-child(8) span');

            if (restriccion) {
                hasRestriction = true;
            }

            lineItems.push({
                idLineaPedido: row.querySelector('td:nth-child(2)').textContent.trim(),
                nombreProceso: row.querySelector('td:nth-child(8)').textContent.trim()
            });
        });

        if (lineItems.length > 0) {
            button.disabled = true;

            if (selectedMachineId) {
                localStorage.setItem('selectedMachineId', selectedMachineId);
            }

            fetch('<?php echo base_url('procesos_pedidos/marcarTerminado'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        lineItems: lineItems
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        let message = 'Uno o más procesos seleccionados tienen restricciones pendientes.\n\n';
                        data.procesosConRestricciones.forEach(item => {
                            message += `${item.nombre_proceso}\nRestringido por: ${item.restricciones.join(', ')}\n\n`;
                        });
                        alert(message);
                        window.location.reload();
                    } else {
                        localStorage.setItem('reloadedFromTerminar', 'true');
                        window.location.reload();
                    }
                })
                .catch(error => {
                    alert('Error al actualizar los estados.');
                })
                .finally(() => {
                    button.disabled = false;
                });
        }
    }
</script>