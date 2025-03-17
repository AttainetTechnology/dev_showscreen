<?php //print_r($presentes); ?>
<?= $cabecera; ?>

<body class="page-select" onload="startTime()">
    <?= $hora; ?>
    <div class="fondo-select">
        <div class="cabecera2">
            <h2><?= $usuario['nombre_usuario']; ?> <?= $usuario['apellidos_usuario']; ?></h2>
        </div>
        <div class="d-flex justify-content-end ">
            <a href="<?= site_url('/presentes')?>" class="btn volverButton">
                <span class="glyphicon glyphicon-arrow-left"></span> Volver
            </a>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-12 columna1 divMaquinas">
                    <form action="<?= site_url('selectMaquina') ?>" method="POST">
                        <div class="table-responsive">
                            <table class="maquina table table-bordered w-100" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                    </tr>
                                </thead>
                                <tbody>
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
                <div class="col-md-8 col-sm-12 columna2">
                    <?php if (isset($procesos) && !empty($procesos)): ?>
                        <h2>Procesos en <?= $nombreMaquinaSeleccionada ?></h2>
                        <div class="table-responsive">
                            <table class="procesos table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Acción</th>
                                        <th>Proceso</th>
                                        <th>Producto</th>
                                        <th>Observaciones</th>
                                        <th>Nº de Piezas</th>
                                        <th>Nombre Base</th>
                                        <th>Med. Inicial</th>
                                        <th>Med. Final</th>
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
                                            <td class="nombre_proceso"><?= $proceso['nombre_proceso'] ?></td>
                                            <td>
                                                <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto"
                                                    width="100">
                                                <br>
                                                <strong><?= $proceso['nombre_producto'] ?></strong>
                                            </td>
                                            <td><?= $proceso['observaciones'] ?></td>
                                            <td><?= $proceso['n_piezas'] ?></td>
                                            <td><?= $proceso['nom_base'] ?></td>
                                            <td><?= $proceso['med_inicial'] ?></td>
                                            <td><?= $proceso['med_final'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif (!isset($idMaquina) || $idMaquina == null): ?>
                        <h2>Procesos asociados</h2>
                        <div class="table-responsive">
                            <table class="procesos table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Acción</th>
                                        <th>Proceso</th>
                                        <th>Cliente</th>
                                        <th>Producto</th>
                                        <th>Observaciones</th>
                                        <th>Nº de Piezas</th>
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
                                                <td class="nombre_proceso"><?= $proceso['nombre_proceso'] ?></td>
                                                <td><?= $proceso['nombre_cliente'] ?></td>
                                                <td>
                                                    <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto"
                                                        width="100">
                                                    <br>
                                                    <strong><?= $proceso['nombre_producto'] ?></strong>
                                                </td>
                                                <td><?= $proceso['observaciones'] ?></td>
                                                <td><?= $proceso['n_piezas'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6">NO TIENES PROCESOS ACTIVOS</td>
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
        (function () {
            var tiempoInactividad = 30000; // 30 segundos
            var temporizador;

            function resetTemporizador() {
                clearTimeout(temporizador);
                temporizador = setTimeout(function () {
                    window.location.href = '/presentes';
                }, tiempoInactividad);
            }

            // Eventos que reiniciarán el temporizador
            window.onload = resetTemporizador;
            window.onmousemove = resetTemporizador;
            window.onmousedown = resetTemporizador;  //interacción táctil/teclado
            window.ontouchstart = resetTemporizador;
            window.onclick = resetTemporizador;     //clics
            window.onkeypress = resetTemporizador;
            window.addEventListener('scroll', resetTemporizador, true);  //scroll

        })();
    </script>