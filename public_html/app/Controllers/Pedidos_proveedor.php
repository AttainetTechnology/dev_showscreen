<?php

namespace App\Controllers;

use App\Models\ProveedoresModel;
use App\Models\PedidosProveedorModel;
use App\Models\LineaPedidoModel;

class Pedidos_proveedor extends BaseControllerGC
{
    protected $idpedido = 0;

    function __construct()
    {
        $this->idpedido = 0;
    }

    public function index()
    {
        $this->todos('estado!=', '6');
    }

    public function pendientesRealizar()
    {
        $this->todos('estado=', '0');
    }

    public function pendientesRecibir()
    {
        $this->todos('estado=', '1');
    }

    public function recibidos()
    {
        $this->todos('estado=', '2');
    }
    //CREAMOS LA PAGINA DE PEDIDOS

    public function todos($coge_estado, $where_estado)
    {

        //Control de login	
        helper('controlacceso');
        $nivel = control_login();
        //Fin Control de Login


        $crud = $this->_getClientDatabase();

        $crud->setSubject('Pedido', 'Pedidos');
        $crud->where($coge_estado . $where_estado);
        $crud->setTable('pedidos_proveedor');
        $crud->defaultOrdering('fecha_salida', 'desc');
        $crud->defaultOrdering('id_pedido', 'desc');
        $crud->requiredFields(['id_proveedor']);

        //RELACIONES
        $crud->setRelation('id_proveedor', 'proveedores', 'nombre_proveedor');
        // $crud->setRelation('id_usuario', 'users', 'nombre_usuario');
        //$crud->setRelation('estado', 'Estados', 'nombre_estado');
        $crud->fieldType('estado', 'dropdown_search', [
            "0" => "Pendiente de realizar",
            "1" => "Pendiente de recibir",
            "2" => "Recibido",
            "6" => "Anulado"
        ]);
        //DISPLAY AS
        $crud->displayAs('bt_imprimir', '');
        $crud->displayAs('id_pedido', 'Id pedido');
        $crud->displayAs('id_proveedor', 'Empresa');
        $crud->displayAs('id_usuario', 'Hace el pedido');

        //VISTAS
        $crud->columns(['id_pedido', 'fecha_salida', 'id_proveedor', 'referencia', 'estado', 'fecha_entrega', 'id_usuario', 'total_pedido']);
        $crud->addFields(['id_proveedor', 'referencia', 'id_usuario', 'fecha_salida', 'fecha_entrega', 'observaciones']);
        $crud->editFields(['bt_imprimir', 'id_proveedor', 'referencia', 'observaciones', 'fecha_salida', 'fecha_entrega', 'id_pedido', 'detalles']);
        $crud->displayAs('bt_imprimir', " ");

        //ACCIONES
        $crud->setActionButton('Imprimir', 'fa fa-print', function ($row) {
            $uri = service('uri');
            $uri = current_url(true);
            $pg2 = urlencode($uri);
            $link = base_url('pedidos_proveedor/print/') . '/' . $row->id_pedido . '?volver=' . $pg2;
            return $link;
        }, true);

        //UNSETS

        $crud->unsetRead();

        //CALLBACKS

        $crud->callbackColumn('total_pedido', function ($value, $row) {
            return "<div class='estado" . $row->estado . "'><strong>" . $value . "€</strong></div>";
        });
        //$crud->callbackEditField('bt_imprimir', array($this, 'boton_imprimir'));

        $crud->callbackEditField('bt_imprimir', function ($fieldValue, $primaryKeyValue, $rowData) {
            $id_pedido = $rowData->id_pedido;
            $id_proveedor = $rowData->id_proveedor;
            // Esta función carga todos los botones
            return '
            <input type="hidden" name="bt_imprimir" value="">
            <a href="' . base_url('pedidos_proveedor/print/' . $id_pedido) . '" class="btn btn-info btn-sm" target="_blanck">
                <i class="fa fa-print fa-fw"></i> Imprimir pedido
            </a>
            <a href="' . base_url('pedidos_proveedor/anular/' . $id_pedido) . '" class="btn btn-danger btn-sm btn_anular">
                <i class="fa fa-trash fa-fw"></i> Anular todo
            </a>
            <a href="' . base_url('pedidos_proveedor/pedido_realizado/' . $id_pedido) . '" class="btn btn-primary btn-sm text-white"">
                <i class="fa fa-check fa-fw"></i> Pedido realizado
            </a>
            <a href="' . base_url('pedidos_proveedor/pedido_recibido/' . $id_pedido) . '" class="btn btn-success btn-sm">
                <i class="fa fa-box fa-fw"></i> Pedido recibido
            </a>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            ';
        });
        $crud->callbackEditField('id_pedido', array($this, 'paso_id_pedido'));
        $crud->callbackEditField('detalles', array($this, 'lineas'));
        $crud->callbackAddField('fecha_salida', array($this, '_saca_fecha_salida'));
        $crud->callbackAddField('fecha_entrega', array($this, '_saca_fecha_entrega'));
        $crud->callbackAddField('id_usuario', array($this, 'guarda_usuario'));
        // Callbacks tabla LOG
        $crud->callbackAfterInsert(function ($stateParameters) {
            $this->logAction('Pedido Proveedor', 'Añadir Pedido', $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterUpdate(function ($stateParameters) {
            $this->logAction('Pedido Proveedor', 'Editar Pedido, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterDelete(function ($stateParameters) {
            $this->logAction('Pedido Proveedor', 'Eliminar Pedido, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });

        $crud->setLangString('form_update_changes', 'Actualizar pedido');
        $crud->setLangString('modal_save', 'Guardar pedido');
        $output = $crud->render();

        if ($output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }
        echo view('layouts/main', (array)$output);
    }

    public function add()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $clienteModel = new ProveedoresModel($db);


        $data['proveedores'] = $clienteModel->findAll();
        $data['usuario_html'] = $this->guarda_usuario();

        echo view('add_pedidoProveedor', $data);
    }

    //modal add pedido
    public function save()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $pedidoModel = new PedidosProveedorModel($db);

        $data = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'referencia' => $this->request->getPost('referencia'),
            'fecha_salida' => $this->request->getPost('fecha_salida'),
            'fecha_entrega' => $this->request->getPost('fecha_entrega'),
            'observaciones' => $this->request->getPost('observaciones'),
        ];

        if ($pedidoModel->insert($data)) {
            // Obtener el ID del pedido recién insertado
            $insertId = $pedidoModel->insertID();

            // Registrar la acción en el log
            $this->logAction('Pedido Proveedor', 'Añadir Pedido', $data);

            // Redirigir a la página en marcha
            return redirect()->to(base_url('pedidos_proveedor'));
        } else {
            return redirect()->back()->with('error', 'No se pudo guardar el pedido');
        }
    }

    function paso_id_pedido($value, $id_pedido)
    {
        return $id_pedido . '<input type="hidden" name="id_pedido" value="' . $id_pedido . '">';
    }


    function guarda_usuario()
    {
        $datos = new \App\Models\Usuarios2_Model();
        $data = usuario_sesion();
        $id_empresa = $data['id_empresa'];
        $id_usuario = $data['id_user'];
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
            // Si no se encuentra el usuario, se añade 'Test', Es para cuando cambia de empresa un superadmin
            $usuarios[$id_usuario] = 'Test';
        }
        return '<input type="hidden" name="id_usuario" value="' . $id_usuario . '">
		<b>' . $usuarios[$id_usuario] . '</b>';
    }

    public function anular($id_pedido)
    {
        $Lineaspedido_model = new LineaPedidoModel();
        $Lineaspedido_model->anular_lineas($id_pedido);
        // TABLA LOG
        $this->logAction('Pedido Proveedor', 'Anular pedido, ID: ' . $id_pedido, []);
        return redirect()->to('pedidos_proveedor/index#/edit/' . $id_pedido);
    }


    function lineas($value, $id_pedido)
    {
        if (isset($_GET['pg2'])) {
            $pg2 = $_GET['pg2'];
        } else {
            $pg2 = "Rafa";
        }

        return '
			<div class="guarda-pedido">
				<!-- Botón de guardar pedido -->
				</br>
				<button type="submit" class="btn btn-primary btn-guardar-pedido">Guardar Pedido</button>
			</div>
			<fieldset>
				<input type="hidden" name="detalles" value="">
				<iframe src="' . base_url('pedidos_proveedor/Linea_pedidos/' . $id_pedido . '?pg2=' . $pg2) . '" frameborder="0" width="100%" class="iframe_lineapedidos"></iframe>
			</fieldset>';
    }


    public function _saca_fecha_salida()
    {
        $entrada = date('Y-m-d');
        return "<input id='field-fecha-entrada' type='date' name='fecha_salida' value='" . $entrada . "' class='datepicker-input form-control hasDatepicker'>";
    }
    public function _saca_fecha_entrega()
    {
        $fecha = date('d-m-Y');
        $entrega = date('Y-m-d', strtotime($fecha . "+ 14 days"));
        return "<input id='field-fecha-entrega' type='date' name='fecha_entrega' value=" . $entrega . " class='datepicker-input form-control hasDatepicker'>";
    }


    // CREAMOS LA LINEA DE PEDIDOS

    public function Linea_pedidos($id_pedido)
    {
        helper('controlacceso');
        $nivel = control_login();
        $crud = $this->_getClientDatabase();
        $crud->setTable('linea_pedido_proveedor');
        $crud->setSubject('Línea de Pedido', 'Lineas del Pedido');
        $crud->where('id_pedido=' . $id_pedido);
        $crud->defaultOrdering('id_lineapedido', 'asc');
        $crud->fieldType('estado', 'dropdown_search', [
            "0" => "Pendiente de realizar",
            "1" => "Pendiente de recibir",
            "2" => "Recibido",
            "6" => "Anulado"
        ]);
        $this->idpedido = $id_pedido;
        $crud->fieldType('id_pedido', 'hidden', $id_pedido);
        $crud->requiredFields(['n_piezas', 'id_producto']);
        $crud->fieldType('total_linea', 'invisible');

        // VISTAS
        $crud->columns(['n_piezas', 'id_producto', 'estado', 'total_linea']);
        $crud->editFields(['id_pedido', 'n_piezas', 'precio_compra', 'id_producto',  'estado', 'fecha_salida', 'fecha_entrega', 'observaciones', 'total_linea']);
        $crud->addFields(['id_pedido', 'n_piezas',  'id_producto', 'observaciones', 'total_linea']);

        // RELACIONES
        $crud->setRelation('id_producto', 'productos_necesidad', 'nombre_producto');
        $crud->setRelation('estado', 'estados_proveedor', 'nombre_estado');

        // DISPLAY_AS
        $crud->displayAs('n_piezas', 'Uds.');
        $crud->displayAs('id_producto', 'Producto');
        $crud->setLangString('modal_save', 'Guardar Línea de Pedido');

        // CALLBACKS
        $crud->callbackColumn('total_linea', function ($value, $row) {
            return "<div class='estado" . $row->estado . "'><strong>" . $value . "€</strong></div>";
        });
        $crud->callbackBeforeUpdate(array($this, 'saca_precio_linea'));
        $crud->callbackAfterUpdate(function ($stateParameters) use ($id_pedido) {
            $this->logAction('Linea pedido proveedor', 'Edita línea de pedido, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            $this->actualizarTotalPedido($id_pedido);
            $this->actualizarEstadoPedido($id_pedido); // Llamada a la nueva función
            return $stateParameters;
        });
        $crud->callbackBeforeInsert(function ($post_array) use ($id_pedido) {
            // Asegurarse de que el id_pedido se incluya en los datos
            $post_array->data['id_pedido'] = $id_pedido;
            return $this->saca_precio_linea($post_array);
        });
        $crud->callbackAfterInsert(function ($stateParameters) use ($id_pedido) {
            $this->logAction('Linea pedido proveedor', 'Añade línea de pedido', $stateParameters);
            $this->actualizarTotalPedido($id_pedido);
            $this->actualizarEstadoPedido($id_pedido); // Llamada a la nueva función
            return $stateParameters;
        });
        $crud->callbackAfterDelete(function ($stateParameters) use ($id_pedido) {
            $this->logAction('Linea pedido proveedor', 'Elimina línea de pedido, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            $this->actualizarTotalPedido($id_pedido);
            $this->actualizarEstadoPedido($id_pedido); // Llamada a la nueva función
            return $stateParameters;
        });
        $crud->callbackAddField('fecha_salida', array($this, '_saca_fecha_salida'));
        $crud->callbackAddField('fecha_entrega', array($this, '_saca_fecha_entrega'));
        $crud->callbackAddField('id_usuario', array($this, 'guarda_usuario'));
        $crud->unsetPrint();
        $crud->unsetExport();
        $crud->unsetRead();

        $output = $crud->render();

        if ($output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }
        return $this->_output_sencillo($output);
    }

    function _pinta_euro_linea($total_linea)
    {
        return "<div> <b>$total_linea &euro;</b></div>";
    }

    public function saca_precio_pedido($post_array)
    {
        $myvar = $post_array->data;
        $elpedido = $myvar['id_pedido'];
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('linea_pedido_proveedor');
        $builder->select('*');
        $builder->where('id_pedido', $elpedido);
        $query2 = $builder->get();
        if ($query2 !== false) {  // Check if query was successful
            $total_pedidos = "0";
            // Variables del actualizador de estados_proveeedor
            $estado_menor = '100';
            foreach ($query2->getResult() as $row) {
                $total_pedidos = $total_pedidos + $row->total_linea;

                // Comienzo el actualizador de estados_proveeedor
                $estado_actual = $row->estado;
                if ($estado_actual <= $estado_menor) {
                    $estado_menor = $estado_actual;
                }
            }
            $data = array('total_pedido' => $total_pedidos);
            $builder = $db->table('pedidos_proveedor');
            $builder->set($data);
            $builder->where('id_pedido', $elpedido);
            $builder->update();

            // Actualizo la línea de pedido y le pongo el estado_total
            $data2 = array('estado' => $estado_menor);

            $builder = $db->table('pedidos_proveedor');
            $builder->set($data2);
            $builder->where('id_pedido', $elpedido);
            $builder->update();
        } else {
            // Handle the error, e.g., log it or set a default value
            log_message('error', 'Query failed in saca_precio_pedido for pedido ID: ' . $elpedido);
        }

        return $post_array;
    }

    public function saca_precio_linea($post_array)
    {
        log_message('debug', 'Datos recibidos para insertar línea de pedido: ' . print_r($post_array, true));

        // Verificar que 'id_producto' esté presente
        if (!isset($post_array->data['id_producto'])) {
            log_message('error', 'ID del producto no está presente en los datos.');
            return $post_array;
        }
        $id_producto = $post_array->data['id_producto'];
        $id_pedido = $post_array->data['id_pedido'];
        $n_piezas = $post_array->data['n_piezas'];
        // Conectamos a la base de datos
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        // Obtener el id_proveedor del pedido actual
        $builder_pedido = $db->table('pedidos_proveedor');
        $builder_pedido->select('id_proveedor');
        $builder_pedido->where('id_pedido', $id_pedido);
        $query_pedido = $builder_pedido->get();
        if ($query_pedido->getNumRows() > 0) {
            $id_proveedor = $query_pedido->getRow()->id_proveedor;
            // Verificar que el producto esté asociado al proveedor correcto
            $builder_producto = $db->table('productos_proveedor');
            $builder_producto->select('precio');
            $builder_producto->where('id_producto_necesidad', $id_producto);
            $builder_producto->where('id_proveedor', $id_proveedor);
            $builder_producto->where('seleccion_mejor', 1);
            $query_producto = $builder_producto->get();
            if ($query_producto->getNumRows() > 0) {
                $producto = $query_producto->getRow();
                $precio = $producto->precio;
            } else {
                // Si no se encuentra el producto con seleccion_mejor = 1, obtener el precio por defecto
                $builder_producto->resetQuery();
                $builder_producto->select('precio');
                $builder_producto->where('id_producto_necesidad', $id_producto);
                $builder_producto->where('id_proveedor', $id_proveedor);
                $query_producto = $builder_producto->get();

                if ($query_producto->getNumRows() > 0) {
                    $producto = $query_producto->getRow();
                    $precio = $producto->precio;
                } else {
                    log_message('error', 'No se encontró el producto con ID: ' . $id_producto . ' para el proveedor con ID: ' . $id_proveedor);
                    $post_array->data['total_linea'] = 0;
                    return $post_array;
                }
            }
            // Calcular el total de la línea
            $total_linea = $n_piezas * $precio;
            $post_array->data['total_linea'] = $total_linea;
            $post_array->data['precio_compra'] = $precio;
            // Actualizar la línea de pedido con el total calculado
            $builder_linea = $db->table('linea_pedido_proveedor');
            $builder_linea->set('total_linea', $total_linea);
            $builder_linea->set('precio_compra', $precio);
            $builder_linea->where('id_pedido', $id_pedido);
            $builder_linea->where('id_producto', $id_producto);
            $builder_linea->update();

            log_message('debug', 'Línea de pedido actualizada con total: ' . $total_linea);
        } else {
            log_message('error', 'No se encontró el pedido con ID: ' . $id_pedido);
            $post_array->data['total_linea'] = 0;
        }
        return $post_array;
    }
    private function actualizarTotalPedido($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('linea_pedido_proveedor');
        $builder->select('SUM(total_linea) as total_pedido');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $total = $query->getRow()->total_pedido;
            // Actualizar el total del pedido en la tabla pedidos_proveedor
            $pedidoBuilder = $db->table('pedidos_proveedor');
            $pedidoBuilder->set('total_pedido', $total);
            $pedidoBuilder->where('id_pedido', $id_pedido);
            $pedidoBuilder->update();
        }
    }

    private function actualizarEstadoPedido($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Obtener el estado más bajo de las líneas del pedido
        $builder = $db->table('linea_pedido_proveedor');
        $builder->select('MIN(estado) as estado_menor');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $estado_menor = $query->getRow()->estado_menor;

            // Actualizar el estado del pedido en la tabla pedidos_proveedor
            $pedidoBuilder = $db->table('pedidos_proveedor');
            $pedidoBuilder->set('estado', $estado_menor);
            $pedidoBuilder->where('id_pedido', $id_pedido);
            $pedidoBuilder->update();

            log_message('debug', 'Estado del pedido actualizado a: ' . $estado_menor . ' para id_pedido: ' . $id_pedido);
        } else {
            log_message('error', 'No se encontraron líneas de pedido para id_pedido: ' . $id_pedido);
        }
    }

    public function pedido_realizado($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        // Actualizar el estado de las líneas de pedido a 1
        $builder_lineas = $db->table('linea_pedido_proveedor');
        $builder_lineas->set('estado', 1);
        $builder_lineas->where('id_pedido', $id_pedido);
        $builder_lineas->update();
        // Actualizar el estado del pedido a 1
        $builder_pedido = $db->table('pedidos_proveedor');
        $builder_pedido->set('estado', 1);
        $builder_pedido->where('id_pedido', $id_pedido);
        $builder_pedido->update();
        return redirect()->to(base_url('pedidos_proveedor'));
    }

    public function pedido_recibido($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        // Actualizar el estado de las líneas de pedido a 2
        $builder_lineas = $db->table('linea_pedido_proveedor');
        $builder_lineas->set('estado', 2);
        $builder_lineas->where('id_pedido', $id_pedido);
        $builder_lineas->update();

        // Actualizar el estado del pedido a 2
        $builder_pedido = $db->table('pedidos_proveedor');
        $builder_pedido->set('estado', 2);
        $builder_pedido->where('id_pedido', $id_pedido);
        $builder_pedido->update();
        return redirect()->to(base_url('pedidos_proveedor'));
    }
    function imprimir_parte($row)
    {
        $uri = current_url();
        $pg2 = $uri;
        return base_url() . "partes/printproveedor/" . $row->id_lineapedido . "?pg2=" . $pg2;
    }
    /* Funciones de salida - Vistas */
    function _output_sencillo($output = null)
    {
        echo view('sencillo', (array)$output);
    }
}
