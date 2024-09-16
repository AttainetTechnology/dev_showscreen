<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\Usuarios2_Model;
use App\Models\Pedidos_model;

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
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$clienteModel = new ClienteModel($db);
		$usuarioModel = new Usuarios2_Model($db);

		$data['clientes'] = $clienteModel->findAll();
		$data['usuario_html'] = $this->guarda_usuario();

		// Si es una solicitud AJAX, devolver solo el contenido del modal
		if ($this->request->isAJAX()) {
			echo view('add_pedido', $data); // Solo el contenido del modal
		} else {
			// Si no es AJAX, redirigir a la página en marcha con el parámetro para abrir el modal
			return redirect()->to(base_url('pedidos/enmarcha?modal=add'));
		}
	}
	function guarda_usuario()
	{
		$datos = new Usuarios2_Model();
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

	public function save()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);

		$data = [
			'id_cliente' => $this->request->getPost('id_cliente'),
			'referencia' => $this->request->getPost('referencia'),
			'id_usuario' => $this->request->getPost('id_usuario'),
			'fecha_entrada' => $this->request->getPost('fecha_entrada'),
			'fecha_entrega' => $this->request->getPost('fecha_entrega'),
			'observaciones' => $this->request->getPost('observaciones'),
		];

		if ($pedidoModel->insert($data)) {
			// Obtener el ID del pedido recién insertado
			$insertId = $pedidoModel->insertID();

			// Registrar la acción en el log
			$this->logAction('Pedido', 'Añadir Pedido', $data);

			// Redirigir a la página en marcha
			return redirect()->to(base_url('pedidos/enmarcha'));
		} else {
			return redirect()->back()->with('error', 'No se pudo guardar el pedido');
		}
	}
}
