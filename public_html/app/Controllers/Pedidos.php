<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\Usuarios2_Model;
use App\Models\Pedidos_model;
use App\Models\EstadoModel;
use App\Models\LineaPedido;
use App\Models\Productos_model;

class Pedidos extends BaseControllerGC
{
	protected $idpedido = 0;

	function __construct()
	{
		$this->idpedido = 0;
	}
	public function index()
	{
		$this->todos('estado!=', '8');
	}
	public function enmarcha()
	{
		$this->todos('estado<', '4');
	}
	public function terminados()
	{
		$this->todos('estado=', '4');
	}
	public function entregados()
	{
		$this->todos('estado=', '5');
	}

	//CREAMOS LA PAGINA DE PEDIDOS

	public function todos($coge_estado, $where_estado)
	{
		// Control de login
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

		// Obtener los pedidos con relaciones
		$data['pedidos'] = $pedidoModel->getPedidoWithRelations($coge_estado, $where_estado);

		// Obtener la lista de clientes y usuarios para los filtros
		$data['clientes'] = $clienteModel->findAll();
		$data['usuarios'] = $usuarioModel->findAll();

		// Verificar el nivel de acceso para permitir la eliminación
		if ($nivel_acceso != 9) {
			$data['allow_delete'] = false;
		} else {
			$data['allow_delete'] = true;
		}

		// Cargar la vista pasando los datos
		echo view('mostrarPedido', $data);
	}

	public function add()
	{
		$session = session();
		$data = datos_user();
		$db = db_connect($data['new_db']);
		$clienteModel = new ClienteModel($db);
		$data['clientes'] = $clienteModel->findAll();
		//pasamos los datos del usuario autenticado para que se usen en la vista
		$data['usuario_sesion'] = [
			'id_user' => $data['id_user'],
			'nombre_usuario' => $data['nombre_usuario'],
			'apellidos_usuario' => $data['apellidos_usuario']
		];
		if ($this->request->isAJAX()) {
			return view('add_pedido', $data);  // Retorna la vista del modal
		} else {
			return redirect()->to(base_url('pedidos/enmarcha?modal=add'));
		}
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
		$data = [
			'id_cliente' => $this->request->getPost('id_cliente'),
			'referencia' => $this->request->getPost('referencia'),
			'id_usuario' => $this->request->getPost('id_usuario'),
			'fecha_entrada' => $this->request->getPost('fecha_entrada'),
			'fecha_entrega' => $this->request->getPost('fecha_entrega'),
			'observaciones' => $this->request->getPost('observaciones'),
			'pedido_por' => $data['nombre_usuario']
		];
		if ($pedidoModel->insert($data)) {
			$this->logAction('Pedido', 'Añadir Pedido', $data);
			return redirect()->to(base_url('pedidos/enmarcha'))->with('success', 'Pedido guardado correctamente');
		} else {
			return redirect()->back()->with('error', 'No se pudo guardar el pedido');
		}
	}
	public function edit($id_pedido)
	{
		helper('controlacceso');
		$session = session();
		$data = datos_user();
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);
		$clienteModel = new ClienteModel($db);
		$estadoModel = new EstadoModel($db);
		$productosModel = new Productos_model($db);
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
		// Obtener la lista de clientes y estados
		$clientes = $clienteModel->findAll();
		$estados = $estadoModel->findAll();
		// Pasar los datos a la vista
		$data['productos'] = $productosModel->findAll();
		$data['clientes'] = $clienteModel->findAll();
		$data['estados'] = array_filter($estadoModel->findAll(), function ($estado) {
			return $estado['id_estado'] != 3; // Filtra el estado con id 3
		});

		$data['pedido'] = $pedido;
		$data['lineas_pedido'] = $lineas_pedido;
		// Cargar la vista de edición del pedido
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
			'id_usuario' => $this->request->getPost('id_usuario'),
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
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);
		$lineaPedidoModel = new LineaPedido($db);
		$db->transStart();
		// Eliminar todas las líneas asociadas al pedido
		$lineaPedidoModel->where('id_pedido', $id_pedido)->delete();
		// Eliminar el pedido
		$pedidoModel->delete($id_pedido);
		$db->transComplete();
		if ($db->transStatus() === false) {
			return redirect()->back()->with('error', 'No se pudo eliminar el pedido');
		}
		return redirect()->to(base_url('pedidos/enmarcha'))->with('success', 'Pedido eliminado correctamente');
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
		// Obtener todas las líneas de un pedido
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
			'nom_base' => $this->request->getPost('nom_base') ?: null,
			'med_inicial' => $this->request->getPost('med_inicial') ?: null,
			'med_final' => $this->request->getPost('med_final') ?: null,
			'lado' => $this->request->getPost('lado') ?: null,
			'distancia' => $this->request->getPost('distancia') ?: null,
			'observaciones' => $this->request->getPost('observaciones') ?: null,
			'fecha_entrada' => $fecha_entrada,
			'fecha_entrega' => $fecha_entrega,
			'n_piezas' => $n_piezas,
			'precio_venta' => $precio_venta,
			// Calcular el total_linea
			'total_linea' => $n_piezas * $precio_venta
		];

		// Insertar la nueva línea de pedido en la base de datos
		if ($lineaspedidoModel->insert($data)) {
			$this->actualizarTotalPedido($data['id_pedido']);
			$this->actualizarEstadoPedido($data['id_pedido']);
			return $this->response->setJSON(['success' => 'Línea de pedido añadida correctamente']);
		} else {
			return $this->response->setJSON(['error' => 'No se pudo añadir la línea de pedido']);
		}
	}
	// Actualizar línea de pedido
	public function updateLineaPedido($id_lineapedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaspedidoModel = new LineaPedido($db);
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
			$this->actualizarTotalPedido($id_pedido);
			$this->actualizarEstadoPedido($id_pedido);
			return $this->response->setJSON(['success' => 'Línea de pedido actualizada correctamente']);
		} else {
			return $this->response->setJSON(['error' => 'No se pudo actualizar la línea de pedido']);
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
		$linea = $lineaPedidoModel->where('id_lineapedido', $id_lineapedido)->first();
		if (!$linea) {
			return redirect()->back()->with('error', 'Línea no encontrada');
		}
		$id_pedido = $linea['id_pedido'];
		$db->transStart();
		$lineaPedidoModel->delete($id_lineapedido);
		$db->transComplete();
		if ($db->transStatus() === false) {
			return redirect()->back()->with('error', 'No se pudo eliminar la línea del pedido');
		}
		return redirect()->to(base_url('pedidos/edit/' . $id_pedido))->with('success', 'Línea del pedido eliminada correctamente');
	}
	public function actualizarEstadoPedido($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaPedidoModel = new LineaPedido($db);
		$builder = $db->table('linea_pedidos');
		$builder->select('estado');
		$builder->where('id_pedido', $id_pedido);
		$query = $builder->get();
		$estados = $query->getResultArray();
		if (empty($estados)) {
			return;
		}
		// Extraer los valores numéricos de los estados
		$estados_array = array_column($estados, 'estado');
		// Si todos los estados son iguales, usar ese estado
		if (count(array_unique($estados_array)) === 1) {
			$nuevo_estado = $estados_array[0];
		} else {
			// Si los estados son diferentes, tomar el más bajo (numéricamente)
			$nuevo_estado = min($estados_array);
		}
		// Actualizar el estado del pedido en la tabla 'pedidos'
		$pedidoModel = new Pedidos_model($db);
		$pedidoModel->update($id_pedido, ['estado' => $nuevo_estado]);
		return $nuevo_estado;
	}
}
