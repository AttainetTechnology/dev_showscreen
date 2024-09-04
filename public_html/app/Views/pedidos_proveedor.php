<?php

// Comienza el foreach para cada $pedido
foreach ($pedido as $ped) { ?>
    <div id="fondo">
        <input type="button" onclick="printDiv('printableArea')" value="Imprimir Pedido" class="btn btn-success btn-sm" />

        <div id="printableArea">
            <!-- info row -->
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
                                ?>" class="logo_partes"><br>
                    <br>
                    <?php
                    // Datos del proveedor en lugar del cliente
                    $db_cliente = db_connect($data['new_db']);
                    $builder = $db_cliente->table('proveedores');
                    $builder->select('id_proveedor, nombre_proveedor');
                    $proveedor_data = $builder->get()->getRow();
                    $nombre_proveedor = $proveedor_data->nombre_proveedor;
                    ?>
                </div>
                <div id="fila_right">
                    <div class="capa-numero-parte">
                        <div class="numero_pedido">Id: <strong><?php echo $ped->id_pedido; ?></strong></div>
                    </div>
                    <!-- Mostrar el nombre del proveedor -->
                    <strong>
                        <h3><?php echo $nombre_proveedor; ?></h3>
                    </strong><br>
                    Ref: <strong><?php echo $ped->referencia; ?></strong>
                    <div class="parte-fechas">
                        <div class="f-entrada">Pedido: <?php echo date("d/m/Y", strtotime($ped->fecha_salida)); ?> &nbsp; </div>
                        <div class="f-entrega"> Entrega:
                            <strong><?php echo date("d/m/Y", strtotime($ped->fecha_entrega)); ?></strong>
                        </div>
                    </div>
                </div>
            </div> <!-- Cierro fila -->
            <div class="tituparte">Pedido externo</div>
            <div id="observaciones">
                <div class="seccionparte">Observaciones de pedido:</div>
                <?php echo $ped->observaciones; ?>
            </div>
            <div class="row">
                <div class="col-xs-12 table-responsive" id="tabla_tipopieza">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Unidades</th>
                                <th>Producto</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($lineas as $l) { ?>
                                <tr>
                                    <td><b><?php echo $l->n_piezas; ?> </b></td>

                                    <td><b><?php echo $l->nombre_producto; ?> </b></td>
                                    <td><b><?php echo $l->total_linea; ?></b> &euro;</td>
                                    <?php $total += $l->total_linea; ?>
                                </tr>
                            <?php } // Cierro foreach lineas 
                            ?>

                            <tr id="total_pedido">
                                <td colspan="6">
                                    Total pedido: <b><?php echo $total; ?> &euro;</b><br />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-xs-6">

                </div>
                <!-- /.col -->
                <div class="col-xs-6">

                </div>
            </div>
            <div class="detalles-pie">
                <small>User: <strong><?php echo $nombre_proveedor; ?> </strong> | Impresión: <?php echo ' ' . date('d-m-Y') . "\n"; ?></small><br>
            </div>
        </div> <!-- /#Printable area -->
    </div> <!-- Fondo -->

<?php } // Cierro foreach pedidos 
?>