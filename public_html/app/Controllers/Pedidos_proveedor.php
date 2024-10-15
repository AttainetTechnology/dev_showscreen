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

    public function todos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('pedidos_proveedor');

        $builder->select('id_pedido, fecha_salida, id_proveedor, referencia, estado, fecha_entrega, id_usuario, total_pedido');
        $builder->orderBy('fecha_salida', 'desc');
        $builder->orderBy('id_pedido', 'desc');

        $pedidos = $builder->get()->getResultArray();

        foreach ($pedidos as &$pedido) {
            $pedido['nombre_proveedor'] = $this->getProveedorNombre($pedido['id_proveedor']);
            $pedido['nombre_usuario'] = $this->getUsuarioNombre($pedido['id_usuario']);
            $pedido['estado_texto'] = $this->getEstadoTexto($pedido['estado']);
            $pedido['acciones'] = [
                'imprimir' => base_url("pedidos_proveedor/print/{$pedido['id_pedido']}"),
                'eliminar' => base_url("Pedidos_proveedor/eliminar/{$pedido['id_pedido']}"), 
                'editar' => base_url("pedidos_proveedor/editar/{$pedido['id_pedido']}")
            ];          
        }
        return view('mostrarPedidosProveedor', ['pedidos' => $pedidos]);
    }

    private function getProveedorNombre($id_proveedor)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $proveedor = $db->table('proveedores')->select('nombre_proveedor')->where('id_proveedor', $id_proveedor)->get()->getRow();
        return $proveedor ? $proveedor->nombre_proveedor : 'Desconocido';
    }

    private function getUsuarioNombre($id_usuario)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $usuario = $db->table('users')->select('nombre_usuario')->where('id', $id_usuario)->get()->getRow();
        return $usuario ? $usuario->nombre_usuario : 'Desconocido';
    }

    private function getEstadoTexto($estado)
    {
        $estados = [
            "0" => "Pendiente de realizar",
            "1" => "Pendiente de recibir",
            "2" => "Recibido",
            "6" => "Anulado"
        ];
        return $estados[$estado] ?? 'Desconocido';
    }
    public function editar($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $pedidoModel = new PedidosProveedorModel($db);
    
        // Obtener datos del pedido
        $pedido = $pedidoModel->find($id_pedido);
        if (!$pedido) {
            return redirect()->to(base_url('pedidos_proveedor'))->with('error', 'Pedido no encontrado.');
        }
    
        // Obtener todas las líneas de pedido asociadas con el pedido
        $builder = $db->table('linea_pedido_proveedor');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();
        $lineasPedido = $query->getResultArray();
    
        // Obtener proveedores y otros datos adicionales
        $proveedores = (new ProveedoresModel($db))->findAll();
        $usuarios = $this->getUsuarios();
        $estados = [
            "0" => "Pendiente de realizar",
            "1" => "Pendiente de recibir",
            "2" => "Recibido",
            "6" => "Anulado"
        ];
    
        // Pasar todos los datos a la vista
        return view('editPedidoProveedor', [
            'pedido' => $pedido,
            'proveedores' => $proveedores,
            'usuarios' => $usuarios,
            'estados' => $estados,
            'lineasPedido' => $lineasPedido // Pasar la variable lineasPedido a la vista
        ]);
    }
    
     
    public function update($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $pedidoModel = new PedidosProveedorModel($db);

        // Verificar que el pedido existe
        $pedido = $pedidoModel->find($id_pedido);
        if (!$pedido) {
            return redirect()->to(base_url('pedidos_proveedor'))->with('error', 'Pedido no encontrado.');
        }

        // Recoger los datos del formulario
        $pedidoData = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'referencia' => $this->request->getPost('referencia'),
            'observaciones' => $this->request->getPost('observaciones'),
            'fecha_salida' => $this->request->getPost('fecha_salida'),
            'fecha_entrega' => $this->request->getPost('fecha_entrega'),
            'estado' => $this->request->getPost('estado')
        ];

        // Guardar cambios en la base de datos
        if ($pedidoModel->update($id_pedido, $pedidoData)) {
            return redirect()->to(base_url('pedidos_proveedor'))->with('message', 'Pedido actualizado con éxito.');
        } else {
            return redirect()->back()->with('error', 'Error al actualizar el pedido.');
        }
    }


    private function getUsuarios()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        return $db->table('users')->select('id, nombre_usuario')->get()->getResultArray();
    }

    public function add()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $clienteModel = new ProveedoresModel($db);

        $data['proveedores'] = $clienteModel->findAll();
        $data['usuario_html'] = $this->guarda_usuario();

        // Obtener el ID del proveedor desde la URL
        $data['id_proveedor_seleccionado'] = $this->request->getGet('id_proveedor');

        echo view('add_pedidoProveedorModal', $data);
    }
    public function addPedido()
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $clienteModel = new ProveedoresModel($db);

    $data['proveedores'] = $clienteModel->findAll();
    $data['usuario_html'] = $this->guarda_usuario();
    $data['id_proveedor_seleccionado'] = $this->request->getGet('id_proveedor');

    return view('addPedidoProveedor', $data); 
}
public function eliminar($id_pedido)
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $pedidoModel = new PedidosProveedorModel($db);
    $deleted = $pedidoModel->delete($id_pedido);

    if ($deleted) {
        // Devolver una respuesta JSON con éxito
        return $this->response->setJSON(['success' => true]);
    } else {
        // Devolver una respuesta JSON de error
        return $this->response->setJSON(['success' => false]);
    }
}


    //modal add pedido
    public function save()
    {
        $data = usuario_sesion(); // Obtiene los datos de la sesión del usuario actual
        $db = db_connect($data['new_db']);
        $pedidoModel = new PedidosProveedorModel($db);
    
        // Datos del pedido, incluyendo `id_usuario`
        $pedidoData = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'referencia' => $this->request->getPost('referencia'),
            'fecha_salida' => $this->request->getPost('fecha_salida'),
            'observaciones' => $this->request->getPost('observaciones'),
            'id_usuario' => $data['id_user'] 
        ];
    
      // Guardar el pedido y verificar si la operación fue exitosa
    if ($pedidoModel->insert($pedidoData)) {
        // Obtener el ID del pedido recién insertado
        $insertId = $pedidoModel->insertID();

        // Redirigir a la página de edición del pedido
        return redirect()->to(base_url("/pedidos_proveedor/editar/$insertId"))
                         ->with('message', 'Pedido guardado con éxito.');
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
    // CREAMOS LA LINEA DE PEDIDOS

    public function Linea_pedidos($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('linea_pedido_proveedor');
    
        // Obtener todas las líneas de pedido asociadas con el pedido
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();
        $lineasPedido = $query->getResultArray();
    
        if (empty($lineasPedido)) {
            log_message('error', 'No se encontraron líneas de pedido para el pedido ID: ' . $id_pedido);
        } else {
            log_message('info', 'Líneas de pedido encontradas: ' . json_encode($lineasPedido));
        }
    
        return view('editPedidoProveedor', [
            'lineasPedido' => $lineasPedido,
            'id_pedido' => $id_pedido,
            'estados' => [
                "0" => "Pendiente de realizar",
                "1" => "Pendiente de recibir",
                "2" => "Recibido",
                "6" => "Anulado"
            ]
        ]);
    }
    

// Método para crear una nueva línea de pedido
public function crearLinea()
{
    $data = $this->request->getPost();
    $data['id_pedido'] = $this->request->getPost('id_pedido');

    $db = db_connect(usuario_sesion()['new_db']);
    $builder = $db->table('linea_pedido_proveedor');

    if ($builder->insert($data)) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false]);
    }
}

// Método para actualizar una línea de pedido
public function actualizarLinea($id_lineapedido)
{
    $data = $this->request->getPost();
    $db = db_connect(usuario_sesion()['new_db']);
    $builder = $db->table('linea_pedido_proveedor');
    $builder->where('id_lineapedido', $id_lineapedido);

    if ($builder->update($data)) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false]);
    }
}

// Método para eliminar una línea de pedido
public function eliminarLinea($id_lineapedido)
{
    $db = db_connect(usuario_sesion()['new_db']);
    $builder = $db->table('linea_pedido_proveedor');
    $builder->where('id_lineapedido', $id_lineapedido);

    if ($builder->delete()) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false]);
    }
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
