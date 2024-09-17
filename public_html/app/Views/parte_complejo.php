<?php

use App\Models\Usuarios_model;
//comienza el foreach
foreach ($pedido as $ped) { ?>
    <div id="fondo">
        <input type="button" onclick="printDiv('printableArea')" value="Imprimir Parte" class="btn btn-success btn-sm" />

        <div id="printableArea">
            <!-- info row -->
            <div class="fila">
                <div id="fila_left">
                    <img src="<?php
                                helper('logo');
                                $logo = logo();
                                echo $logo; ?>" class="logo_partes"><br>
                    <br>
                    <?php
                    function usuarios()
                    {
                        $datos = new \App\Models\Usuarios2_Model();
                        $data = usuario_sesion();
                        $id_empresa = $data['id_empresa'];
                        $id_usuario = $data['id_user'];

                        // Define los criterios para la consulta a la base de datos
                        $array = ['nivel_acceso' => '1', 'id_empresa' => $id_empresa];
                        $usuarios = $datos->where($array)->findAll();
                        $user_ids = array();
                        foreach ($usuarios as $usuario) {
                            $user_ids[] = $usuario['id'];
                        }

                        $db_cliente = db_connect($data['new_db']);
                        $builder = $db_cliente->table('users');
                        $builder->select('id, nombre_usuario, apellidos_usuario');
                        $builder->where('id', $id_usuario);
                        $builder->where('user_activo', '1');
                        $query = $builder->get();

                        $usuarios = array();
                        if ($query->getNumRows() > 0) {
                            foreach ($query->getResult() as $row) {
                                $usuarios[$row->id] = $row->nombre_usuario . ' ' . $row->apellidos_usuario;
                            }
                        } else {
                            //Si no se encuentra el usuario, se añade 'Test', Es para cuando cambia de empresa un superadmin
                            $usuarios[$id_usuario] = 'Test';
                        }
                        return $usuarios;
                    }
                    $user_ped = usuarios();
                    ?>
                    <b><?php echo $ped->pedido_por; ?></b><br>
                    Id.Ped: <b><?php echo $ped->id_pedido; ?></b> | User: <b><?php echo isset($user_ped[$ped->id_usuario]) ? $user_ped[$ped->id_usuario] : 'test'; ?> </b>
                </div>

                <!-- /.col -->
                <div id="fila_center">
                    Fecha de entrada:<br>
                    <strong><?php echo date("d/m/Y", strtotime($ped->fecha_entrada)); ?></strong><br>
                    Fecha de entrega:<br>
                    <strong><?php echo date("d/m/Y", strtotime($ped->fecha_entrega)); ?></strong><br>
                </div>
                <!-- /.col -->
                <div id="fila_right">
                    Cliente:<br>
                    <strong><?php echo $ped->nombre_cliente; ?></strong><br>
                    Referencia ped:<br>
                    <strong><?php echo $ped->referencia; ?></strong>
                </div>
            </div> <!-- Cierro fila -->
            <div class="tituparte">Parte de trabajo</div>
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
                                <th>Nombre de la base</th>
                                <th>Med. inic.</th>
                                <th>Med. fin.</th>
                                <th>Modelo</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($lineas as $l) { ?>
                                <tr>
                                    <td><b><?php echo $l->n_piezas; ?> </b></td>

                                    <td><b><?php echo $l->nombre_producto; ?> </b></td>
                                    <td><b><?php echo $l->nom_base; ?></b></td>
                                    <td><b><?php echo $l->med_inicial; ?></b></td>
                                    <td><b><?php echo $l->med_final; ?></b></td>
                                    <?php
                                    $session = session();
                                    $session_data = $session->get('logged_in');
                                    $id_empresa = $session_data['id_empresa'];

                                    $id_producto = $l->id_producto; // Asume que $l es el producto actual y tiene una propiedad id_producto
                                    $imagen = isset($l->imagen) ? $l->imagen : 'default.png'; // Asegúrate de que $l->imagen esté definida y tenga un valor

                                    $imagen_producto = base_url('public/assets/uploads/files') . "/$id_empresa/productos/" . $imagen;
                                    ?>
                                    <td><img src="<?php echo $imagen_producto; ?>" style="max-height:60px"></td>

                                </tr>
                                <tr style="background-color: #eee">
                                    <td colspan="2"><?php if ($l->observaciones) {
                                                        echo "ATENCIÓN!:<strong> " . $l->observaciones . "</strong>";
                                                    } ?></td>
                                    <td style="text-align: right" colspan="4">
                                        Buenas:
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Malas:
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Firma:
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                </tr>
                            <?php } // Cierro foreach lineas 
                            ?>

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
        </div> <!-- /#Printable area -->
    </div> <!-- Fondo -->

<?php } // Cierro foreach pedido 
?>