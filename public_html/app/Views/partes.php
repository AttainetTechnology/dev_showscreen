<?php foreach ($lineas as $l) { ?>
    <?php foreach ($productos as $prod) { ?>
        <?php foreach ($pedidos as $p) { ?>
            <?php foreach ($clientes as $cli) { ?>
                <div id="fondo">
                    <?php
                    if (isset($_GET['pg2'])) {
                        $volver = $_GET['pg2'];
                    }
                    ?>
                    <a href="<? echo $volver; ?>" class="btn btn-warning btn-sm">Cerrar</a>

                    <a href="javascript:void(0);" onclick="verificarYMarcarLinea(<?php echo $l->id_lineapedido; ?>, '<?php echo $volver; ?>');" class="btn btn-info btn-sm">Cerrar y marcar línea como recibida</a>

                    <script>
                        function verificarYMarcarLinea(id_lineapedido, volver) {
                            fetch('<?php echo base_url(); ?>/Partes_controller/verificarEstadoProcesos/' + id_lineapedido)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'error') {
                                        alert(data.message);
                                    } else {
                                        window.location.href = '<?php echo base_url(); ?>/Partes_controller/CambiaEstado/' + id_lineapedido + '?volver=' + volver;
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    </script>

                    <input type="button" onclick="printDiv('printableArea')" value="Imprimir Parte" class="btn btn-success btn-sm" />

                    <div id="printableArea">
                        <!-- Cabecera -->
                        <div class="row">
                            <div id="parte_fila_left">
                                <img src="<?php echo base_url("public/assets/uploads/logo/") . "/" . $url_logo; ?>" class="logo_partes"><br>
                                Cliente:
                                <address>
                                    <strong><? echo $cli->nombre_cliente; ?></strong>
                                </address>
                                Referencia ped:
                                <address>
                                    <strong><? echo $p->referencia; ?></strong>
                                </address>
                            </div>
                            <div id="parte_fila_right" class="imagenparte">
                                <div class="capa-numero-parte">
                                    <div class="numero_parte">Id: <strong><? echo $p->id_pedido; ?></strong></div>
                                </div>
                                <h3><b><? echo $prod->nombre_producto; ?></b> </h3>
                                <div class="parte-fechas">
                                    <div class="f-entrada">Entrada: <? echo date("d-m-Y", strtotime($p->fecha_entrada)); ?> &nbsp; </div>
                                    <div class="f-entrega"> Entrega:
                                        <strong><? echo date("d-m-Y", strtotime($p->fecha_entrega)); ?></strong>
                                    </div>
                                </div>
                                <img src="<?php echo base_url("public/assets/uploads/files/"); ?>/<? echo $prod->imagen; ?>" class="imagen_parte" /><br />
                            </div>
                            <!-- END Cabecera -->
                        </div><!-- /.row -->
                        <!-- Table row -->
                        <div class="row">
                            <div class="col-xs-12 table-responsive" id="tabla_tipopieza">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Unidades</th>
                                            <th>Producto</th>
                                            <th>Nombre de la base</th>
                                            <th>Med. inic.</th>
                                            <th>Med. fin.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b><? echo $l->n_piezas; ?></b></td>
                                            <td><b><? echo $prod->nombre_producto; ?></b></td>
                                            <td><b><? echo $l->nom_base; ?></b></td>
                                            <td><b> <? echo $l->med_inicial; ?></b></td>
                                            <td><b> <? echo $l->med_final; ?></b></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="observaciones">
                                    <?php if ($l->lado != "") { ?>
                                        Lado a mecanizar: <strong><? echo $l->lado; ?></strong><br>
                                    <?php } ?>
                                    <?php if ($l->distancia != "") { ?>
                                        Distancia de las ranuras: <strong><? echo $l->distancia; ?> cm.</strong><br><br>
                                    <?php } ?>
                                    <?php if ($l->observaciones != "") { ?>
                                        Observaciones: <strong><? echo $l->observaciones; ?></strong>

                                    <?php } ?>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-xs-12 table-responsive">
                                <?php
                                $i = '1';
                                foreach ($procesos as $proc) { ?>
                                    <?php if ($i == '7') { ?><div class="detalles-pie">
                                            <small>L.P: <strong><?php echo $l->id_lineapedido; ?></strong> | User: <strong><?php echo $nombre_usuario; ?> <?php echo $apellidos_usuario; ?></strong> | Impresi&oacute;n: <?php echo ' ' . date('d-m-Y') . "\n"; ?></small><br>
                                        </div>
                                        <div class="pagina1"></div>
                                        <div class="pagina2"></div>
                                        <!-- Repetimos la cabecera para la segunda pagina -->
                                        <!-- Cabecera -->
                                        <div class="row">
                                            <div id="parte_fila_left">
                                                <img src="<?php echo base_url("public/assets/uploads/logo/") . "/" . $url_logo; ?>" class="logo_partes"><br>
                                                Cliente:
                                                <address>
                                                    <strong><?php echo $cli->nombre_cliente; ?></strong>
                                                </address>
                                                Referencia ped:
                                                <address>
                                                    <strong><?php echo $p->referencia; ?></strong>
                                                </address>
                                            </div>
                                            <div id="parte_fila_right" class="imagenparte">
                                                <div class="capa-numero-parte">
                                                    <div class="numero_parte">Parte T. <strong><?php echo $p->id_pedido; ?></strong></div>
                                                    <H2>Hoja 2 &nbsp;</H2>
                                                </div>
                                                <h3><b><?php echo $prod->nombre_producto; ?></b> </h3>
                                                <div class="parte-fechas">
                                                    <div class="f-entrada">Entrada: <?php echo date("d-m-Y", strtotime($p->fecha_entrada)); ?> &nbsp; </div>
                                                    <div class="f-entrega"> Entrega:
                                                        <strong><?php echo date("d-m-Y", strtotime($p->fecha_entrega)); ?></strong>
                                                    </div>
                                                </div>
                                                <img src="<?php echo base_url("public/assets/uploads/files/"); ?>/<?php echo $prod->imagen; ?>" class="imagen_parte" /><br />
                                            </div>
                                            <!-- END Cabecera -->

                                            <div class="row"> <!-- DIV ROW-->
                                                <div class="col-xs-12 table-responsive" id="tabla_tipopieza"> <!-- DIV COL XS 12-->
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Unidades</th>
                                                                <th>Producto</th>
                                                                <th>Nombre de la base</th>
                                                                <th>Med. inic.</th>
                                                                <th>Med. fin.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><b><?php echo $l->n_piezas; ?></b></td>
                                                                <td><b><?php echo $prod->nombre_producto; ?></b></td>
                                                                <td><b><?php echo $l->nom_base; ?></b></td>
                                                                <td><b> <?php echo $l->med_inicial; ?></b></td>
                                                                <td><b> <?php echo $l->med_final; ?></b></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div id="observaciones">
                                                        <?php if ($l->lado != "") { ?>
                                                            Lado a mecanizar: <strong><?php echo $l->lado; ?></strong><br>
                                                        <?php } ?>
                                                        <?php if ($l->distancia != "") { ?>
                                                            Distancia de las ranuras: <strong><?php echo $l->distancia; ?> cm.</strong><br>
                                                        <?php } ?>
                                                        <br>
                                                        <?php if ($l->observaciones != "") { ?>
                                                            Observaciones: <strong><?php echo $l->observaciones; ?></strong>
                                                        <?php } ?>
                                                    </div><!-- DIV FILA OBSERVACIONES -->
                                                </div> <!-- DIV COL XS 12-->
                                            </div> <!-- DIV ROW-->

                                        </div> <!-- CIERRO DIV LIBRE -->

                                        <!-- Fin Repetimos la cabecera para la segunda pagina -->

                                    <?php } ?>
                                    <div class="linea_proceso">
                                        <table class="tabla_proceso">
                                            <tr>
                                                <td><b><? echo $proc->nombre_proceso ?> </b><br><br>Operario/s:</td>
                                                <td>
                                                    <table class="casillas">
                                                        <tr>
                                                            <td class="cabecera_tabla">Fecha</td>
                                                            <td class="cabecera_tabla">Hora ini.</td>
                                                            <td class="cabecera_tabla">Hora fin.</td>
                                                            <td class="cabecera_tabla"><b>Buenas</b></td>
                                                            <td class="cabecera_tabla"><b>Malas</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="casillas"></td>
                                                            <td class="casillas"></td>
                                                            <td class="casillas"></td>
                                                            <td class="casillas"></td>
                                                            <td class="casillas"></td>
                                                        </tr>
                                                    </table>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <?php $i += '1'; ?>

                                <?php } //Cierro foreach proceso 
                                ?>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="pie_de_parte">
                            <div class="incidencia">Ha habido alguna incidencia?</div>
                            <div class="revisado">Revisado por:</div>
                            <div class="total_buenas"><b>TOTALES BUENAS:</b>
                                <div class="total">&nbsp;&nbsp;&nbsp;&nbsp;</div>
                            </div>
                        </div> <!-- Cierro el pie del parte -->
                        <?php if ((isset($mas_de_una_linea)) and ($mas_de_una_linea != "")) { ?>
                            <div class="pedido-completo-pie">
                                <h3><b>Atenci&oacute;n!</b></h3> Este parte forma parte de un pedido con otros partes de trabajo. Comprueba si hay que utilizar el material sobrante con otro parte de trabajo:<br>
                                <?php foreach ($mas_de_una_linea as $mas) { ?>

                                    - <?php echo $mas->n_piezas; ?> <?php echo $mas->nombre_producto; ?> - Nom. base: <B><?php echo $mas->nom_base; ?></B> <br>
                                <?php    }    ?>
                            </div>
                        <?php } ?>
                        <div class="detalles-pie">
                            <small>L.P: <strong><?php echo $l->id_lineapedido; ?></strong> | User: <strong><?php echo $nombre_usuario; ?> <?php echo $apellidos_usuario; ?></strong> | Impresi&oacute;n: <? echo ' ' . date('d-m-Y') . "\n"; ?></small><br>
                        </div>
                    </div>
                    <!-- /#Printable area -->
                </div> <!-- Fondo -->

<?php
            } // Cierro foreach clientes
        } //Cierro foreach productos	
    }
}
?>