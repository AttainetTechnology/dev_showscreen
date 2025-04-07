<?php //print_r($presentes); ?>
<?= $cabecera; ?>

<body class="page-select" onload="startTime()">
    <?= $hora; ?>
    <div class="fondo-select">
        <div class="container">
            <a href="<?= site_url('presentes/'); ?>" class="btn volverButton volverButtonEdit">
                <span class="glyphicon glyphicon-arrow-left"></span> Volver
            </a>
            <table class="procesos table table-responsive">
                <thead class="table-primary">
                    <tr>
                        <th>Proceso</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Observaciones</th>
                        <th>Nº de Piezas</th>
                        <th>Nombre Base</th>
                        <th>Med. Inicial</th>
                        <th>Med. Final</th>
                        <th>Distancia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= esc($proceso['nombre_proceso']) ?></td>
                        <td><?= esc($proceso['nombre_cliente']) ?></td>
                        <td>
                            <img src="<?= esc($proceso['imagen_producto']) ?>"
                                alt="<?= esc($proceso['nombre_producto']) ?>" style="max-width: 100px;">
                            <br>
                            <?= esc($proceso['nombre_producto']) ?>
                        </td>
                        <td><?= esc($proceso['observaciones']) ?></td>
                        <td><?= esc($proceso['n_piezas']) ?></td>
                        <td><?= esc($proceso['nom_base']) ?></td>
                        <td><?= esc($proceso['med_inicial']) ?></td>
                        <td><?= esc($proceso['med_final']) ?></td>
                        <td><?= esc($proceso['distancia']) ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-6">
                    <h3>Añadir piezas</h3>
                    <form action="<?= site_url('editarPiezas') ?>" method="POST">
                        <input type="hidden" name="id_relacion_proceso_usuario"
                            value="<?= esc($unidadesIndividuales['id']) ?>">

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
                        <div class="col-md-10 col10 ">
                            <h3>Piezas</h3>
                            <table class="procesos table table-bordered">
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
                            <div class="volver">

                            </div>
                        </div>

                </div>
                <br>

                <div class="col-md-6">
                    <div class="calculator">
                        <div class="row">
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(1)">1</button>
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(2)">2</button>
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(3)">3</button>
                        </div>
                        <div class="row">
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(4)">4</button>
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(5)">5</button>
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(6)">6</button>
                        </div>
                        <div class="row">
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(7)">7</button>
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(8)">8</button>
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(9)">9</button>
                        </div>
                        <div class="row">
                            <button type="button" class="btnCalculadora btn-danger" onclick="clearInput()">C</button>
                            <button type="button" class="btnCalculadora btnNumero" onclick="addNumber(0)">0</button>
                            <button type="button" class="btnCalculadora btn-warning" onclick="deleteLast()">⌫</button>
                        </div>
                    </div>

                    <div class="butons">
                        <button type="submit" class="btn btnApuntarGenerico bntApuntar" name="action"
                            value="apuntar_cambios">Apuntar</button>
                            <button type="submit" class="btn btnApuntarGenerico btnTerminarPedido" name="action"
                            value="apuntar_terminar">Apuntar y
                            terminar
                            pedido</button>
                        <button type="submit" class="btn btnApuntarGenerico btnSalir" name="action"
                            value="apuntar_continuar">Salir sin terminar</button>
                        <button type="submit" class="btn btnApuntarGenerico btnFaltaMaterial" name="action"
                            value="falta_material">FALTA DE
                            MATERIAL</button>
                    </div>
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
                    window.onmousedown = resetTemporizador;  //interacción táctil/teclado
                    window.ontouchstart = resetTemporizador;
                    window.onclick = resetTemporizador;     //clics
                    window.onkeypress = resetTemporizador;
                    window.addEventListener('scroll', resetTemporizador, true);  //scroll

                })();
</script>
