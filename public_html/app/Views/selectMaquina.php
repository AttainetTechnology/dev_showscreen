<?php //print_r($presentes); ?>
<?= $cabecera; ?>

<body class="page-select" onload="startTime()">
    <?= $hora; ?>
    <div class="fondo-select">
        <div class="cabecera2">
            <h2><?= $usuario['nombre_usuario']; ?> <?= $usuario['apellidos_usuario']; ?></h2>
        </div>
        <div class="d-flex justify-content-end ">
            <a href="<?= site_url('salir/' . $usuario['id']); ?>" class="btn volverButton">
                <span class="glyphicon glyphicon-arrow-left"></span> Volver
            </a>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 columna1">
                    <form action="<?= site_url('selectMaquina') ?>" method="POST">
                        <table class="maquina table table-bordered">
                            <thead>
                                <tr>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($maquinas as $maquina): ?>
                                    <tr>
                                        <td>
                                            <button type="submit" name="id_maquina" value="<?= $maquina['id_maquina'] ?>"
                                                class="btn btn-light btn-block"><?= $maquina['nombre'] ?></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-8 columna2">
                    <?php if (isset($procesos) && !empty($procesos)): ?>
                        <h2>Procesos en <?= $nombreMaquinaSeleccionada ?></h2>
                        <table class="procesos table  table-responsive">
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
                                                <button type="submit" class="btn boton btnAdd">Seleccionar
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27"
                                                        viewBox="0 0 26 27" fill="none">
                                                        <path
                                                            d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z"
                                                            fill="white" />
                                                    </svg>

                                                </button>
                                            </form>
                                        </td>
                                        <td class="nombre_proceso"><?= $proceso['nombre_proceso'] ?></td>
                                        <td>
                                            <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto" width="100">
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
                    <?php elseif (!isset($idMaquina) || $idMaquina == null): ?>
                        <h2>Procesos asociados</h2>
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
                                                    class="btn boton  btnEditar">Editar
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16"
                                                        viewBox="0 0 15 16" fill="none">
                                                        <path
                                                            d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z"
                                                            fill="white" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z"
                                                            fill="white" />
                                                    </svg></a>
                                            </td>
                                            <td class="nombre_proceso"><?= $proceso['nombre_proceso'] ?></td>
                                            <td><?= $proceso['nombre_cliente'] ?></td>
                                            <td>
                                                <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto" width="100">
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
                    <?php endif; ?>
                    <br>
                </div>
                <script>
                    window.history.replaceState({}, document.title, "<?= base_url('selectMaquina'); ?>");
                </script>
            </div>
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