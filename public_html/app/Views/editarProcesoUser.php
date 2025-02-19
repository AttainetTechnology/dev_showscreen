<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<div class="container">
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
