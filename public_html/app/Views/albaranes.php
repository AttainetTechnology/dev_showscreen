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
            Imprimir Albarán
        </button>
        <div id="printableArea" style="min-height: 1500px; max-width: 1240px; margin: 0 auto; background-color: #fff; padding: 20px;">
            <!-- Primera página -->
            <style>
            #cabecera {
                line-height: 1.2;
                font-size: 18px;
            }
            #cabecera strong {
                font-size: unset!Important;
            }
            </style>
            <div style="display: flex;flex-direction: row;height: 280px;margin-bottom: 20px;" id="cabecera">
                <!-- Columna izquierda -->
                <div style="flex: 1; margin: 2px 0;margin-right: 40px;">   
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
                ?>" class="logo_partes"><br>
                    <div style="margin-top: 11px;">
                    <?php
                    if ($ped->emp_alb == 1) {
                        echo "<strong>ATTAINET TECHNOLOGY S.L.</strong><BR>B12987343<br>
                        C/ de les Boqueres, 111b, 12550 <BR> Almazora, Castellón de la Plana, ESPAÑA<br>
                        Tel: 964 095 117";
                    } else {
                        echo "<strong>ASISTENCIA CDC</strong><BR>B12884219<br>C/ Barranc de Rátils 8-10, 12200 <BR> Onda, Castellón de la Plana<br>España<br>
                        Tel: 964 095 117";
                    }
                    ?>
                    </div>
                </div>
                <!-- Columna central -->
                <div style="flex: 1; margin-top: 84px;marging-right: 40px;">
                    <strong><?php echo $ped->nombre_cliente; ?></strong><br>
                    <?php echo isset($cliente['nif']) ? esc($cliente['nif']) : ''; ?><br>
                    <?php echo isset($cliente['direccion']) ? esc($cliente['direccion']) : ''; ?><br>
                    <?php echo isset($cliente['provincia']) ? esc($cliente['provincia']) : ''; ?><br>
                    <?php echo isset($cliente['pais']) ? esc($cliente['nombre_pais']) : ''; ?><br>
                    </div>
                    <!-- Columna derecha -->
                    <div style="flex: 1; margin: 2px 0; text-align:right"><h1>ALBARÁN</h1>
                        <div style="margin-top: 33px;">
                            <div style="background: #000; color: #fff; padding: 20px; font-weight: bold; font-size: 20px; width: 200px; margin-left: auto; text-align: right;margin-bottom: 20px;">
                                Id Pedido: <?php echo $ped->id_pedido; ?><br>
                            </div>
                            Nº Alb: <strong><?php echo $ped->albaran; ?></strong><br>
                            Fecha alb: <strong><?php echo ' ' . date('d-m-Y') . "\n"; ?></strong><br>
                            Referencia: <strong><?php echo $ped->referencia; ?></strong><br>
                        </div>
                </div>
            </div>

            <!-- Mostrar las líneas del pedido -->
            <div class="row">
            <div class="col-xs-12 table-responsive" id="tabla_tipopieza">
                <table class="table" style="font-size:20px !important;">
                <thead>
                    <tr>
                    <th>Unidades</th>
                    <th>Producto</th>
                    <th>Nombre de la base</th>
                    <th>Med. inic.</th>
                    <th>Med. fin.</th>
                    </tr>
                </thead>
                <tbody style="font-size:40px !important;">
                    <?php foreach ($lineas as $l) : ?>
                    <tr>
                        <td style="font-size:20px !important;"><b><?= esc($l->ultimo_fichaje) ?></b></td>
                        <td style="font-size:20px !important;"><b><?= esc($l->nombre_producto) ?></b></td>
                        <td style="font-size:20px !important;"><b><?= esc($l->nom_base) ?></b></td>
                        <td style="font-size:20px !important;"><b><?= esc($l->med_inicial) ?></b></td>
                        <td style="font-size:20px !important;"><b><?= esc($l->med_final) ?></b></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
            <div>
                    <div style="font-size:16px; margin-top:20px;">
                        <strong>Observaciones:</strong>
                        <?php if (!empty($ped->obs_alb)): ?><br> <?= esc($ped->obs_alb) ?><br><?php endif; ?><br><br>
                    </div>
            </div>
            </div>
            <br>
            <div style="text-align: center; width: 100%; font-size:16px;">
                <?php
                    echo esc($mensaje);
                ?>
            </div>

            <!-- Pie de página -->
            <style>
            @media print {
                .pie-albaran {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    font-size: 10px;
                    text-align: center;
                    background: #fff;
                    z-index: 9999;
                    padding-bottom: 10px;
                }
            }
            .pie-albaran,
            .pie-albaran strong {
                font-size: 10px;
                text-align: center;
            }
            .pie-albaran h4 {
                font-size: 18px!Important;
            }
            </style>
            <script>
            // Mantener .pie-albaran siempre en el fondo de #printableArea
            document.addEventListener("DOMContentLoaded", function() {
                var printableArea = document.getElementById('printableArea');
                var pie = printableArea.querySelector('.pie-albaran');
                if (printableArea && pie) {
                    printableArea.style.position = 'relative';
                    pie.style.position = 'absolute';
                    pie.style.left = 0;
                    pie.style.bottom = 0;
                    pie.style.width = '100%';
                }
            });
            </script>
            <div class="pie-albaran"><br><br>
                <div><hr>
                <h4>www.offertiles.com</h4>
                OFICINA TÉCNICA Y EXPOSICIÓN +34 964 095 117 | C/ de les Boqueres, 111b, 12550 - Almazora, Castellón de la Plana, ESPAÑA<br>
                ALMACÉN DE CARGAS +34 689 20 44 94 | C/ Barranc de Rátils 8-10, 12200 - Onda, Castellón de la Plana, ESPAÑA.
            <br>Creado por: <strong><?php echo $user_ped; ?> </strong> | Imprime: <strong><?php echo $nombre_usuario; ?>  <?php echo $apellidos_usuario; ?> (<?php echo ' ' . date('d-m-Y') . "\n"; ?>)
                </div>
            </div>
         </div>


<?php } ?>

<script>
    function printPedido(idPedido) {
        // Realiza la solicitud AJAX para actualizar estado_alb a 1
        fetch('<?= base_url("pedidos/updateEstadoAlb") ?>/' + idPedido, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ estado_alb: 1 }) 
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('El albarán se ha marcado como "Impreso".');
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