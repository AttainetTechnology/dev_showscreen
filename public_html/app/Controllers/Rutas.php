<?php

namespace App\Controllers;

use App\Models\Rutas_model;

class Rutas extends BaseController
{

	public function todas($coge_estado, $where_estado)
	{
		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Rutas');
		$data['amiga'] = $this->getBreadcrumbs();

		return view('mostrarRutas', [
			'estado' => json_encode([
				'condicion' => $coge_estado,
				'valor' => $where_estado,
			]),
			'amiga' => $data['amiga']  // Pasar amiga a la vista
		]);
	}



	public function index()
	{
		return $this->todas('estado_ruta!=', '9');
	}

	public function enmarcha()
	{
		return $this->todas('estado_ruta!=', '2');
	}


	public function getRutas()
	{
		$coge_estado = $this->request->getJSON()->coge_estado ?? null;
		$where_estado = $this->request->getJSON()->where_estado ?? null;

		if ($coge_estado === null || $where_estado === null) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Los parámetros "coge_estado" y "where_estado" son requeridos.'
			])->setStatusCode(400);
		}

		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);

		try {
			$rutas = $model->getRutasWithDetails($coge_estado, $where_estado);

			// Formatear fechas
			foreach ($rutas as &$ruta) {
				$ruta['fecha_ruta'] = date('d-m-y', strtotime($ruta['fecha_ruta']));
			}

			return $this->response->setJSON(['success' => true, 'data' => $rutas]);
		} catch (\Exception $e) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Error al obtener las rutas: ' . $e->getMessage()
			])->setStatusCode(500);
		}
	}


	public function add_ruta()
	{
		// Añadir breadcrumb
		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Rutas', base_url('/rutas'));
		$this->addBreadcrumb('Añadir ruta');
		$data['amiga'] = $this->getBreadcrumbs();

		// Obtener datos de sesión
		$userData = usuario_sesion();
		$db = db_connect($userData['new_db']);

		// Inicializar los modelos
		$clientesModel = new \App\Models\ClienteModel($db);
		$poblacionesModel = new \App\Models\PoblacionesModel($db);

		// Obtener datos de clientes, poblaciones y transportistas
		$clientes = $clientesModel->findAll();
		$poblaciones = $poblacionesModel->findAll();
		$transportistas = $this->getTransportistas();

		// Formatear la fecha actual
		$fechaHoy = date('d-m-y');

		// Pasar todos los datos necesarios a la vista, incluyendo 'amiga'
		return view('add_ruta', [
			'amiga' => $data['amiga'],  // Pasamos 'amiga' aquí
			'clientes' => $clientes,
			'poblaciones' => $poblaciones,
			'transportistas' => $transportistas,
			'fechaHoy' => $fechaHoy
		]);
	}

	public function addRuta()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);
		$data = $this->request->getPost();

		if ($model->insert($data)) {
			return redirect()->to('/rutas/enmarcha');
		} else {
			return redirect()->back()->with('error', 'Error al añadir la ruta');
		}
	}

	public function obtenerNombreTransportistaPorId($id_transportista)
	{
		$db_cliente = db_connect(usuario_sesion()['new_db']);
		$builder = $db_cliente->table('users');
		$builder->select('nombre_usuario, apellidos_usuario');
		$builder->where('id', $id_transportista);
		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			$result = $query->getRow();
			return $result->nombre_usuario . ' ' . $result->apellidos_usuario;
		}
	}
	public function editar_ruta($id)
	{

		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Rutas', base_url('/rutas'));
		$this->addBreadcrumb('Editar ruta');
		$data['amiga'] = $this->getBreadcrumbs();

		// Obtener datos de sesión
		$userData = usuario_sesion();
		$db = db_connect($userData['new_db']);
		$model = new Rutas_model($db);

		try {
			$ruta = $model->getRutaById($id);
			if (!$ruta) {
				throw new \Exception('Ruta no encontrada');
			}

			// Inicializar otros modelos
			$clientesModel = new \App\Models\ClienteModel($db);
			$poblacionesModel = new \App\Models\PoblacionesModel($db);

			$clientes = $clientesModel->findAll();
			$poblaciones = $poblacionesModel->findAll();
			$transportistas = $this->getTransportistas();

			return view('editar_rutas', [
				'amiga' => $data['amiga'],
				'ruta' => $ruta,
				'clientes' => $clientes,
				'poblaciones' => $poblaciones,
				'transportistas' => $transportistas
			]);
		} catch (\Exception $e) {
			return redirect()->back()->with('error', $e->getMessage());
		}
	}

	public function updateRuta($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);

		$updatedData = $this->request->getPost();

		try {
			if ($model->update($id, $updatedData)) {
				return redirect()->to('/rutas')->with('success', 'Ruta actualizada correctamente');
			} else {
				throw new \Exception('Error al actualizar la ruta');
			}
		} catch (\Exception $e) {
			return redirect()->back()->with('error', $e->getMessage());
		}
	}

	public function deleteRuta($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);

		if ($model->delete($id)) {
			return $this->response->setJSON(['success' => true, 'message' => 'Ruta eliminada correctamente']);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la ruta']);
		}
	}

	function getTransportistas()
	{

		$db_original = \Config\Database::connect();
		$data = usuario_sesion();
		$db_cliente = db_connect($data['new_db']);
		$builder_original = $db_original->table('users');
		$builder_original->select('id, nivel_acceso');
		$builder_original->where('nivel_acceso', '1');
		$query_original = $builder_original->get();

		if (!$query_original) {
			log_message('error', 'Error en la consulta a la base de datos original: ' . $db_original->error());
			return [];
		}

		$transportistas_original = $query_original->getResultArray();

		$builder_cliente = $db_cliente->table('users');
		$builder_cliente->select('id, nombre_usuario, apellidos_usuario');
		$query_cliente = $builder_cliente->get();

		if (!$query_cliente) {
			log_message('error', 'Error en la consulta a la base de datos del cliente: ' . $db_cliente->error());
			return [];
		}

		$transportistas_cliente = $query_cliente->getResultArray();

		$transport = [];
		foreach ($transportistas_original as $trans_original) {
			foreach ($transportistas_cliente as $trans_cliente) {
				if ($trans_original['id'] == $trans_cliente['id'] && $trans_original['nivel_acceso'] == '1') {
					$transport[$trans_cliente['id']] = $trans_cliente['nombre_usuario'] . " " . $trans_cliente['apellidos_usuario'];
				}
			}
		}

		return $transport;
	}
	public function preparado($id_ruta)
	{
		$data = usuario_sesion(); 
		$db = db_connect($data['new_db']);
		$rutas_model = new Rutas_model($db);

		$data = [
			'estado_ruta' => '0' 
		];
		$rutas_model->update($id_ruta, $data);
		$this->enmarcha();
		$post_array = ['action' => 'Actualizar "No preparado"', 'id_ruta' => $id_ruta];
		$this->logAction('Rutas', 'Actualizar "No preparado"', $post_array);
	}

	public function cambiarEstado($idRuta)
	{
		$nuevoEstado = $this->request->getJSON()->estado;
		if (empty($nuevoEstado) || !in_array($nuevoEstado, ['0', '1', '2', '3'])) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Estado inválido'
			])->setStatusCode(400);
		}

		if ($nuevoEstado == '2') {
			$nuevoEstado = '0';
		}

		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);
	
		try {

			$model->update($idRuta, ['estado_ruta' => $nuevoEstado]);
	
			return $this->response->setJSON(['success' => true]);
		} catch (\Exception $e) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Error al actualizar el estado: ' . $e->getMessage()
			])->setStatusCode(500);
		}
	}
	
}
