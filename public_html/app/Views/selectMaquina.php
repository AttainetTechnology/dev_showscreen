<div class="fondo-empleados">
    <div class="container-fluid d-flex flex-row flex-wrap">
    <h2><?= $usuario['nombre_usuario']; ?> <?= $usuario['apellidos_usuario']; ?></h2>
        <form action="<?= site_url('selectMaquina') ?>" method="POST">
            <label for="maquina">Seleccione una Máquina:</label>
            <select name="id_maquina" id="maquina" required>
                <option value="">Seleccione...</option>
                <?php foreach ($maquinas as $maquina): ?>
                    <option value="<?= $maquina['id_maquina'] ?>"><?= $maquina['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Ver Procesos</button>
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