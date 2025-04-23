<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\Usuarios2_Model;
use App\Models\Pedidos_model;
use App\Models\EstadoModel;
use App\Models\LineaPedido;
use App\Models\Productos_model;
use App\Models\Lineaspedido_model;
use App\Models\ProcesosPedido;
use App\Models\RelacionProcesoUsuario_model;

class Pedidos extends BaseController
{
	protected $idpedido = 0;

	function __construct()
	{
		$this->idpedido = 0;
	}
	public function index()
	{

		$redirect = check_access_level();

		$redirectUrl = session()->getFlashdata('redirect');
		if ($redirect && is_string($redirectUrl)) {
			return redirect()->to($redirectUrl);
		}

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

		$pedidoModel = new Pedidos_model($db);
		$clienteModel = new ClienteModel($db);
		$usuarioModel = new Usuarios2_Model($db);

		$data['pedidos'] = $pedidoModel->getPedidoWithRelations($coge_estado, $where_estado);

		$data['clientes'] = $clienteModel->findAll();
		$data['users'] = $usuarioModel->findAll();
		$estadoModel = new EstadoModel($db);
		$data['estados'] = $estadoModel->findAll();

		$data['allow_delete'] = ($nivel_acceso == 9);
		$data['amiga'] = $this->getBreadcrumbs();

		echo view('mostrarPedido', $data);
	}
	public function add()
	{
		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Pedidos', base_url('/pedidos/enmarcha'));
		$this->addBreadcrumb('Añadir Pedido');
		$data = datos_user();
		$db = db_connect($data['new_db']);

		$clienteModel = new ClienteModel($db);
		$data['clientes'] = $clienteModel->findAll();

		$id_usuario = $data['id_user'];

		$builder = $db->table('users');
		$builder->select('nombre_usuario, apellidos_usuario');
		$builder->where('id', $id_usuario);
		$builder->where('user_activo', '1');
		$query = $builder->get();
		$usuario = $query->getRow();
		$data['amiga'] = $this->getBreadcrumbs();

		$data['usuario_sesion'] = $usuario ? [
			'id_user' => $id_usuario,
			'nombre_usuario' => $usuario->nombre_usuario,
			'apellidos_usuario' => $usuario->apellidos_usuario
		] : [
			'id_user' => $id_usuario,
			'nombre_usuario' => 'Usuario desconocido',
			'apellidos_usuario' => ''
		];

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

		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);

		if (
			!$this->validate([
				'id_cliente' => 'required',
				'fecha_entrada' => 'required',
				'fecha_entrega' => 'required',
			])
		) {
			return redirect()->back()->with('error', 'Faltan datos obligatorios');
		}

		$builder = $db->table('users');
		$builder->select('nombre_usuario, apellidos_usuario');
		$builder->where('id', $data['id_user']);
		$builder->where('user_activo', '1');
		$query = $builder->get();
		$usuario = $query->getRow();

		$nombre_usuario = $usuario ? $usuario->nombre_usuario . ' ' . $usuario->apellidos_usuario : 'test';

		$pedidoData = [
			'id_cliente' => $this->request->getPost('id_cliente'),
			'referencia' => $this->request->getPost('referencia'),
			'id_usuario' => $this->request->getPost('id_usuario'),
			'fecha_entrada' => $this->request->getPost('fecha_entrada'),
			'fecha_entrega' => $this->request->getPost('fecha_entrega'),
			'observaciones' => $this->request->getPost('observaciones'),
			'pedido_por' => $nombre_usuario
		];

		$id_pedido = $pedidoModel->insert($pedidoData, true);

		if ($id_pedido) {
			$this->logAction('Pedido', 'Añadir pedido, ID: ' . $id_pedido, $pedidoData);
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

		$pedido = $pedidoModel->findPedidoWithUser($id_pedido);
		$this->actualizarEstadoPedido($id_pedido);

		if (!$pedido) {
			return redirect()->back()->with('error', 'Pedido no encontrado');
		}

		$builder = $db->table('linea_pedidos');
		$builder->select('linea_pedidos.*, productos.nombre_producto');
		$builder->join('productos', 'productos.id_producto = linea_pedidos.id_producto');
		$builder->where('linea_pedidos.id_pedido', $id_pedido);
		$query = $builder->get();
		$lineas_pedido = $query->getResultArray();

		$data['productos'] = $productosModel->findAll();
		$data['users'] = $usuarioModel->findAll();
		$data['clientes'] = $clienteModel->findAll();
		$data['estados'] = array_filter($estadoModel->findAll(), function ($estado) {
			return $estado['id_estado'] != 3;
		});
		$data['amiga'] = $this->getBreadcrumbs();
		$data['pedido'] = $pedido;
		$data['lineas_pedido'] = $lineas_pedido;
		return view('editPedido', $data);
	}


	public function duplicar($id_pedido)
	{
		$session = session();

		$data = datos_user();
		$usuario_id = $data['id_user'];
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);
		$pedido = (array) $pedidoModel->find($id_pedido);

		if (!$pedido) {
			return redirect()->back()->with('error', 'Pedido no encontrado');
		}

		$nuevoPedido = [
			'id_cliente' => $pedido['id_cliente'],
			'referencia' => '[DUPLICADO] ' . $pedido['referencia'],
			'observaciones' => $pedido['observaciones'],
			'fecha_entrada' => date('Y-m-d'),
			'fecha_entrega' => date('Y-m-d', strtotime('+14 days')),
			'estante' => $pedido['estante'],
			'id_usuario' => $usuario_id,
			'total_pedido' => $pedido['total_pedido'],
			'detalles' => $pedido['detalles'],
			'estado' => 0,
			'pedido_por' => $pedido['pedido_por'],
			'representante' => $pedido['representante'],
			'bt_imprimir' => $pedido['bt_imprimir']
		];
		$nuevoPedidoId = $pedidoModel->insert($nuevoPedido);

		if (!$nuevoPedidoId) {
			return redirect()->back()->with('error', 'Error al duplicar el pedido');
		}

		$lineaPedidoModel = new LineaPedido($db);
		$lineas = $lineaPedidoModel->where('id_pedido', $id_pedido)->findAll();

		foreach ($lineas as $linea) {
			$nuevaLinea = [
				'id_pedido' => $nuevoPedidoId,
				'fecha_entrada' => date('Y-m-d'),
				'fecha_entrega' => date('Y-m-d', strtotime('+14 days')),
				'id_producto' => $linea['id_producto'],
				'n_piezas' => $linea['n_piezas'],
				'nom_base' => $linea['nom_base'],
				'nom_inserto' => $linea['nom_inserto'],
				'tono' => $linea['tono'],
				'cal' => $linea['cal'],
				'torelo' => $linea['torelo'],
				'med_inicial' => $linea['med_inicial'],
				'med_final' => $linea['med_final'],
				'lado' => $linea['lado'],
				'distancia' => $linea['distancia'],
				'observaciones' => $linea['observaciones'],
				'id_usuario' => $usuario_id,
				'unidades' => $linea['unidades'],
				'precio_venta' => $linea['precio_venta'],
				'manipulacion' => $linea['manipulacion'],
				'descuento' => $linea['descuento'],
				'add_linea' => $linea['add_linea'],
				'total_linea' => $linea['total_linea'],
				'estado' => 0
			];
			$lineaPedidoModel->insert($nuevaLinea);
		}
		$this->logAction('Pedidos', 'Duplica Pedido, ID: ' . $id_pedido, []);
		return redirect()->to(base_url('pedidos/edit/' . $nuevoPedidoId))->with('success', 'Pedido duplicado correctamente');
	}

	public function update($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$pedidoModel = new Pedidos_model($db);

		if (
			!$this->validate([
				'id_cliente' => 'required',
				'fecha_entrada' => 'required',
				'fecha_entrega' => 'required',
			])
		) {
			return redirect()->back()->with('error', 'Faltan datos obligatorios');
		}

		$pedido = $pedidoModel->find($id_pedido);
		if (!$pedido) {
			return redirect()->back()->with('error', 'Pedido no encontrado');
		}
		$updateData = [
			'id_cliente' => $this->request->getPost('id_cliente'),
			'referencia' => $this->request->getPost('referencia'),
			'id_usuario' => $data['id_user'],
			'fecha_entrada' => $this->request->getPost('fecha_entrada'),
			'fecha_entrega' => $this->request->getPost('fecha_entrega'),
			'observaciones' => $this->request->getPost('observaciones'),
		];

		$updateData['estado'] = $pedido->estado;

		if ($pedidoModel->update($id_pedido, $updateData)) {
			$this->logAction('Pedidos', 'Edita pedido, ID: ' . $id_pedido, []);
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
		$this->logAction('Pedidos', 'Eliminado pedido, ID: ' . $id_pedido, []);
		return redirect()->to(base_url('pedidos/enmarcha'))->with('success', 'Pedido y sus líneas asociadas eliminados correctamente');
	}

	public function actualizarTotalPedido($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$builder = $db->table('linea_pedidos');
		$builder->selectSum('total_linea', 'suma_total');
		$builder->where('id_pedido', $id_pedido);
		$builder->where('estado !=', 6);
		$query = $builder->get();
		$resultado = $query->getRow();
		$totalPedido = $resultado->suma_total ?? 0;
		$pedidoModel = new Pedidos_model($db);
		$pedidoModel->update($id_pedido, ['total_pedido' => $totalPedido]);
		return $totalPedido;
	}
	public function entregar($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$Lineaspedido_model = model('App\Models\Lineaspedido_model');
		$Lineaspedido_model->entrega_lineas($id_pedido);
		$RelacionProcesosUsuario_model = new RelacionProcesoUsuario_model($db);
		if ($RelacionProcesosUsuario_model !== null) {
			$registros = $RelacionProcesosUsuario_model
				->where('id_pedido', $id_pedido)
				->findAll();
			$dataAgrupada = [];
			foreach ($registros as $registro) {
				$id_linea_pedido = $registro['id_linea_pedido'];

				if (!isset($dataAgrupada[$id_linea_pedido])) {
					$dataAgrupada[$id_linea_pedido] = [];
				}
				$Usuarios_model = new \App\Models\Usuarios2_model($db);
				$usuario = $Usuarios_model->find($registro['id_usuario']);
				$usuario_nombre = $usuario ? $usuario['nombre_usuario'] . ' ' . $usuario['apellidos_usuario'] : 'Usuario desconocido';
				$Maquinas_model = new \App\Models\Maquinas($db);
				$maquina = $Maquinas_model->find($registro['id_maquina']);
				$maquina_nombre = $maquina ? $maquina['nombre'] : 'Máquina desconocida';
				$dataAgrupada[$id_linea_pedido][] = [
					'usuario' => $usuario_nombre,
					'buenas' => $registro['buenas'],
					'malas' => $registro['malas'],
					'repasadas' => $registro['repasadas'],
					'maquina' => $maquina_nombre,
				];
			}
			$RelacionProcesosUsuario_model->where('id_pedido', $id_pedido)->delete();
			$Lineaspedido_model = new Lineaspedido_model($db);
			$fecha_hoy = date('Y-m-d');

			foreach ($dataAgrupada as $id_linea_pedido => $datos) {
				$escandallo = '';
				foreach ($datos as $dato) {
					$escandallo .= "[fecha: $fecha_hoy, Usuario: {$dato['usuario']}, B: {$dato['buenas']}, M: {$dato['malas']}, R: {$dato['repasadas']}, Maquina: {$dato['maquina']}]".PHP_EOL;
				}
				
				$updateResult = $Lineaspedido_model->update($id_linea_pedido, ['escandallo' => $escandallo]);
			}
		} else {
			log_message('error', 'No se pudo cargar el modelo RelacionProcesosUsuario_model');
		}
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
		if (
			!$this->validate([
				'id_producto' => 'required',
			])
		) {
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
		$id_pedido = $this->request->getPost('id_pedido');
		if ($lineaspedidoModel->insert($data)) {
			$this->actualizarTotalPedido($data['id_pedido']);
			$this->actualizarEstadoPedido($data['id_pedido']);
			$this->logAction('Pedidos', 'Añade linea pedido, Id pedido: ' . $id_pedido, []);
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
		$relacionProcesosUsuariosModel = model('App\Models\RelacionProcesoUsuario_model', false, $db);

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

			if (isset($updateData['estado']) && $updateData['estado'] == 4) {
				$procesosPedidoModel->where('id_linea_pedido', $id_lineapedido)
					->set('estado', $updateData['estado'])
					->update();
			}
	
			if (isset($updateData['estado']) && $updateData['estado'] == 5) {
				$procesosPedidoModel->where('id_linea_pedido', $id_lineapedido)
					->set('estado', $updateData['estado'])
					->update();

				// Elimina los registros en relacion_proceso_usuario si la línea se marca como 'entregado'
				$relacionProcesosUsuariosModel->where('id_linea_pedido', $id_lineapedido)->delete();
			}

			$this->actualizarTotalPedido($id_pedido);
			$this->actualizarEstadoPedido($id_pedido);
			if ($this->request->isAJAX()) {
				$this->logAction('Pedidos', 'Edita linea pedido, ID: ' . $id_lineapedido, []);
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

		$isEstadoEnCola = ($linea_pedido['estado'] === 'en cola');

		$data['productos'] = $productosModel->findAll();
		$data['estados'] = $estadoModel->findAll();
		$data['linea_pedido'] = $linea_pedido;
		$data['isEstadoEnCola'] = $isEstadoEnCola;

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
		$session = session();
		$session_data = $session->get('logged_in');
		$nivel_acceso = $session_data['nivel'];

		$lineaPedidoModel = new LineaPedido($db);
		$procesosPedidoModel = new ProcesosPedido($db);
		$relacionProcesoUsuarioModel = $db->table('relacion_proceso_usuario');

		$linea = $lineaPedidoModel->where('id_lineapedido', $id_lineapedido)->first();

		if (!$linea) {
			return redirect()->back()->with('error', 'Línea no encontrada');
		}

		$id_pedido = $linea['id_pedido'];

		if ($nivel_acceso != 9) {
			return $this->anularLinea($id_lineapedido, $id_pedido);
		}

		$db->transStart();

		$relacionProcesoUsuarioModel->where('id_linea_pedido', $id_lineapedido)->delete();

		$procesosPedidoModel->where('id_linea_pedido', $id_lineapedido)->delete();

		$lineaPedidoModel->delete($id_lineapedido);

		$db->transComplete();

		if ($db->transStatus() === false) {
			return redirect()->back()->with('error', 'No se pudo eliminar la línea del pedido');
		}

		$this->logAction('Pedidos', 'Elimina Línea pedido, ID: ' . $id_lineapedido, []);

		return redirect()->to(base_url('pedidos/edit/' . $id_pedido))->with('success', 'Línea del pedido, procesos asociados y registros en relacion_proceso_usuario eliminados correctamente');
	}

	public function anularLinea($id_lineapedido, $id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$lineaPedidoModel = new LineaPedido($db);
		$relacionProcesoUsuarioModel = $db->table('relacion_proceso_usuario');

		$linea = $lineaPedidoModel->where('id_lineapedido', $id_lineapedido)->first();

		if (!$linea) {
			return redirect()->to(base_url('pedidos/edit/' . $id_pedido))->with('error', 'La línea de pedido no existe.');
		}

		$db->transStart();

		// Anular la línea de pedido
		$update = $lineaPedidoModel->update($id_lineapedido, ['estado' => 6]);

		if ($update) {
			// Eliminar registros en relacion_proceso_usuario
			$relacionProcesoUsuarioModel->where('id_linea_pedido', $id_lineapedido)->delete();

			$totalPedido = $this->actualizarTotalPedido($id_pedido);
			$this->logAction('Pedidos', 'Anula Línea pedido, ID: ' . $id_lineapedido, []);

			$db->transComplete();
			return redirect()->to(base_url('pedidos/edit/' . $id_pedido))->with('success', 'Línea de pedido anulada correctamente, registros en relacion_proceso_usuario eliminados y total del pedido actualizado. Total: ' . $totalPedido);
		} else {
			$db->transRollback();
			return redirect()->to(base_url('pedidos/edit/' . $id_pedido))->with('error', 'No se pudo anular la línea de pedido');
		}
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
	$unicos = array_unique($estados_array);
	sort($unicos);

	// Si hay al menos una línea en estado 2 ("Material recibido") o 3 ("En Máquinas"), el pedido se pone como estado 3
	if (in_array(2, $unicos) || in_array(3, $unicos)) {
		$nuevo_estado = 3;
	} elseif (count($unicos) === 1) {
		$nuevo_estado = $unicos[0];
	} elseif ($unicos === [5, 6]) {
		$nuevo_estado = 5; // entregado
	} elseif ($unicos === [4, 5, 6] || $unicos === [4, 5] || $unicos === [4, 6]) {
		$nuevo_estado = 4; // terminado
	} else {
		$activos = array_filter($unicos, fn($e) => $e != 6); // eliminamos anulados
		$nuevo_estado = min($activos);
	}

	$pedidoModel = new Pedidos_model($db);
	$pedidoModel->update($id_pedido, ['estado' => $nuevo_estado]);

	return $nuevo_estado;
}


}
