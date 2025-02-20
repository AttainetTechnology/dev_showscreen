<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<div class="container">
    <br>
    <div class="volver">
        <a href="<?= site_url('selectMaquina/'); ?>" class="btn btn-light">
            <span class="glyphicon glyphicon-arrow-left"></span> Volver
        </a>
    </div>
    <h3>Proceso seleccionado</h3>
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Proceso</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Observaciones</th>
                <th>Número de Piezas</th>
                <th>Nombre Base</th>
                <th>Medida Inicial</th>
                <th>Distancia</th>
                <th>Medida Final</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= esc($proceso['nombre_proceso']) ?></td>
                <td><?= esc($proceso['nombre_cliente']) ?></td>
                <td>
                    <img src="<?= esc($proceso['imagen_producto']) ?>" alt="<?= esc($proceso['nombre_producto']) ?>"
                        style="max-width: 100px;">
                    <br>
                    <?= esc($proceso['nombre_producto']) ?>
                </td>
                <td><?= esc($proceso['observaciones']) ?></td>
                <td><?= esc($proceso['n_piezas']) ?></td>
                <td><?= esc($proceso['nom_base']) ?></td>
                <td><?= esc($proceso['med_inicial']) ?></td>
                <td><?= esc($proceso['distancia']) ?></td>
                <td><?= esc($proceso['med_final']) ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="row">
        <div class="col-md-6">
            <h4>Piezas</h4>
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th></th>
                        <th>Buenas</th>
                        <th>Malas</th>
                        <th>Repasadas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Ultimo</strong></td>
                        <td><?= esc($unidadesIndividuales['buenas']) ?></td>
                        <td><?= esc($unidadesIndividuales['malas']) ?></td>
                        <td><?= esc($unidadesIndividuales['repasadas']) ?></td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td><strong>Totales</strong></td>
                        <td><?= esc($totales['total_buenas']) ?></td>
                        <td><?= esc($totales['total_malas']) ?></td>
                        <td><?= esc($totales['total_repasadas']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h4>Editar Datos de Proceso</h4>
            <form action="<?= site_url('editarPiezas') ?>" method="POST">
                <input type="hidden" name="id_relacion_proceso_usuario" value="<?= esc($unidadesIndividuales['id']) ?>">
                <div class="form-group">
                    <label for="buenas">Buenas:</label>
                    <input type="number" id="buenas" name="buenas" class="form-control" value="0" min="0">
                </div>

                <div class="form-group">
                    <label for="malas">Malas:</label>
                    <input type="number" id="malas" name="malas" class="form-control" value="0" min="0">
                </div>

                <div class="form-group">
                    <label for="repasadas">Repasadas:</label>
                    <input type="number" id="repasadas" name="repasadas" class="form-control" value="0" min="0">
                </div>
                <br>
                <button type="submit" class="btn btn-primary" name="action" value="apuntar_cambios">Apuntar</button>
                <button type="submit" class="btn btn-success" name="action" value="apuntar_terminar">Apuntar y Terminar
                    Pedido</button>
                <button type="submit" class="btn btn-warning" name="action" value="apuntar_continuar">Apuntar y
                    Continuar Más Tarde</button>
            </form>
        </div>

    </div>