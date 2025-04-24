<style>
    #printableArea {
        font-size: 30px !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>?v=<?= time() ?>">
<?php
// Comienza el foreach		
foreach ($pedido as $ped) { ?>
    <div id="fondo">
        <button onclick="printPedido(<?= $ped->id_pedido ?>)" class="boton btnImprimir">
            Imprimir Pedido
        </button>
        <div id="printableArea">
            <!-- Primera página -->
            <div class="fila">
                <div id="fila_left">
                    <?php
                    $data = datos_user();
                    $logo = $data['url_logo'];
                    $user_ped = $ped->pedido_por;
                    ?>
                    <img src="<?php
                    $session = session();
                    $session_data = $session->get('logged_in');
                    $id_empresa = $session_data['id_empresa'];
                    echo base_url('public/assets/uploads/files/' . $logo);
                    ?>" class="logo_partes"><br><br>
                </div>
                <div id="fila_right">
                    <div class="capa-numero-parte">
                        <div class="numero_pedido" style="background-color: #eee"><strong>ID PED: <?php echo $ped->id_pedido; ?></strong></div>
                    </div>
                    <strong>
                        <h3><?php echo $ped->nombre_cliente; ?></h3>
                    </strong><br>
                    Ref: <strong><?php echo $ped->referencia; ?></strong>
                    <div class="parte-fechas">
                        <div class="f-entrada">Entrada: <?php echo date("d/m/Y", strtotime($ped->fecha_entrada)); ?> &nbsp;
                        </div>
                        <div class="f-entrega"> Entrega:
                            <strong><?php echo date("d/m/Y", strtotime($ped->fecha_entrega)); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tituparte">Pedido interno</div>
            <div id="observaciones" style="font-size:24px !important;">
                <div class="seccionparte">Observaciones de pedido:</div>
                <?php echo $ped->observaciones; ?>
            </div>

            <div class="row">
                <div class="col-xs-12 table-responsive" id="tabla_tipopieza">
                    <table class="table" style=" font-size:20px !important;">
                        <thead>
                            <tr>
                                <th>Unidades</th>
                                <th>Producto</th>
                                <th>Nombre de la base</th>
                                <th>Med. inic.</th>
                                <th>Med. fin.</th>
                               <!-- <th>Total</th>-->
                            </tr>
                        </thead>
                        <tbody style=" font-size:40px !important;">
                            <?php $total = 0; ?>
                            <?php
                            $lineas_pag1 = array_slice($lineas, 0, 12); // Dividir correctamente las líneas para la primera página
                            foreach ($lineas_pag1 as $l) { ?>
                                <tr>
                                    <td style=" font-size:20px !important;"><b><?php echo $l->n_piezas; ?> </b></td>
                                    <td style=" font-size:20px !important;"><b><?php echo $l->nombre_producto; ?> </b></td>
                                    <td style=" font-size:20px !important;"><b><?php echo $l->nom_base; ?></b></td>
                                    <td style=" font-size:20px !important;"><b><?php echo $l->med_inicial; ?></b></td>
                                    <td style=" font-size:20px !important;"><b><?php echo $l->med_final; ?></b></td>
                                    <!--<td style=" font-size:20px !important;"><b><?php echo $l->total_linea; ?></b> &euro;</td>-->
                                    <?php $total += $l->total_linea; ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (count($lineas) > 12) { ?>
                <div class="page-break"></div> <!-- Fuerza un salto de página -->

                <!-- Segunda página - Tabla para los registros restantes -->
                <div class="row">
                    <div class="col-xs-12 table-responsive" id="tabla_tipopieza_2">
                        <table class="table" style="font-size:16px !important;">
                            <thead>
                                <tr>
                                    <th>Unidades</th>
                                    <th>Producto</th>
                                    <th>Nombre de la base</th>
                                    <th>Med. inic.</th>
                                    <th>Med. fin.</th>
                                    <!--<th>Total</th>-->
                                </tr>
                            </thead>
                            <tbody style=" font-size:40px !important;">
                                <?php
                                $lineas_pag2 = array_slice($lineas, 12); // Líneas para la segunda página
                                foreach ($lineas_pag2 as $l) { ?>
                                    <tr>
                                        <td style=" font-size:20px !important;"><b><?php echo $l->n_piezas; ?> </b></td>
                                        <td style=" font-size:20px !important;"><b><?php echo $l->nombre_producto; ?> </b></td>
                                        <td style=" font-size:20px !important;"><b><?php echo $l->nom_base; ?></b></td>
                                        <td style=" font-size:20px !important;"><b><?php echo $l->med_inicial; ?></b></td>
                                        <td style=" font-size:20px !important;"><b><?php echo $l->med_final; ?></b></td>
                                        <!--<td style=" font-size:20px !important;"><b><?php echo $l->total_linea; ?></b> &euro;</td>-->
                                        <?php $total += $l->total_linea; ?>
                                    </tr>
                                <?php } ?>
                                <tr id="total_pedido">
                                    <td colspan="6">
                                       <!-- Total pedido: <b><?php echo $total; ?> &euro;</b><br />-->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>

            <!-- Pie de página -->
            <div class="detalles-pie">
                <small>Creado por: <strong><?php echo $user_ped; ?> </strong> | 
                Imprime: <strong><?php echo $nombre_usuario; ?>  <?php echo $apellidos_usuario; ?> (<?php echo ' ' . date('d-m-Y') . "\n"; ?>)</small>
            </div>
        </div>
    </div>

<?php } ?>

<script>
    function printPedido(idPedido) {
        // Realiza la solicitud AJAX para actualizar bt_imprimir
        fetch('<?= base_url("pedidos/updateBtImprimir") ?>/' + idPedido, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ bt_imprimir: 2 }) // Actualiza bt_imprimir a 2
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('El pedido se ha marcado como "Impreso".');
                printDiv('printableArea'); // Llama a la función para imprimir
            } else {
                alert('Error al actualizar el estado de impresión: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al procesar la solicitud.');
        });
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>