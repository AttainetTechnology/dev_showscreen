<?php //print_r($presentes); 
?>
<?= $cabecera; ?>

<body class="page-select" onload="startTime()">
    <?= $hora; ?>
    <div class="fondo-select">
        <div class="cabecera2">
            <h2><?= $usuario['nombre_usuario']; ?> <?= $usuario['apellidos_usuario']; ?></h2>
        </div>
        <div class="d-flex justify-content-end ">
            <a href="<?= site_url('/presentes') ?>" class="btn volverButton">
                <span class="glyphicon glyphicon-arrow-left"></span> Volver
            </a>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-12 columna1 divMaquinas">
                    <form action="<?= site_url('selectMaquina') ?>" method="POST">
                        <div class="table-responsive">
                            <table class="maquina table table-bordered w-100" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Si hay procesos activos -->
                                    <?php foreach ($maquinas as $maquina): ?>
                                        <tr>
                                            <td style="word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">
                                                <button type="submit" name="id_maquina"
                                                    value="<?= $maquina['id_maquina'] ?>"
                                                    class="btn btn-light btn-block"><?= $maquina['nombre'] ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 col-sm-12 columna2">
                    <?php if (isset($procesos) && !empty($procesos)): ?>
                        <h2><?= $nombreMaquinaSeleccionada ?></h2>
                        <div class="table-responsive">
                            <table class="procesos table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Parte <input type="text" id="searchParte" onkeyup="filtrarPartes()" placeholder="Buscar..." style="width:80%;"></th> </th>
                                        <th>Cliente</th>
                                        <th>Proceso</th>
                                        <th>Nº de Piezas</th>
                                        <th>Med. inicial</th>
                                        <th>Med. final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($procesos as $proceso): ?>
                                        <tr>
                                            <td>
                                                <form action="<?= site_url('seleccionarProceso') ?>" method="POST">
                                                    <input type="hidden" name="id_linea_pedido"
                                                        value="<?= $proceso['id_linea_pedido'] ?>">
                                                    <input type="hidden" name="id_proceso_pedido"
                                                        value="<?= $proceso['id_relacion'] ?>">
                                                    <input type="hidden" name="id_pedido" value="<?= $proceso['id_pedido'] ?>">
                                                    <input type="hidden" name="id_maquina" value="<?= $idMaquina ?>">
                                                    <button type="submit" class="btn boton btnAdd">Seleccionar</button>
                                                </form>
                                            </td>
                                            <td class="nombre_proceso"><?= $proceso['id_linea_pedido'] ?></td>
                                                <td><?= $proceso['nombre_cliente'] ?></td>
                                                <td>
                                                <?= $proceso['nombre_proceso'] ?><br><img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto"
                                                        width="100"><br><strong><?= $proceso['nombre_producto'] ?></strong>
                                                </td>
                                                <td><strong><?= $proceso['n_piezas'] ?></strong> pzas <?= $proceso['nom_base'] ?></td>
                                                <td><?= $proceso['med_inicial'] ?></td>
                                                <td><?= $proceso['med_final'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Si hay procesos asignados -->
                    <?php elseif (!isset($idMaquina) || $idMaquina == null): ?>
                        <h2>Tus partes activos</h2>
                        <div class="table-responsive">
                            <table class="procesos table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Parte </th>
                                        <th>Cliente</th>
                                        <th>Proceso</th>
                                        <th>Nº de Piezas</th>
                                        <th>Producto</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($procesosUsuario)): ?>
                                        <?php foreach ($procesosUsuario as $proceso): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= site_url('editarProceso/' . $proceso['id']) ?>"
                                                        class="btn boton btnEditar">Editar</a>
                                                </td>
                                                <td class="nombre_proceso"><?= $proceso['id_linea_pedido'] ?></td>
                                                <td><?= $proceso['nombre_cliente'] ?></td>
                                                <td>
                                                <?= $proceso['nombre_proceso'] ?>
                                                </td>
                                                <td><strong><?= $proceso['n_piezas'] ?></strong> pzas <?= $proceso['nom_base'] ?></td>
                                                <td><strong><?= $proceso['nombre_producto'] ?></strong><br><img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto"
                                                        width="100"></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6">NO TIENES PARTES ASIGNADOS</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <br>
                </div>
            </div>
        </div>

        <script>
            window.history.replaceState({}, document.title, "<?= base_url('selectMaquina'); ?>");
        </script>

    </div>
    <script>
        (function() {
            var tiempoInactividad = 30000; // 30 segundos
            var temporizador;

            function resetTemporizador() {
                clearTimeout(temporizador);
                temporizador = setTimeout(function() {
                    window.location.href = '/presentes';
                }, tiempoInactividad);
            }

            // Eventos que reiniciarán el temporizador
            window.onload = resetTemporizador;
            window.onmousemove = resetTemporizador;
            window.onmousedown = resetTemporizador; //interacción táctil/teclado
            window.ontouchstart = resetTemporizador;
            window.onclick = resetTemporizador; //clics
            window.onkeypress = resetTemporizador;
            window.addEventListener('scroll', resetTemporizador, true); //scroll

        })();

        function filtrarPartes() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchParte");
            filter = input.value.toUpperCase();
            table = document.querySelector(".table-responsive .procesos");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) { // Empieza en 1 para evitar el encabezado
                td = tr[i].getElementsByTagName("td")[1]; // Index 1, segunda columna
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>