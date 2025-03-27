<?php

namespace App\Controllers;

use App\Models\ProveedoresModel;
use App\Models\PedidosProveedorModel;
use App\Models\LineaPedidoModel;
use App\Models\ProductosNecesidadModel;


class Pedidos_proveedor extends BaseController
{
    protected $idpedido = 0;

    function __construct()
    {
        $this->idpedido = 0;
    }

    public function index()
    {
        helper('controlacceso');
        $redirect = check_access_level();
        $redirectUrl = session()->getFlashdata('redirect');
        if ($redirect && is_string($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
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
        // Agregar breadcrumbs para la página de todos los pedidos
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Pedidos');

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('pedidos_proveedor');

        $builder->select('id_pedido, fecha_salida, id_proveedor, referencia, estado, fecha_entrega, id_usuario, total_pedido');
        $builder->orderBy('fecha_salida', 'desc');
        $builder->orderBy('id_pedido', 'desc');

        $pedidos = $builder->get()->getResultArray();

        $data['amiga'] = $this->getBreadcrumbs();

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

        return view('mostrarPedidosProveedor', [
            'pedidos' => $pedidos,
            'amiga' => $data['amiga']
        ]);
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
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Pedidos', base_url('/pedidos_proveedor'));
        $this->addBreadcrumb('Editar Pedido');

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $pedidoModel = new PedidosProveedorModel($db);
        $pedido = $pedidoModel->find($id_pedido);

        if (!$pedido) {
            return redirect()->to(base_url('pedidos_proveedor'))->with('error', 'Pedido no encontrado.');
        }

        // Actualizar consulta para incluir nombre_producto
        $builder = $db->table('linea_pedido_proveedor lp');
        $builder->select('lp.*, pp.ref_producto, pn.nombre_producto');
        $builder->join('productos_proveedor pp', 'pp.ref_producto = lp.ref_producto', 'left');
        $builder->join('productos_necesidad pn', 'pp.id_producto_necesidad = pn.id_producto', 'left');
        $builder->where('lp.id_pedido', $id_pedido);
        $lineasPedido = $builder->get()->getResultArray();

        $proveedores = (new ProveedoresModel($db))->findAll();
        $usuarios = $this->getUsuarios();
        $estados = [
            "0" => "Pendiente de realizar",
            "1" => "Pendiente de recibir",
            "2" => "Recibido",
            "6" => "Anulado"
        ];

        return view('editPedidoProveedor', [
            'pedido' => $pedido,
            'proveedores' => $proveedores,
            'usuarios' => $usuarios,
            'estados' => $estados,
            'lineasPedido' => $lineasPedido,
            'id_pedido' => $id_pedido,
        ]);
    }


    public function update($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $pedidoModel = new PedidosProveedorModel($db);
        $pedido = $pedidoModel->find($id_pedido);
        if (!$pedido) {
            return redirect()->to(base_url('pedidos_proveedor'))->with('error', 'Pedido no encontrado.');
        }
        $pedidoData = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'referencia' => $this->request->getPost('referencia'),
            'observaciones' => $this->request->getPost('observaciones'),
            'fecha_salida' => $this->request->getPost('fecha_salida'),
            'fecha_entrega' => $this->request->getPost('fecha_entrega'),
            'estado' => $this->request->getPost('estado')
        ];
        if ($pedidoModel->update($id_pedido, $pedidoData)) {
            return redirect()->to(base_url('pedidos_proveedor/editar/' . $id_pedido))->with('message', 'Pedido actualizado con éxito.');
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
        $data['id_proveedor_seleccionado'] = $this->request->getGet('id_proveedor');
        echo view('add_pedidoProveedorModal', $data);
    }
    public function addPedido()
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Pedidos Proveedor', base_url('pedidos_proveedor'));
        $this->addBreadcrumb('Añadir Pedido');

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $clienteModel = new ProveedoresModel($db);

        $data['proveedores'] = $clienteModel->findAll();
        $data['usuario_html'] = $this->guarda_usuario();
        $data['id_proveedor_seleccionado'] = $this->request->getGet('id_proveedor');
        $data['amiga'] = $this->getBreadcrumbs();

        return view('addPedidoProveedor', $data);
    }
    public function eliminar($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $pedidoModel = new PedidosProveedorModel($db);
        $deleted = $pedidoModel->delete($id_pedido);
        if ($deleted) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function save()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $pedidoModel = new PedidosProveedorModel($db);
        log_message('debug', 'Datos recibidos en el método save: ' . print_r($this->request->getPost(), true));
        $pedidoData = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'referencia' => $this->request->getPost('referencia'),
            'fecha_salida' => $this->request->getPost('fecha_salida'),
            'observaciones' => $this->request->getPost('observaciones'),
            'id_usuario' => $data['id_user']
        ];
        if ($pedidoModel->insert($pedidoData)) {
            $idPedido = $pedidoModel->insertID();

            $idProducto = $this->request->getPost('id_producto');
            $idProductoProveedor = $this->request->getPost('id');

            log_message('debug', "Pedido creado con ID: $idPedido, id_producto: $idProducto, id_producto_proveedor: $idProductoProveedor");

            if ($idProducto && $idProductoProveedor) {
                $this->crearLineaAutomatica($idPedido, $idProductoProveedor);
            }

            return redirect()->to(base_url("/pedidos_proveedor/editar/$idPedido"))
                ->with('message', 'Pedido guardado con éxito.');
        } else {
            log_message('error', 'Error al guardar el pedido: ' . print_r($pedidoData, true));
            return redirect()->back()->with('error', 'No se pudo guardar el pedido');
        }
    }

    private function crearLineaAutomatica($idPedido, $idProductoProveedor)
    {

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        $builder = $db->table('productos_proveedor');
        $builder->select('ref_producto, precio');
        $builder->where('id', $idProductoProveedor);
        $producto = $builder->get()->getRow();

        if ($producto) {
            $lineaData = [
                'id_pedido' => $idPedido,
                'ref_producto' => $producto->ref_producto,
                'precio_compra' => $producto->precio,
                'n_piezas' => 1, // Ajustar si es necesario
                'total_linea' => $producto->precio,
            ];

            $lineaPedidoModel = new LineaPedidoModel($db);
            if ($lineaPedidoModel->insert($lineaData)) {
                log_message('debug', "Línea de pedido creada para el pedido $idPedido: " . print_r($lineaData, true));
                $this->actualizarTotalPedido($idPedido);
            } else {
                log_message('error', "Error al insertar línea de pedido para el pedido $idPedido: " . print_r($lineaData, true));
            }
        } else {
            log_message('error', "No se encontró el producto proveedor con ID: $idProductoProveedor");
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
            $usuarios[$id_usuario] = 'Test';
        }
        return '<input type="hidden" name="id_usuario" value="' . $id_usuario . '">
		<b>' . $usuarios[$id_usuario] . '</b>';
    }

    public function anular($id_pedido)
    {
        $Lineaspedido_model = new LineaPedidoModel();
        $Lineaspedido_model->anular_lineas($id_pedido);
        $this->logAction('Pedido Proveedor', 'Anular pedido, ID: ' . $id_pedido, []);
        return redirect()->to(base_url('pedidos_proveedor/editar/' . $id_pedido));
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
                "1" => "Enviado",
                "2" => "Recibido",
                "6" => "Anulado"
            ]
        ]);
    }
    public function addLineaPedidoForm($id_pedido)
    {
        $db = db_connect(usuario_sesion()['new_db']);

        // Obtener el id_proveedor del pedido
        $pedidoBuilder = $db->table('pedidos_proveedor');
        $pedido = $pedidoBuilder->select('id_proveedor')->where('id_pedido', $id_pedido)->get()->getRow();

        if (!$pedido) {
            return redirect()->back()->with('error', 'Pedido no encontrado.');
        }

        // Filtrar productos que pertenecen al proveedor
        $productosBuilder = $db->table('productos_proveedor');
        $productosBuilder->select('ref_producto, id');
        $productosBuilder->where('id_proveedor', $pedido->id_proveedor);
        $productos = $productosBuilder->get()->getResultArray();

        return view('addLineaPedidoProveedor', [
            'productos' => $productos,
            'id_pedido' => $id_pedido
        ]);
    }

    public function crearLinea()
    {
        $data = $this->request->getPost();
        $ref_producto = $this->request->getPost('ref_producto');

        $db = db_connect(usuario_sesion()['new_db']);
        $builder = $db->table('productos_proveedor');
        $builder->select('precio');
        $builder->where('ref_producto', $ref_producto);
        $producto = $builder->get()->getRow();

        if (!$producto) {
            return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado']);
        }

        $data['precio_compra'] = $producto->precio;
        $data['total_linea'] = $data['precio_compra'] * $data['n_piezas'];

        $lineaBuilder = $db->table('linea_pedido_proveedor');
        if ($lineaBuilder->insert($data)) {
            $this->actualizarTotalPedido($data['id_pedido']);
            $this->actualizarEstadoPedido($data['id_pedido']);
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar la línea de pedido']);
        }
    }

    public function actualizarLinea($id_lineapedido)
    {
        $data = $this->request->getPost();

        // Validar datos esenciales
        if (empty($data['id_pedido']) || empty($id_lineapedido)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos.']);
        }

        $db = db_connect(usuario_sesion()['new_db']);
        $builder_linea = $db->table('linea_pedido_proveedor');

        // Obtener los datos actuales de la línea
        $builder_linea->select('ref_producto, precio_compra, id_pedido');
        $builder_linea->where('id_lineapedido', $id_lineapedido);
        $linea_actual = $builder_linea->get()->getRow();

        if (!$linea_actual) {
            return $this->response->setJSON(['success' => false, 'message' => 'Línea de pedido no encontrada.']);
        }

        $id_pedido = $linea_actual->id_pedido;
        $ref_producto_actual = $linea_actual->ref_producto;
        $precio_compra_actual = $linea_actual->precio_compra;

        // Si la referencia del producto cambia, actualiza el precio
        if (!empty($data['ref_producto']) && $data['ref_producto'] !== $ref_producto_actual) {
            $builder_producto = $db->table('productos_proveedor');
            $builder_producto->select('precio');
            $builder_producto->where('ref_producto', $data['ref_producto']);
            $producto = $builder_producto->get()->getRow();

            if ($producto) {
                $data['precio_compra'] = $producto->precio;
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado.']);
            }
        } else {
            // Si no cambia la referencia, mantén el precio actual
            $data['precio_compra'] = $precio_compra_actual;
        }

        // Recalcular el total_linea
        $n_piezas = $data['n_piezas'] ?? 1; // Número de piezas por defecto a 1 si no se envía
        $data['total_linea'] = $data['precio_compra'] * $n_piezas;

        // Actualizar la línea
        $builder_linea->where('id_lineapedido', $id_lineapedido);
        if ($builder_linea->update($data)) {
            // Actualizar el total del pedido
            $this->actualizarTotalPedido($id_pedido);
            $this->actualizarEstadoPedido($data['id_pedido']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Línea de pedido actualizada correctamente.',
                'total_linea' => $data['total_linea']
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar la línea de pedido.']);
        }
    }
    public function eliminarLinea($id_lineapedido)
    {
        $data = $this->request->getPost();
        $db = db_connect(usuario_sesion()['new_db']);
        $builder = $db->table('linea_pedido_proveedor');
        $linea = $builder->select('id_pedido')->where('id_lineapedido', $id_lineapedido)->get()->getRow();

        if ($builder->where('id_lineapedido', $id_lineapedido)->delete() && $linea) {
            // Actualizar estado y total del pedido si se elimina correctamente
            $this->actualizarEstadoPedido($linea->id_pedido);
            $this->actualizarTotalPedido($linea->id_pedido);
        }

        // Redirigir siempre a la página de edición del pedido
        return redirect()->to(base_url('pedidos_proveedor/editar/' . ($linea->id_pedido ?? '')));
    }

    public function editLineaPedidoForm($id_lineapedido)
    {
        $db = db_connect(usuario_sesion()['new_db']);

        // Obtener los datos de la línea de pedido
        $lineaBuilder = $db->table('linea_pedido_proveedor');
        $lineaBuilder->where('id_lineapedido', $id_lineapedido);
        $lineaPedido = $lineaBuilder->get()->getRowArray();

        if (!$lineaPedido) {
            return redirect()->back()->with('error', 'Línea de pedido no encontrada.');
        }

        // Obtener el id_proveedor del pedido asociado
        $pedidoBuilder = $db->table('pedidos_proveedor');
        $pedido = $pedidoBuilder->select('id_proveedor')->where('id_pedido', $lineaPedido['id_pedido'])->get()->getRow();

        if (!$pedido) {
            return redirect()->back()->with('error', 'Pedido asociado no encontrado.');
        }

        // Filtrar productos que pertenecen al proveedor
        $productosBuilder = $db->table('productos_proveedor');
        $productosBuilder->select('ref_producto');
        $productosBuilder->where('id_proveedor', $pedido->id_proveedor);
        $productos = $productosBuilder->get()->getResultArray();

        return view('editLineaProveedor', [
            'lineaPedido' => $lineaPedido,
            'productos' => $productos
        ]);
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
        if ($query2 !== false) {
            $total_pedidos = "0";
            $estado_menor = '100';
            foreach ($query2->getResult() as $row) {
                $total_pedidos = $total_pedidos + $row->total_linea;
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
            $data2 = array('estado' => $estado_menor);

            $builder = $db->table('pedidos_proveedor');
            $builder->set($data2);
            $builder->where('id_pedido', $elpedido);
            $builder->update();
        } else {
            log_message('error', 'Query failed in saca_precio_pedido for pedido ID: ' . $elpedido);
        }

        return $post_array;
    }
    public function saca_precio_linea($post_array)
    {
        if (!isset($post_array->data['id_producto']) || !isset($post_array->data['id_lineapedido'])) {
            log_message('error', 'ID del producto o ID de la línea de pedido no está presente en los datos.');
            return $post_array;
        }

        $ref_producto = $post_array->data['ref_producto'];
        $id_pedido = $post_array->data['id_pedido'];
        $n_piezas = $post_array->data['n_piezas'] ?? 0;
        $id_lineapedido = $post_array->data['id_lineapedido'];
        $precio_compra = $post_array->data['precio_compra'] ?? null;

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        if ($precio_compra === null) {
            $builder_pedido = $db->table('pedidos_proveedor');
            $builder_pedido->select('id_proveedor');
            $builder_pedido->where('id_pedido', $id_pedido);
            $query_pedido = $builder_pedido->get();

            if ($query_pedido->getNumRows() > 0) {
                $id_proveedor = $query_pedido->getRow()->id_proveedor;
                $builder_producto = $db->table('productos_proveedor');
                $builder_producto->select('precio');
                $builder_producto->where('id_producto_necesidad', $ref_producto);
                $builder_producto->where('id_proveedor', $id_proveedor);
                $query_producto = $builder_producto->get();

                if ($query_producto->getNumRows() > 0) {
                    $precio_compra = $query_producto->getRow()->precio;
                } else {
                    $builder_producto->resetQuery();
                    $builder_producto->select('precio');
                    $builder_producto->where('id_producto_necesidad', $ref_producto);
                    $builder_producto->where('id_proveedor', $id_proveedor);
                    $query_producto = $builder_producto->get();

                    if ($query_producto->getNumRows() > 0) {
                        $precio_compra = $query_producto->getRow()->precio;
                    } else {
                        log_message('error', 'No se encontró el producto con ID: ' . $ref_producto . ' para el proveedor con ID: ' . $id_proveedor);
                        $post_array->data['total_linea'] = 0;
                        return $post_array;
                    }
                }
            }
        }
        // Calcula el total usando el precio manual o el precio encontrado
        $precio_compra = is_numeric($precio_compra) ? (float) $precio_compra : 0;
        $n_piezas = is_numeric($n_piezas) ? (int) $n_piezas : 0;
        $total_linea = $n_piezas * $precio_compra;
        $post_array->data['total_linea'] = $total_linea;
        $post_array->data['precio_compra'] = $precio_compra;

        // Actualiza la base de datos con el total de línea y el precio de compra
        $builder_linea = $db->table('linea_pedido_proveedor');
        $builder_linea->set('total_linea', $total_linea);
        $builder_linea->set('precio_compra', $precio_compra);
        $builder_linea->where('id_lineapedido', $id_lineapedido);
        $builder_linea->update();

        // También actualiza el total del pedido completo
        $this->actualizarTotalPedido($id_pedido);
        $this->actualizarEstadoPedido($data['id_pedido']);
        return $post_array;
    }
    private function actualizarTotalPedido($idPedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Obtener el total de las líneas asociadas al pedido
        $builder = $db->table('linea_pedido_proveedor');
        $builder->selectSum('total_linea');
        $builder->where('id_pedido', $idPedido);
        $total = $builder->get()->getRow()->total_linea;

        // Validar si hay líneas de pedido
        $builder->resetQuery();
        $builder->select('id_lineapedido');
        $builder->where('id_pedido', $idPedido);
        $hasLines = $builder->countAllResults();

        // Si no hay líneas, establecer el total a 0
        if (!$hasLines) {
            $total = 0;
        }
        // Actualizar el total en la tabla pedidos_proveedor
        $pedidoModel = new PedidosProveedorModel($db);
        $pedidoModel->update($idPedido, ['total_pedido' => $total]);
    }

    private function actualizarEstadoPedido($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('linea_pedido_proveedor');
        $builder->select('MIN(estado) as estado_menor');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $estado_menor = $query->getRow()->estado_menor;
            $pedidoBuilder = $db->table('pedidos_proveedor');
            $pedidoBuilder->set('estado', $estado_menor);
            $pedidoBuilder->where('id_pedido', $id_pedido);
            $pedidoBuilder->update();
        } else {
            log_message('error', 'No se encontraron líneas de pedido para id_pedido: ' . $id_pedido);
        }
    }
    public function pedido_realizado($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder_lineas = $db->table('linea_pedido_proveedor');
        $builder_lineas->set('estado', 1);
        $builder_lineas->where('id_pedido', $id_pedido);
        $builder_lineas->update();
        $builder_pedido = $db->table('pedidos_proveedor');
        $builder_pedido->set('estado', 1);
        $builder_pedido->where('id_pedido', $id_pedido);
        $builder_pedido->update();
        return redirect()->to(base_url('pedidos_proveedor/editar/' . $id_pedido));
    }

    public function pedido_recibido($id_pedido)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder_lineas = $db->table('linea_pedido_proveedor');
        $builder_lineas->set('estado', 2);
        $builder_lineas->where('id_pedido', $id_pedido);
        $builder_lineas->update();
        $builder_pedido = $db->table('pedidos_proveedor');
        $builder_pedido->set('estado', 2);
        $builder_pedido->where('id_pedido', $id_pedido);
        $builder_pedido->update();
        return redirect()->to(base_url('pedidos_proveedor/editar/' . $id_pedido));
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
        echo view('sencillo', (array) $output);
    }
}
