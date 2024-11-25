<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\Usuarios2_Model;
use App\Models\Pedidos_model;
use App\Models\EstadoModel;
use App\Models\LineaPedido;
use App\Models\Productos_model;
use App\Models\ProcesosPedido;

class Pedidos extends BaseController
{
	protected $idpedido = 0;

	function __construct()
	{
		$this->idpedido = 0;
	}
	public function index()
	{
		$this->todos('estado<=', '6');
	}
	public function enmarcha()
	{
		$this->todos('estado<=', '4');
	}
	public function terminados()
	{
		$this->todos('estado=', '4');
	}
	public function entregados()
	{
		$this->todos('estado=', '5');
	}

	//CREAMOS LA PAGINA DE PEDIDOs

	public function todos($coge_estado, $where_estado)
	{
		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Pedidos');
		helper('controlacceso');
		$session = session();
		$data = datos_user();
		$db = db_connect($data['new_db']);
		$session_data = $session->get('logged_in');
		$nivel_acceso = $session_data['nivel'];

		// Cargar el modelo de pedidos, clientes y usuarios
		$pedidoModel = new Pedidos_model($db);
		$clienteModel = new ClienteModel($db);
		$usuarioModel = new Usuarios2_Model($db);

		// Obtener todos los pedidos
		$data['pedidos'] = $pedidoModel->getPedidoWithRelations($coge_estado, $where_estado);

		// Obtener la lista de clientes, usuarios y estados para los filtros
		$data['clientes'] = $clienteModel->findAll();
		$data['users'] = $usuarioModel->findAll();
		$estadoModel = new EstadoModel($db);  // Añadir la carga de estados
		$data['estados'] = $estadoModel->findAll();

		// Verificar el nivel de acceso para permitir la eliminación
		$data['allow_delete'] = ($nivel_acceso == 9);
		$data['amiga'] = $this->getBreadcrumbs();
		// Cargar la vista pasando los datos
		echo view('mostrarPedido', $data);
	}
	public function add()
	{
		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Pedidos', base_url('/pedidos/enmarcha'));
		$this->addBreadcrumb('Añadir Pedido');
		$data = datos_user();  // Obtener los datos de la sesión del usuario
		$db = db_connect($data['new_db']);  // Conectar a la base de datos del cliente

		$clienteModel = new ClienteModel($db);
		$data['clientes'] = $clienteModel->findAll();

		// Obtener el ID del usuario autenticado
		$id_usuario = $data['id_user'];

		// Consulta para obtener el nombre y apellidos desde la tabla 'users' de la BBDD del cliente
		$builder = $db->table('users');
		$builder->select('nombre_usuario, apellidos_usuario');
		$builder->where('id', $id_usuario);
		$builder->where('user_activo', '1');
		$query = $builder->get();
		$usuario = $query->getRow();
		$data['amiga'] = $this->getBreadcrumbs();

		// Verificar si se encontró el usuario
		$data['usuario_sesion'] = $usuario ? [
			'id_user' => $id_usuario,
			'nombre_usuario' => $usuario->nombre_usuario,
			'apellidos_usuario' => $usuario->apellidos_usuario
		] : [
			'id_user' => $id_usuario,
			'nombre_usuario' => 'Usuario desconocido',
			'apellidos_usuario' => ''
		];

		// Cargar la vista completa como página, en lugar de manejar una petición AJAX
		return view('add_pedido', $data);
	}

	function guarda_usuario()
	{
		$session = session();
		$data = datos_user();
		$db = db_connect($data['new_db']);
		$datos = new Usuarios2_Model($db);
		$data = usuario_sesion();
		$id_empresa = $data['id_empresa'];
		$id_usuario = $data['id_user'];
		$db_cliente = db_connect($data['new_db']);
		$builder = $db_cliente->table('users');
		$builder->select('id, nombre_usuario, apellidos_usuario');
		$builder->where('user_activo', '1');
		$query = $builder->get();
		$usuarios = [];
		if ($query->getNumRows() > 0) {
			foreach ($query->getResult() as $row) {
				$usuarios[$row->id] = $row->nombre_usuario . ' ' . $row->apellidos_usuario;
			}
		}
		return $usuarios;
	}
	public function save()
	{
		// Obtener los datos del usuario autenticado desde la sesión
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);

		// Validación básica de datos
		if (!$this->validate([
			'id_cliente' => 'required',
			'fecha_entrada' => 'required',
			'fecha_entrega' => 'required',
		])) {
			return redirect()->back()->with('error', 'Faltan datos obligatorios');
		}

		// Obtener el nombre del usuario desde la tabla 'users' en la base de datos
		$builder = $db->table('users');
		$builder->select('nombre_usuario, apellidos_usuario');
		$builder->where('id', $data['id_user']);
		$builder->where('user_activo', '1');
		$query = $builder->get();
		$usuario = $query->getRow();

		// Verificar si se encontró el usuario
		$nombre_usuario = $usuario ? $usuario->nombre_usuario . ' ' . $usuario->apellidos_usuario : 'test';

		// Preparar los datos para insertar el pedido
		$pedidoData = [
			'id_cliente' => $this->request->getPost('id_cliente'),
			'referencia' => $this->request->getPost('referencia'),
			'id_usuario' => $this->request->getPost('id_usuario'),
			'fecha_entrada' => $this->request->getPost('fecha_entrada'),
			'fecha_entrega' => $this->request->getPost('fecha_entrega'),
			'observaciones' => $this->request->getPost('observaciones'),
			'pedido_por' => $nombre_usuario
		];

		// Insertar el pedido y capturar el ID recién creado
		$id_pedido = $pedidoModel->insert($pedidoData, true);

		if ($id_pedido) {
			$this->logAction('Pedido', 'Añadir Pedido', $pedidoData);
			return redirect()->to(base_url("pedidos/edit/$id_pedido"))->with('success', 'Pedido guardado correctamente');
		} else {
			return redirect()->back()->with('error', 'No se pudo guardar el pedido');
		}
	}

	public function edit($id_pedido)
	{
		$this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Pedidos', base_url('/pedidos/enmarcha'));
        $this->addBreadcrumb('Editar Pedido');
		helper('controlacceso');
		$session = session();
		$data = datos_user();
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);
		$clienteModel = new ClienteModel($db);
		$estadoModel = new EstadoModel($db);
		$productosModel = new Productos_model($db);
		$usuarioModel = new Usuarios2_Model($db);
		// Obtener el pedido actual a editar
		$pedido = $pedidoModel->findPedidoWithUser($id_pedido);
		if (!$pedido) {
			return redirect()->back()->with('error', 'Pedido no encontrado');
		}
		// Obtener las líneas de pedido con el nombre del producto
		$builder = $db->table('linea_pedidos');
		$builder->select('linea_pedidos.*, productos.nombre_producto');
		$builder->join('productos', 'productos.id_producto = linea_pedidos.id_producto');
		$builder->where('linea_pedidos.id_pedido', $id_pedido);
		$query = $builder->get();
		$lineas_pedido = $query->getResultArray();

		// Pasar los datos a la vista
		$data['productos'] = $productosModel->findAll();
		$data['users'] = $usuarioModel->findAll();
		$data['clientes'] = $clienteModel->findAll();
		$data['estados'] = array_filter($estadoModel->findAll(), function ($estado) {
			return $estado['id_estado'] != 3; // Filtra el estado con id 3
		});
		$data['amiga'] = $this->getBreadcrumbs();
		$data['pedido'] = $pedido;
		$data['lineas_pedido'] = $lineas_pedido;
		return view('editPedido', $data);
	}
	public function update($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);
		// Validación básica de datos
		if (!$this->validate([
			'id_cliente' => 'required',
			'fecha_entrada' => 'required',
			'fecha_entrega' => 'required',
		])) {
			return redirect()->back()->with('error', 'Faltan datos obligatorios');
		}
		$updateData = [
			'id_cliente' => $this->request->getPost('id_cliente'),
			'referencia' => $this->request->getPost('referencia'),
			'id_usuario' => $data['id_user'],
			'fecha_entrada' => $this->request->getPost('fecha_entrada'),
			'fecha_entrega' => $this->request->getPost('fecha_entrega'),
			'observaciones' => $this->request->getPost('observaciones'),
			'estado' => $this->request->getPost('estado'),
		];

		if ($pedidoModel->update($id_pedido, $updateData)) {
			return redirect()->to(base_url('pedidos/edit/' . $id_pedido))->with('success', 'Pedido actualizado correctamente');
		} else {
			return redirect()->back()->with('error', 'No se pudo actualizar el pedido');
		}
	}

	function imprimir_parte($row)
	{
		if (is_numeric($row)) {
			$url = base_url() . "/partes/print/" . $row;
			return redirect()->to($url);
		} else {
			return redirect()->to(base_url('/error_page'))->with('error', 'Valor inválido recibido.');
		}
	}
	public function delete($id_pedido)
	{
		// Obtener datos de la sesión
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$session = session();
		$session_data = $session->get('logged_in');
		$nivel_acceso = $session_data['nivel'];
		if ($nivel_acceso != 9) {
			return redirect()->back()->with('error', 'No tienes permiso para eliminar este pedido');
		}
		$pedidoModel = new Pedidos_model($db);
		$lineaPedidoModel = new LineaPedido($db);
		$procesosPedidoModel = new ProcesosPedido($db);
		$db->transStart();
		$lineasPedido = $lineaPedidoModel->where('id_pedido', $id_pedido)->findAll();
		foreach ($lineasPedido as $linea) {
			$procesosPedidoModel->where('id_linea_pedido', $linea['id_lineapedido'])->delete();
		}
		$lineaPedidoModel->where('id_pedido', $id_pedido)->delete();
		$pedidoModel->delete($id_pedido);
		$db->transComplete();
		if ($db->transStatus() === false) {
			return redirect()->back()->with('error', 'No se pudo eliminar el pedido');
		}
		return redirect()->to(base_url('pedidos/enmarcha'))->with('success', 'Pedido y sus líneas asociadas eliminados correctamente');
	}

	public function actualizarTotalPedido($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$builder = $db->table('linea_pedidos');
		$builder->selectSum('total_linea', 'suma_total');
		$builder->where('id_pedido', $id_pedido);
		$query = $builder->get();
		$resultado = $query->getRow();
		$totalPedido = $resultado->suma_total ?? 0;
		$pedidoModel = new Pedidos_model($db);
		$pedidoModel->update($id_pedido, ['total_pedido' => $totalPedido]);
		return $totalPedido;
	}
	public function entregar($id_pedido)
	{
		$Lineaspedido_model = model('App\Models\Lineaspedido_model');
		$Lineaspedido_model->entrega_lineas($id_pedido);
		$this->logAction('Pedidos', 'Entrega pedido, ID: ' . $id_pedido, []);
		return redirect()->to('pedidos/edit/' . $id_pedido);
	}
	public function anular($id_pedido)
	{
		$Lineaspedido_model = model('App\Models\Lineaspedido_model');
		$Lineaspedido_model->anular_lineas($id_pedido);
		$this->logAction('Pedidos', 'Anular pedido, ID: ' . $id_pedido, []);
		return redirect()->to('pedidos/enmarcha');
	}

	// LOGICA LINEA PEDIDO
	public function mostrarLineasPedido($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaspedidoModel = new LineaPedido($db);
		$lineas_pedido = $lineaspedidoModel->where('id_pedido', $id_pedido)->findAll();
		return view('mostrarLineasPedido', ['lineas_pedido' => $lineas_pedido, 'pedido_id' => $id_pedido]);
	}
	public function addLineaPedido()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaspedidoModel = new LineaPedido($db);
		if (!$this->validate([
			'id_producto' => 'required',
		])) {
			return redirect()->back()->with('error', 'El producto es obligatorio.');
		}
		$fecha_entrada = $this->request->getPost('fecha_entrada') ?: date('Y-m-d');
		$fecha_entrega = $this->request->getPost('fecha_entrega') ?: date('Y-m-d', strtotime('+14 days'));
		$n_piezas = $this->request->getPost('n_piezas') ?: 0;
		$precio_venta = $this->request->getPost('precio_venta') ?: 0;
		$data = [
			'id_pedido' => $this->request->getPost('id_pedido'),
			'id_producto' => $this->request->getPost('id_producto'),
			'nom_base' => $this->request->getPost('nom_base') ?: '',
			'med_inicial' => $this->request->getPost('med_inicial') ?: '',
			'med_final' => $this->request->getPost('med_final') ?: '',
			'lado' => $this->request->getPost('lado') ?: '',
			'distancia' => $this->request->getPost('distancia') ?: '',
			'observaciones' => $this->request->getPost('observaciones') ?: '',
			'fecha_entrada' => $fecha_entrada,
			'fecha_entrega' => $fecha_entrega,
			'n_piezas' => $n_piezas,
			'precio_venta' => $precio_venta,
			'total_linea' => $n_piezas * $precio_venta
		];
		if ($lineaspedidoModel->insert($data)) {
			$this->actualizarTotalPedido($data['id_pedido']);
			$this->actualizarEstadoPedido($data['id_pedido']);
			return $this->response->setJSON(['success' => 'Línea de pedido añadida correctamente']);
		} else {
			return $this->response->setJSON(['error' => 'No se pudo añadir la línea de pedido']);
		}
	}
	public function updateLineaPedido($id_lineapedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaspedidoModel = new LineaPedido($db);
		$procesosPedidoModel = new ProcesosPedido($db);

		$updateData = [
			'id_producto' => $this->request->getPost('id_producto') ?? null,
			'n_piezas' => $this->request->getPost('n_piezas') ?? null,
			'precio_venta' => $this->request->getPost('precio_venta') ?? null,
			'nom_base' => $this->request->getPost('nom_base') ?? null,
			'med_inicial' => $this->request->getPost('med_inicial') ?? null,
			'med_final' => $this->request->getPost('med_final') ?? null,
			'lado' => $this->request->getPost('lado') ?? null,
			'distancia' => $this->request->getPost('distancia') ?? null,
			'estado' => $this->request->getPost('estado') ?? null,
			'fecha_entrada' => $this->request->getPost('fecha_entrada') ?? null,
			'fecha_entrega' => $this->request->getPost('fecha_entrega') ?? null,
			'observaciones' => $this->request->getPost('observaciones') ?? null,
			'total_linea' => ($this->request->getPost('n_piezas') && $this->request->getPost('precio_venta')) ? $this->request->getPost('n_piezas') * $this->request->getPost('precio_venta') : null,
		];

		if ($lineaspedidoModel->update($id_lineapedido, $updateData)) {
			$id_pedido = $this->request->getPost('id_pedido');

			if (isset($updateData['estado'])) {
				$procesosPedidoModel->where('id_linea_pedido', $id_lineapedido)
					->set('estado', $updateData['estado'])
					->update();
			}
			$this->actualizarTotalPedido($id_pedido);
			$this->actualizarEstadoPedido($id_pedido);
			if ($this->request->isAJAX()) {
				return $this->response->setJSON(['success' => true, 'message' => 'Línea de pedido actualizada correctamente']);
			} else {
				return redirect()->to(base_url("pedidos/edit/$id_pedido"));
			}
		} else {
			if ($this->request->isAJAX()) {
				return $this->response->setJSON(['success' => false, 'error' => 'No se pudo actualizar la línea de pedido.']);
			} else {
				return redirect()->back()->with('error', 'No se pudo actualizar la línea de pedido.');
			}
		}
	}
	public function mostrarFormularioEditarLineaPedido($id_lineapedido)
	{
		$data = datos_user();
		$db = db_connect($data['new_db']);
		$productosModel = new Productos_model($db);
		$estadoModel = new EstadoModel($db);
		$lineaPedidoModel = new LineaPedido($db);
		$linea_pedido = $lineaPedidoModel->find($id_lineapedido);
		if (!$linea_pedido) {
			return $this->response->setStatusCode(404, 'Línea de pedido no encontrada');
		}
		$data['productos'] = $productosModel->findAll();
		$data['estados'] = $estadoModel->findAll();
		$data['linea_pedido'] = $linea_pedido;
		if ($this->request->isAJAX()) {
			return view('editLineaPedido', $data);
		} else {
			return redirect()->back()->with('error', 'Acción no permitida');
		}
	}
	public function mostrarFormularioAddLineaPedido($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$productosModel = new Productos_model($db);
		$data['productos'] = $productosModel->findAll();
		$data['pedido'] = ['id_pedido' => $id_pedido];
		$fecha_entrada = date('Y-m-d');
		$fecha_entrega = date('Y-m-d', strtotime('+14 days'));
		$data['fecha_entrada'] = $fecha_entrada;
		$data['fecha_entrega'] = $fecha_entrega;
		return view('addLineaPedido', $data);
	}
	public function deleteLinea($id_lineapedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaPedidoModel = new LineaPedido($db);
		$procesosPedidoModel = new ProcesosPedido($db);
		$linea = $lineaPedidoModel->where('id_lineapedido', $id_lineapedido)->first();
		if (!$linea) {
			return redirect()->back()->with('error', 'Línea no encontrada');
		}
		$id_pedido = $linea['id_pedido'];
		$db->transStart();
		$procesosPedidoModel->where('id_linea_pedido', $id_lineapedido)->delete();
		$lineaPedidoModel->delete($id_lineapedido);
		$db->transComplete();
		if ($db->transStatus() === false) {
			return redirect()->back()->with('error', 'No se pudo eliminar la línea del pedido');
		}
		return redirect()->to(base_url('pedidos/edit/' . $id_pedido))->with('success', 'Línea del pedido y procesos asociados eliminados correctamente');
	}

	public function actualizarEstadoPedido($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$builder = $db->table('linea_pedidos');
		$builder->select('estado');
		$builder->where('id_pedido', $id_pedido);
		$query = $builder->get();
		$estados = $query->getResultArray();
		if (empty($estados)) {
			return;
		}
		$estados_array = array_column($estados, 'estado');
		if (count(array_unique($estados_array)) === 1) {
			$nuevo_estado = $estados_array[0];
		} else {
			$nuevo_estado = min($estados_array);
		}
		$pedidoModel = new Pedidos_model($db);
		$pedidoModel->update($id_pedido, ['estado' => $nuevo_estado]);
		return $nuevo_estado;
	}
}
