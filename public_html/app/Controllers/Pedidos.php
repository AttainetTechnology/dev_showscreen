<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\Usuarios2_Model;
use App\Models\Pedidos_model;
use App\Models\EstadoModel;
use App\Models\LineaPedido;

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

		// Cargar los clientes
		$data['clientes'] = $clienteModel->findAll();

		// Aquí pasamos los datos del usuario autenticado para que se usen en la vista
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
				// Construye el array de forma clara con el id como clave y el nombre completo como valor
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
		// Control de login
		helper('controlacceso');
		$session = session();
		$data = datos_user();
		$db = db_connect($data['new_db']);
		// Cargar el modelo de pedidos, clientes, estados y líneas de pedido
		$pedidoModel = new Pedidos_model($db);
		$clienteModel = new ClienteModel($db);
		$estadoModel = new EstadoModel($db);
		$lineaspedidoModel = new LineaPedido($db);  // Modelo de líneas de pedido
		// Obtener el pedido actual a editar
		$pedido = $pedidoModel->findPedidoWithUser($id_pedido);
		if (!$pedido) {
			return redirect()->back()->with('error', 'Pedido no encontrado');
		}
		// Obtener las líneas de pedido asociadas
		$lineas_pedido = $lineaspedidoModel->where('id_pedido', $id_pedido)->findAll();
		// Obtener la lista de clientes y estados
		$data['clientes'] = $clienteModel->findAll();
		$data['estados'] = $estadoModel->findAll();
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

	public function entregar($id_pedido)
	{

		$Lineaspedido_model = model('App\Models\Lineaspedido_model');
		$Lineaspedido_model->entrega_lineas($id_pedido);
		// TABLA LOG
		$this->logAction('Pedidos', 'Entrega pedido, ID: ' . $id_pedido, []);
		return redirect()->to('pedidos/edit/' . $id_pedido);
	}
	public function anular($id_pedido)
	{
		$Lineaspedido_model = model('App\Models\Lineaspedido_model');
		$Lineaspedido_model->anular_lineas($id_pedido);
		// TABLA LOG
		$this->logAction('Pedidos', 'Anular pedido, ID: ' . $id_pedido, []);
		return redirect()->to('pedidos/enmarcha');
	}

	// LOGICA LINEA PEDIDO
	// Mostrar las líneas del pedido
	public function mostrarLineasPedido($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaspedidoModel = new LineaPedido($db);
		// Obtener todas las líneas de un pedido
		$lineas_pedido = $lineaspedidoModel->where('id_pedido', $id_pedido)->findAll();
		return view('mostrarLineasPedido', ['lineas_pedido' => $lineas_pedido, 'pedido_id' => $id_pedido]);
	}

	// Actualizar línea de pedido
	public function updateLineaPedido($id_lineapedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaspedidoModel = new LineaPedido($db);
		// Recoge los datos del formulario o usa null si no están presentes
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
		// Actualiza la línea de pedido en la base de datos
		if ($lineaspedidoModel->update($id_lineapedido, $updateData)) {
			return $this->response->setJSON(['success' => 'Línea de pedido actualizada correctamente']);
		} else {
			return $this->response->setJSON(['error' => 'No se pudo actualizar la línea de pedido']);
		}
	}
}
