<?php //print_r($presentes);?>
<?=$cabecera;?>
<body class="page-select" onload="startTime()">
<?=$hora;?>
<div class="fondo-select">
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
            <h4>Editar Datos de Proceso</h4>
            <form action="<?= site_url('editarPiezas') ?>" method="POST">
                <input type="hidden" name="id_relacion_proceso_usuario" value="<?= esc($unidadesIndividuales['id']) ?>">

                <div class="form-group">
                    <label for="buenas">Buenas:</label>
                    <input type="text" id="buenas" name="buenas" class="form-control" value="0" readonly>
                </div>

                <div class="form-group">
                    <label for="malas">Malas:</label>
                    <input type="text" id="malas" name="malas" class="form-control" value="0" readonly>
                </div>

                <div class="form-group">
                    <label for="repasadas">Repasadas:</label>
                    <input type="text" id="repasadas" name="repasadas" class="form-control" value="0" readonly>
                </div>
                <br>
                <div class="col-md-10">
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
        </div>
        <br>

        <div class="col-md-6">
            <div class="calculator">
                <div class="row">
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(1)">1</button>
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(2)">2</button>
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(3)">3</button>
                </div>
                <div class="row">
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(4)">4</button>
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(5)">5</button>
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(6)">6</button>
                </div>
                <div class="row">
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(7)">7</button>
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(8)">8</button>
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(9)">9</button>
                </div>
                <div class="row">
                    <button type="button" class="btnCalculadora btn-danger" onclick="clearInput()">C</button>
                    <button type="button" class="btnCalculadora btn-light" onclick="addNumber(0)">0</button>
                    <button type="button" class="btnCalculadora btn-warning" onclick="deleteLast()">⌫</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="action" value="apuntar_cambios">Apuntar</button>
            <button type="submit" class="btn btn-success" name="action" value="apuntar_terminar">Apuntar y Terminar
                Pedido</button>
            <button type="submit" class="btn btn-warning" name="action" value="apuntar_continuar">Apuntar y Continuar
                Más Tarde</button>
            <br>
            <button type="submit" class="btn btn-warning" name="action" value="falta_material">FALTA DE
                MATERIAL</button>
            </form>
        </div>
    </div>
    <div class="row">

    </div>

    <script>
        let activeField = 'buenas';

        function setActiveField(field) {
            activeField = field;
        }

        function addNumber(num) {
            let input = document.getElementById(activeField);
            if (input.value === '0') {
                input.value = num;
            } else {
                input.value += num;
            }
        }

        function deleteLast() {
            let input = document.getElementById(activeField);
            input.value = input.value.slice(0, -1);
            if (input.value === '') {
                input.value = '0';
            }
        }

        function clearInput() {
            document.getElementById(activeField).value = '0';
        }

        document.getElementById('buenas').addEventListener('focus', () => setActiveField('buenas'));
        document.getElementById('malas').addEventListener('focus', () => setActiveField('malas'));
        document.getElementById('repasadas').addEventListener('focus', () => setActiveField('repasadas'));
    </script>

    <style>
        .calculator {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px;
        }

        .row {
            display: flex;
            justify-content: center;
            margin-bottom: 5px;
        }

        .btnCalculadora {
            width: 60px;
            height: 60px;
            font-size: 20px;
            margin: 3px;
        }
    </style>