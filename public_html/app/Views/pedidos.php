<style>
    #printableArea {
        font-size: 25px !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>?v=<?= time() ?>">
<?php
// Comienza el foreach		
foreach ($pedido as $ped) { ?>
    <div id="fondo">
        <button onclick="printDiv('printableArea')" class="boton btnImprimir">
            Imprimir Pedido
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                <path d="M8.71593 4.72729C8.16741 4.72729 7.64136 4.95853 7.2535 5.37014C6.86564 5.78174 6.64774 6.34 6.64774 6.9221V9.11691H5.61365C5.06514 9.11691 4.53909 9.34814 4.15123 9.75975C3.76337 10.1714 3.54547 10.7296 3.54547 11.3117L3.54547 14.6039C3.54547 15.186 3.76337 15.7443 4.15123 16.1559C4.53909 16.5675 5.06514 16.7987 5.61365 16.7987H6.64774V17.8961C6.64774 18.4782 6.86564 19.0365 7.2535 19.4481C7.64136 19.8597 8.16741 20.0909 8.71593 20.0909H14.9205C15.469 20.0909 15.995 19.8597 16.3829 19.4481C16.7708 19.0365 16.9887 18.4782 16.9887 17.8961V16.7987H18.0227C18.5713 16.7987 19.0973 16.5675 19.4852 16.1559C19.873 15.7443 20.0909 15.186 20.0909 14.6039V11.3117C20.0909 10.7296 19.873 10.1714 19.4852 9.75975C19.0973 9.34814 18.5713 9.11691 18.0227 9.11691H16.9887V6.9221C16.9887 6.34 16.7708 5.78174 16.3829 5.37014C15.995 4.95853 15.469 4.72729 14.9205 4.72729H8.71593ZM7.68184 6.9221C7.68184 6.63105 7.79078 6.35192 7.98471 6.14612C8.17864 5.94032 8.44167 5.8247 8.71593 5.8247H14.9205C15.1947 5.8247 15.4578 5.94032 15.6517 6.14612C15.8456 6.35192 15.9546 6.63105 15.9546 6.9221V9.11691H7.68184V6.9221ZM8.71593 12.4091C8.16741 12.4091 7.64136 12.6404 7.2535 13.052C6.86564 13.4636 6.64774 14.0218 6.64774 14.6039V15.7013H5.61365C5.3394 15.7013 5.07637 15.5857 4.88244 15.3799C4.68851 15.1741 4.57956 14.895 4.57956 14.6039V11.3117C4.57956 11.0207 4.68851 10.7415 4.88244 10.5357C5.07637 10.3299 5.3394 10.2143 5.61365 10.2143H18.0227C18.297 10.2143 18.56 10.3299 18.754 10.5357C18.9479 10.7415 19.0568 11.0207 19.0568 11.3117V14.6039C19.0568 14.895 18.9479 15.1741 18.754 15.3799C18.56 15.5857 18.297 15.7013 18.0227 15.7013H16.9887V14.6039C16.9887 14.0218 16.7708 13.4636 16.3829 13.052C15.995 12.6404 15.469 12.4091 14.9205 12.4091H8.71593ZM15.9546 14.6039V17.8961C15.9546 18.1872 15.8456 18.4663 15.6517 18.6721C15.4578 18.8779 15.1947 18.9935 14.9205 18.9935H8.71593C8.44167 18.9935 8.17864 18.8779 7.98471 18.6721C7.79078 18.4663 7.68184 18.1872 7.68184 17.8961V14.6039C7.68184 14.3129 7.79078 14.0337 7.98471 13.8279C8.17864 13.6221 8.44167 13.5065 8.71593 13.5065H14.9205C15.1947 13.5065 15.4578 13.6221 15.6517 13.8279C15.8456 14.0337 15.9546 14.3129 15.9546 14.6039Z" fill="black" fill-opacity="0.6" />
            </svg>
        </button>
        <div id="printableArea">
            <!-- Primera página -->
            <div class="fila">
                <div id="fila_left">
                    <?php
                    $data = datos_user();
                    $logo = $data['url_logo'];
                    ?>
                    <img src="<?php
                                $session = session();
                                $session_data = $session->get('logged_in');
                                $id_empresa = $session_data['id_empresa'];
                                echo base_url('public/assets/uploads/files/' . $logo);
                                ?>" class="logo_partes"><br><br>
                    <?php
                    $db_cliente = db_connect($data['new_db']);
                    $builder = $db_cliente->table('users');
                    $builder->select('id, nombre_usuario, apellidos_usuario');
                    $user_ped = $nombre_usuario . " " . $apellidos_usuario;
                    ?>
                </div>
                <div id="fila_right">
                    <div class="capa-numero-parte">
                        <div class="numero_pedido">Id: <strong><?php echo $ped->id_pedido; ?></strong></div>
                    </div>
                    <strong>
                        <h3><?php echo $ped->nombre_cliente; ?></h3>
                    </strong><br>
                    Ref: <strong><?php echo $ped->referencia; ?></strong>
                    <div class="parte-fechas">
                        <div class="f-entrada">Entrada: <?php echo date("d/m/Y", strtotime($ped->fecha_entrada)); ?> &nbsp; </div>
                        <div class="f-entrega"> Entrega:
                            <strong><?php echo date("d/m/Y", strtotime($ped->fecha_entrega)); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tituparte">Pedido interno</div>
            <div id="observaciones">
                <div class="seccionparte">Observaciones de pedido:</div>
                <?php echo $ped->observaciones; ?>
            </div>

            <div class="row">
                <div class="col-xs-12 table-responsive" id="tabla_tipopieza">
                    <table class="table" style=" font-size:16px !important;">
                        <thead>
                            <tr>
                                <th>Unidades</th>
                                <th>Producto</th>
                                <th>Nombre de la base</th>
                                <th>Med. inic.</th>
                                <th>Med. fin.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody style=" font-size:35px !important;">
                            <?php $total = 0; ?>
                            <?php
                            $lineas_pag1 = array_slice($lineas, 0);
                            foreach ($lineas_pag1 as $l) { ?>
                                <tr>
                                    <td style=" font-size:16px !important;"><b><?php echo $l->n_piezas; ?> </b></td>
                                    <td style=" font-size:16px !important;"><b><?php echo $l->nombre_producto; ?> </b></td>
                                    <td style=" font-size:16px !important;"><b><?php echo $l->nom_base; ?></b></td>
                                    <td style=" font-size:16px !important;"><b><?php echo $l->med_inicial; ?></b></td>
                                    <td style=" font-size:16px !important;"><b><?php echo $l->med_final; ?></b></td>
                                    <td style=" font-size:16px !important;"><b><?php echo $l->total_linea; ?></b> &euro;</td>
                                    <?php $total += $l->total_linea; ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (count($lineas) > 9) { ?>
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
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody style=" font-size:35px !important;">
                                <?php
                                $lineas_pag2 = array_slice($lineas, 9); // Registros desde el noveno en adelante
                                foreach ($lineas_pag2 as $l) { ?>
                                    <tr>
                                        <td style=" font-size:16px !important;"><b><?php echo $l->n_piezas; ?> </b></td>
                                        <td style=" font-size:16px !important;"><b><?php echo $l->nombre_producto; ?> </b></td>
                                        <td style=" font-size:16px !important;"><b><?php echo $l->nom_base; ?></b></td>
                                        <td style=" font-size:16px !important;"><b><?php echo $l->med_inicial; ?></b></td>
                                        <td style=" font-size:16px !important;"><b><?php echo $l->med_final; ?></b></td>
                                        <td style=" font-size:16px !important;"><b><?php echo $l->total_linea; ?></b> &euro;</td>
                                        <?php $total += $l->total_linea; ?>
                                    </tr>
                                <?php } ?>
                                <tr id="total_pedido">
                                    <td colspan="6">
                                        Total pedido: <b><?php echo $total; ?> &euro;</b><br />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>

            <!-- Pie de página -->
            <div class="detalles-pie">
                <small>User: <strong><?php echo $user_ped; ?> </strong> | Impresión: <?php echo ' ' . date('d-m-Y') . "\n"; ?></small><br>
            </div>
        </div>
    </div>

<?php } ?>