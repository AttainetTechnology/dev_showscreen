<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>?v=<?= time() ?>">
<?php

use App\Models\Usuarios_model;
//comienza el foreach
foreach ($pedido as $ped) { ?>
    <div id="fondo">
        <button onclick="printDiv('printableArea')" class="boton btnImprimir">
            Imprimir Parte
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                <path d="M8.71593 4.72729C8.16741 4.72729 7.64136 4.95853 7.2535 5.37014C6.86564 5.78174 6.64774 6.34 6.64774 6.9221V9.11691H5.61365C5.06514 9.11691 4.53909 9.34814 4.15123 9.75975C3.76337 10.1714 3.54547 10.7296 3.54547 11.3117L3.54547 14.6039C3.54547 15.186 3.76337 15.7443 4.15123 16.1559C4.53909 16.5675 5.06514 16.7987 5.61365 16.7987H6.64774V17.8961C6.64774 18.4782 6.86564 19.0365 7.2535 19.4481C7.64136 19.8597 8.16741 20.0909 8.71593 20.0909H14.9205C15.469 20.0909 15.995 19.8597 16.3829 19.4481C16.7708 19.0365 16.9887 18.4782 16.9887 17.8961V16.7987H18.0227C18.5713 16.7987 19.0973 16.5675 19.4852 16.1559C19.873 15.7443 20.0909 15.186 20.0909 14.6039V11.3117C20.0909 10.7296 19.873 10.1714 19.4852 9.75975C19.0973 9.34814 18.5713 9.11691 18.0227 9.11691H16.9887V6.9221C16.9887 6.34 16.7708 5.78174 16.3829 5.37014C15.995 4.95853 15.469 4.72729 14.9205 4.72729H8.71593ZM7.68184 6.9221C7.68184 6.63105 7.79078 6.35192 7.98471 6.14612C8.17864 5.94032 8.44167 5.8247 8.71593 5.8247H14.9205C15.1947 5.8247 15.4578 5.94032 15.6517 6.14612C15.8456 6.35192 15.9546 6.63105 15.9546 6.9221V9.11691H7.68184V6.9221ZM8.71593 12.4091C8.16741 12.4091 7.64136 12.6404 7.2535 13.052C6.86564 13.4636 6.64774 14.0218 6.64774 14.6039V15.7013H5.61365C5.3394 15.7013 5.07637 15.5857 4.88244 15.3799C4.68851 15.1741 4.57956 14.895 4.57956 14.6039V11.3117C4.57956 11.0207 4.68851 10.7415 4.88244 10.5357C5.07637 10.3299 5.3394 10.2143 5.61365 10.2143H18.0227C18.297 10.2143 18.56 10.3299 18.754 10.5357C18.9479 10.7415 19.0568 11.0207 19.0568 11.3117V14.6039C19.0568 14.895 18.9479 15.1741 18.754 15.3799C18.56 15.5857 18.297 15.7013 18.0227 15.7013H16.9887V14.6039C16.9887 14.0218 16.7708 13.4636 16.3829 13.052C15.995 12.6404 15.469 12.4091 14.9205 12.4091H8.71593ZM15.9546 14.6039V17.8961C15.9546 18.1872 15.8456 18.4663 15.6517 18.6721C15.4578 18.8779 15.1947 18.9935 14.9205 18.9935H8.71593C8.44167 18.9935 8.17864 18.8779 7.98471 18.6721C7.79078 18.4663 7.68184 18.1872 7.68184 17.8961V14.6039C7.68184 14.3129 7.79078 14.0337 7.98471 13.8279C8.17864 13.6221 8.44167 13.5065 8.71593 13.5065H14.9205C15.1947 13.5065 15.4578 13.6221 15.6517 13.8279C15.8456 14.0337 15.9546 14.3129 15.9546 14.6039Z" fill="black" fill-opacity="0.6" />
            </svg>
        </button>
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