<div class="fondo-empleados">
    <div class="container-fluid d-flex flex-row flex-wrap">
        <h2><?= $usuario['nombre_usuario']; ?> <?= $usuario['apellidos_usuario']; ?></h2>
        <div class="volver">
            <a href="<?= site_url('salir/' . $usuario['id']); ?>" class="btn btn-light">
                <span class="glyphicon glyphicon-arrow-left"></span> Volver
            </a>
            <!-- Formulario para seleccionar la máquina, pero solo mostrando las máquinas en una tabla -->
            <form action="<?= site_url('selectMaquina') ?>" method="POST">
                <label for="maquina">Seleccione una Máquina:</label>

                <!-- Tabla para mostrar las máquinas -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre de la Máquina</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maquinas as $maquina): ?>
                            <tr>
                                <td>
                                    <!-- Cada fila de la tabla es un botón de selección que pasa el id de la máquina al formulario -->
                                    <button type="submit" name="id_maquina" value="<?= $maquina['id_maquina'] ?>"
                                        class="btn btn-light btn-block"><?= $maquina['nombre'] ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
            <?php if (isset($procesos) && !empty($procesos)): ?>
                <h2>Procesos Seleccionados en <?= $nombreMaquinaSeleccionada ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th>Proceso</th>
                            <th>Linea de Pedido</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Observaciones</th>
                            <th>Número de Piezas</th>
                            <th>Nombre Base</th>
                            <th>Medida Inicial</th>
                            <th>Medida Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($procesos as $proceso): ?>
                            <tr>
                                <td><?= $proceso['nombre_proceso'] ?></td>
                                <td><?= $proceso['id_linea_pedido'] ?></td>
                                <td><?= $proceso['nombre_cliente'] ?></td>
                                <td>
                                    <strong><?= $proceso['nombre_producto'] ?></strong><br>
                                    <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto" width="100">
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
            <?php endif; ?>

            <script>
                window.history.replaceState({}, document.title, "<?= base_url('selectMaquina'); ?>");

            </script>